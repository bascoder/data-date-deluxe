<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 * @property Profiel profiel
 */
class Like extends CI_Model
{
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

    public function query_mijn_gegeven_likes()
    {
        $this->load->model('profiel');
        $profiel = current_profiel();
        if ($profiel === NULL) {
            throw new Exception('Moet authenticated zijn');
        }

        $query = $this->db
            ->select('liked_id')
            ->from('Like')
            ->where('liker_id', $profiel->pid)
            ->get();

        $liked_profielen = [];
        $like = $query->row();
        do {
            $liked = $this->profiel->query_by_id($like->liked_id);
            array_push($liked_profielen, $liked);
        } while ($like = $query->next_row());

        return $liked_profielen;
    }
}
