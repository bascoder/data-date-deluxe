<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 * @property Profiel profiel
 */
class Like extends CI_Model
{
    const GEGEVEN_LIKE = 'Crush 😍';
    const ONTVANGEN_LIKE = 'Volger 💐';
    const WEDERZIJDSE_LIKE = "Match 💑";
    const GEEN_LIKE = 'Nog niet ontdekt 👀';

    // image lib ondersteund alleen ASCII
    private $status_overlay_mapping = array(
        self::GEEN_LIKE => 'New',
        self::GEGEVEN_LIKE => 'Crush',
        self::ONTVANGEN_LIKE => 'Volger',
        self::WEDERZIJDSE_LIKE => 'Match <3'
    );

    private $query_mapping = array(
        self::GEGEVEN_LIKE => 'query_mijn_gegeven_likes',
        self::ONTVANGEN_LIKE => 'query_mijn_ontvangen_likes',
        self::WEDERZIJDSE_LIKE => 'query_wederzijdse_likes'
    );

    /**
     * @param $status string
     * @return array
     */
    public function get_status_overlay_text($status)
    {
        return $this->status_overlay_mapping[$status];
    }

    public function insert($liker, $liked)
    {
        if ($liker === NULL || $liked === NULL) {
            throw new InvalidArgumentException('liker en liked moeten objecten zijn');
        }

        $liker_id = intval($liker->pid);
        $liked_id = intval($liked->pid);
        if ($liked_id === $liker_id) {
            throw new InvalidArgumentException('Je kan jezelf niet liken');
        }

        $status = $this->db->insert('Like', array(
            'liker_id' => $liker_id,
            'liked_id' => $liked_id
        ));

        return $status;
    }

    public function query_by_type($type, $limit = PHP_INT_MAX, $offset = 0)
    {
        if (!is_integer($limit)) {
            throw new InvalidArgumentException('Limit moet een int zijn');
        }
        if (!$this->is_relatie_type_valid($type)) {
            throw new InvalidArgumentException('Type moet een valid relatie type zijn');
        }

        // roep dynamisch bijhorend method aan
        $method = $this->query_mapping[$type];
        $results = $this->$method($limit, $offset);
        if ($results === FALSE) {
            log_message('error', $this->db->display_error());
            throw new Exception($this->db->display_error());
        }

        return $results;
    }

    public function get_like_status($pid_other)
    {
        $profiel = $this->get_profiel_or_throw();
        $pid_mine = intval($profiel->pid);
        $pid_other = intval($pid_other);

        //wederzijds
        $sql = "SELECT COUNT(*) as num_count
                FROM Like my_like
                  INNER JOIN `Like` other_like ON other_like.liked_id = my_like.liker_id
                                                  AND other_like.liker_id = my_like.liked_id
                WHERE my_like.liker_id = ?
                  AND other_like.liker_id = ?;";

        $result = $this->db->query($sql, array($pid_mine, $pid_other));
        if (!$result) return FALSE;
        $count = $result->row()->num_count;

        if ($count != 0) {
            return self::WEDERZIJDSE_LIKE;
        }

        //gegeven
        $sql = "SELECT COUNT(*) AS num_count
                FROM `Like` L
                WHERE L.liker_id = ?
                  AND L.liked_id = ?;";
        $result = $this->db->query($sql, array($pid_mine, $pid_other));
        if (!$result) return FALSE;
        $count = $result->row()->num_count;

        if ($count != 0) {
            return self::GEGEVEN_LIKE;
        }

        //ontvangen
        $sql = "SELECT COUNT(*) AS num_count
                FROM `Like` L
                WHERE L.liked_id = ?
                  AND L.liker_id = ?;";
        $result = $this->db->query($sql, array($pid_mine, $pid_other));
        if (!$result) return FALSE;
        $count = $result->row()->num_count;

        if ($count != 0) {
            return self::ONTVANGEN_LIKE;
        }

        // anders niks
        return self::GEEN_LIKE;
    }

    public function query_mijn_gegeven_likes($limit = PHP_INT_MAX, $offset = 0)
    {
        $profiel = $this->get_profiel_or_throw();

        // select gegeven likes - wederzijds
        $sql = "SELECT other.liked_id
                FROM Like other
                WHERE other.liker_id = ?
                    AND other.liked_id NOT IN (
                      SELECT other2.liked_id
                      FROM Like other2
                      JOIN Like me ON me.liked_id = other2.liker_id
                        AND me.liker_id = other2.liked_id
                        AND me.liked_id = ?
                    )
                LIMIT ?,?;";

        $query = $this->db->query($sql,
            array($profiel->pid, $profiel->pid, intval($offset), intval($limit)));
        if (!$query) {
            return FALSE;
        }

        $liked_profielen = [];
        $like = $query->row();
        if (isset($like)) {
            do {
                $liked = $this->profiel->query_by_id($like->liked_id);
                array_push($liked_profielen, $liked);
            } while ($like = $query->next_row());
        }
        return $liked_profielen;
    }

    public function query_mijn_ontvangen_likes($limit = PHP_INT_MAX, $offset = 0)
    {
        $profiel = $this->get_profiel_or_throw();

        // ontvangen likes - wederzijdse likes
        $sql = "SELECT other.liker_id
                FROM Like other
                WHERE other.liked_id = ?
                    AND other.liker_id NOT IN (
                      SELECT other2.liker_id
                      FROM Like other2
                      JOIN Like me ON me.liked_id = other2.liker_id
                        AND me.liker_id = other2.liked_id
                        AND me.liker_id = ?
                    )
                LIMIT ?,?;";

        $query = $this->db->query($sql,
            array($profiel->pid, $profiel->pid, intval($offset), intval($limit)));
        if (!$query) {
            return FALSE;
        }

        $likers = [];
        $row = $query->row();

        while (isset($row)) {
            $liker = $this->profiel->query_by_id($row->liker_id);
            array_push($likers, $liker);

            $row = $query->next_row();
        }

        return $likers;
    }

    public function query_wederzijdse_likes($limit = PHP_INT_MAX, $offset = 0)
    {
        $profiel = $this->get_profiel_or_throw();

        $query = $this->db
            ->select('other.liker_id')
            ->from('Like me')
            ->join('Like other', 'other.liked_id = me.liker_id AND other.liker_id = me.liked_id')
            ->where('me.liker_id', $profiel->pid)
            ->limit(intval($limit))
            ->offset(intval($offset))
            ->get();

        if (!$query) {
            return FALSE;
        }

        $matched_profielen = [];
        $row = $query->row();
        while (isset($row)) {
            $other = $this->profiel->query_by_id($row->liker_id);
            array_push($matched_profielen, $other);

            $row = $query->next_row();
        }

        return $matched_profielen;
    }

    /**
     * @param $like_relatie_type string
     * @return bool TRUE if valid
     */
    public function is_relatie_type_valid($like_relatie_type)
    {
        return ($like_relatie_type === Like::GEGEVEN_LIKE
            || $like_relatie_type === Like::ONTVANGEN_LIKE
            || $like_relatie_type === Like::WEDERZIJDSE_LIKE);
    }

    private function get_profiel_or_throw()
    {
        $profiel = current_profiel();
        if ($profiel === NULL) {
            throw new Exception('Moet authenticated zijn');
        }

        return $profiel;
    }

}
