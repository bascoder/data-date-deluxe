<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 */
class Profiel extends CI_Model
{
    const GESLACHT_ID_MAN = 1;
    const GESLACHT_ID_VROUW = 2;

    /**
     * @param $fields array met post data, eerst filteren met make_fields method
     * @return bool|int Return inserted row ID if success, else FALSE
     */
    public function insert_entry($fields)
    {
        $sql = "INSERT INTO Profiel(voornaam, achternaam, email, password, is_admin, nickname, beschrijving,
                                    geboorte_datum, leeftijd_voorkeur_min, leeftijd_voorkeur_max, valt_op_man, valt_op_vrouw, geslacht_id, profiel_foto_id) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result = $this->db->query($sql,
            array(
                $fields['voornaam'], $fields['achternaam'], $fields['email'], $fields['password'],
                $fields['is_admin'], $fields['nickname'], $fields['beschrijving'], $fields['geboorte_datum'],
                $fields['leeftijd_voorkeur_min'], $fields['leeftijd_voorkeur_max'],
                $fields['valt_op_man'], $fields['valt_op_vrouw'],
                $fields['geslacht_id'], $fields['profiel_foto_id']
            ));

        if ($result === FALSE) {
            return FALSE;
        }
        return $this->db->insert_id();
    }

    /**
     * Query Profiel by profiel_id, return NULL if not found.
     * @param $profiel_id integer
     * @return mixed|null Profiel of NULL
     */
    public function query_by_id($profiel_id)
    {
        if (!is_numeric($profiel_id)) {
            throw new InvalidArgumentException('Profiel ID moet numeriek zijn');
        }
        return $this->query_by('pid', intval($profiel_id));
    }

    /**
     * @param $nickname string
     * @return mixed|null NULL on failure anders een Profiel
     */
    public function query_by_nickname($nickname)
    {
        if (!is_string($nickname) || strlen($nickname) === 0) {
            throw new InvalidArgumentException('Nickname moet een string zijn');
        }
        return $this->query_by('nickname', $nickname);
    }

    /**
     * @param $email string
     * @return mixed|null NULL on failure anders een Profiel
     */
    public function query_by_email($email)
    {
        if (!is_string($email) || strlen($email) === 0) {
            throw new InvalidArgumentException('Email moet een string zijn');
        }
        return $this->query_by('email', $email);
    }

    /**
     * 6 random profielen voor home page
     * @param int $aantal default 6
     * @return array met profielen
     */
    public function query_random_profielen($aantal = 6)
    {
        $aantal = intval($aantal) + 1;
        if ($aantal == 0) {
            throw new InvalidArgumentException('Aantal moet meer zijn dan 0');
        }

        // query benodigde attributen
        $query = $this->db
            ->select('pid, nickname, geslacht_id, geboorte_datum, beschrijving, persoonlijkheids_type_id')
            ->from('Profiel')
            ->order_by('RANDOM()')
            ->limit($aantal)
            ->get();

        // voeg extra attributen toe, en append aan array
        $profielen = [];
        while ($row = $query->next_row()) {
            $this->add_geslacht($row);
            $this->add_persoonlijkheids_type($row);
            $this->add_merk_array($row);
            $row->profiel_foto = placeholder_url($row->geslacht->geslacht);

            $beschrijving = $row->beschrijving;
            $row->beschrijving = $this->eerste_zin($beschrijving);

            array_push($profielen, $row);
        }

        return $profielen;
    }

    /**
     * Query met bepaalde where clauses
     * $where clauses moet een array zijn met daarin een assoc array met twee keys: field en value
     * @param $where_clauses array
     * @param $joins array met joins
     * @param int $limit
     * @param int $offset
     * @return array|null array met profielen
     */
    public function query_by_extra($where_clauses, $joins, $limit = 6, $offset = 0)
    {
        foreach ($where_clauses as $where) {
            $this->db->where($where['field'], $where['value']);
        }
        foreach ($joins as $join) {
            $this->db->join($join['table'], $join['condition']);
        }

        $query = $this->db->limit($limit)->offset($offset)->get('Profiel');
        $row = $query->row();
        $profielen = [];
        do {
            if (isset($row)) {
                $profiel = $row;
                $this->add_alles($profiel);

                array_push($profielen, $profiel);
            }
        } while ($row = $query->next_row());
        if (count($profielen) === 0)
            return NULL;
        else {
            return $profielen;
        }
    }

    public function count_where($where_clauses, $joins)
    {
        foreach ($where_clauses as $where) {
            $this->db->where($where['field'], $where['value']);
        }
        foreach ($joins as $join) {
            $this->db->join($join['table'], $join['condition']);
        }

        return $this->db->count_all_results('Profiel');
    }

    /**
     * Delete profiel met $pid, alleen toegestaan voor huidig profiel of admin
     * @param $pid int profiel id to delete
     * @return bool result
     * @throws Exception als $pid niet van de huidige gebruiker is of huidige gebruiker geen admin is
     */
    public function delete($pid)
    {
        if (current_profiel()->pid === $pid || current_privileges() === Authentication::ADMIN) {
            $result = $this->db->delete('Profiel', array('pid' => $pid));
            // safe cast het resultaat
            return $result === FALSE ? FALSE : TRUE;
        } else {
            throw new Exception('Invalid credentials');
        }
    }

    /**
     * Query by een field
     * @param $field
     * @param $value
     * @return mixed|null
     */
    private function query_by($field, $value)
    {
        $query = $this->db->get_where('Profiel', array($field => $value));
        $row = $query->row();
        if (isset($row)) {
            $profiel = $row;
            $this->add_alles($profiel);

            return $profiel;
        }
        return NULL;
    }

    /**
     * Maak een array met fields voor insert_entry met business rules
     * @param $post array with post input
     * @return array fields with applied business rules
     * @throws InvalidArgumentException als een business rule violated is
     */
    public function make_fields($post)
    {
        $fields = [];
        if (isset($post['voornaam'])) {
            $fields['voornaam'] = $post['voornaam'];
        } else {
            throw new InvalidArgumentException('Voornaam is verplicht');
        }
        if (isset($post['achternaam'])) {
            $fields['achternaam'] = $post['achternaam'];
        } else {
            throw new InvalidArgumentException('Achternaam is verplicht');
        }
        if (isset($post['mail'])) {
            $fields['email'] = $post['mail'];
        } else {
            throw new InvalidArgumentException('Email adres is verplicht');
        }
        if (isset($post['gender'])) {
            $geslachtId = $post['gender'];
        } else {
            throw new InvalidArgumentException('Geslacht is verplicht');
        }
        $fields['geslacht_id'] = $geslachtId;
        if (isset($post['geboorte_datum'])) {
            $fields['geboorte_datum'] = strtotime($post['geboorte_datum']);
            if (!$this->is_ouder_dan_18($fields['geboorte_datum'])) {
                throw new InvalidArgumentException('Je moet ouder dan 18 zijn om deze website te gebruiken.');
            }
            if ($fields['geboorte_datum'] === FALSE) {
                throw new InvalidArgumentException('Geboortedatum heeft geen geldig format');
            }
        } else {
            throw new InvalidArgumentException('Geboortedatum is verplicht');
        }
        if ($this->is_leeftijd_voorkeur_min_valid($post)) {
            $fields['leeftijd_voorkeur_min'] = $post['leeftijd_voorkeur_min'];
        } else {
            throw new InvalidArgumentException('Leeftijd voorkeur is verplicht');
        }
        if ($this->is_leeftijd_voorkeur_max_valid($post)) {
            $fields['leeftijd_voorkeur_max'] = $post['leeftijd_voorkeur_max'];
        } else {
            throw new InvalidArgumentException('Leeftijd voorkeur is verplicht');
        }
        if (isset($post['sex_preference'])) {
            $pref = strtolower($post['sex_preference']);
        } else {
            throw new InvalidArgumentException('Seksuele voorkeur is verplicht');
        }
        $fields['valt_op_man'] = $this->valt_op_man($pref, $geslachtId);
        $fields['valt_op_vrouw'] = $this->valt_op_vrouw($pref, $geslachtId);
        $fields['nickname'] = $post['nickname'];
        if ($this->is_password_valid($post)) {
            try {
                $fields['password'] = $this->hash_password($post['password']);
            } catch (Exception $e) {
                log_message('error', 'password_hash failed');
                show_error('Er ging iets mis met het verwerken van uw gegevens', 500);
            }
        } else {
            throw new InvalidArgumentException('Een wachtwoord is verplicht');
        }
        if (isset($post['beschrijving'])) {
            $fields['beschrijving'] = $post['beschrijving'];
        } else {
            // beschrijving is default ''
            $fields['beschrijving'] = '';
        }
        $fields['is_admin'] = FALSE;
        if (isset($post['profiel_foto_url'])) {
            $fields['profiel_foto_id'] = $geslachtId;
        } else {
            $fields['profiel_foto_id'] = $geslachtId;
        }

        return $fields;
    }


    /**
     * @param $pref string
     * @param $geslachtId integer
     * @return bool true if valt op man
     */
    private function valt_op_man($pref, $geslachtId)
    {
        $hetero_vrouw = $pref == 'm' && $geslachtId == self::GESLACHT_ID_VROUW;
        $homo_man = $pref == 'm' && $geslachtId == self::GESLACHT_ID_MAN;
        return $pref == 'bi' || $hetero_vrouw || $homo_man;
    }

    /**
     * @param $pref string
     * @param $geslachtId integer
     * @return bool true if valt op vrouw
     */
    private function valt_op_vrouw($pref, $geslachtId)
    {
        $hetero_man = $pref == 'v' && $geslachtId == self::GESLACHT_ID_MAN;
        $homo_vrouw = $pref == 'v' && $geslachtId == self::GESLACHT_ID_VROUW;
        return $pref == 'bi' || $hetero_man || $homo_vrouw;
    }

    /**
     * Returns een hash en salt in 1 string, geschikt voor de password_verify functie van php
     * @param $password string
     * @return string hash en salt
     * @throws Exception als password_hash faalt
     */
    private function hash_password($password)
    {
        // genereert hash, algoritme en salt
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === FALSE) {
            throw new Exception('Password_hash returned false');
        }
        return $hash;
    }

    /**
     * @param $post
     * @return bool
     */
    private function is_password_valid($post)
    {
        return isset($post['password']) && !empty($post['password']) && strlen($post['password']) >= 8;
    }

    /**
     * @param $post
     * @return bool
     */
    private function is_leeftijd_voorkeur_min_valid($post)
    {
        return isset($post['leeftijd_voorkeur_min']) && is_numeric($post['leeftijd_voorkeur_min']) && $post['leeftijd_voorkeur_min'] >= 18;
    }

    /**
     * @param $post
     * @return bool
     */
    private function is_leeftijd_voorkeur_max_valid($post)
    {
        $is_numeric = is_numeric($post['leeftijd_voorkeur_max']);
        $jonger_dan_99 = $post['leeftijd_voorkeur_max'] <= 99;
        $max_groter_min = $post['leeftijd_voorkeur_max'] >= $post['leeftijd_voorkeur_min'];
        return isset($post['leeftijd_voorkeur_max']) && $is_numeric && $jonger_dan_99 && $max_groter_min;
    }

    /**
     * @param $profiel
     */
    private function add_profiel_foto($profiel)
    {
        $query = $this->db->get_where('Foto', array('fid' => $profiel->profiel_foto_id));
        $foto = $query->row();
        if (isset($foto)) {
            $profiel->profiel_foto = $foto;
        }
    }

    private function add_foto_array($profiel)
    {
        $profiel->fotos = [];

        $query = $this->db->get_where('Foto', array('profiel_id' => $profiel->pid));
        $foto = $query->first_row();
        while ($foto !== NULL) {
            if (isset($foto)) {
                array_push($profiel->fotos, $foto);
            }
            $foto = $query->next_row();
        }
    }

    private function add_merk_array($profiel)
    {
        $profiel->merken = [];

        $query = $this->db->select('merk.*')
            ->from('Merk')
            ->join('Merk_voorkeur', 'profiel_id = ' . intval($profiel->pid) . ' AND merk_id = mid')
            ->get();
        $merk = $query->first_row();
        while ($merk !== NULL) {
            if (isset($merk)) {
                array_push($profiel->merken, $merk);
            }
            $merk = $query->next_row();
        }
    }

    private function add_geslacht($profiel)
    {
        $query = $this->db->get_where('Geslacht', array('gid' => $profiel->geslacht_id));
        $geslacht = $query->row();
        if (isset($geslacht)) {
            $profiel->geslacht = $geslacht;
        }
    }

    private function add_persoonlijkheids_type($profiel)
    {
        $query = $this->db->get_where('Persoonlijkheids_type', array('ptid' => $profiel->persoonlijkheids_type_id));
        $type = $query->row();
        if (isset($type)) {
            $profiel->persoonlijkheids_type = $type;
        }
    }

    private function add_persoonlijkheids_type_voorkeur($profiel)
    {
        $query = $this->db->get_where('Persoonlijkheids_type', array('ptid' => $profiel->persoonlijkheids_type_voorkeur_id));
        $type = $query->row();
        if (isset($type)) {
            $profiel->persoonlijkheids_type_voorkeur = $type;
        }
    }

    /**
     * @param $geboorte_datum_ts integer geboorte datum als unix timestamp
     * @return bool
     */
    private function is_ouder_dan_18($geboorte_datum_ts)
    {
        $jaar_in_seconden_18 = 568024668;
        return $geboorte_datum_ts <= (time() - $jaar_in_seconden_18);
    }

    /**
     * @param $beschrijving
     * @return string
     */
    private function eerste_zin($beschrijving)
    {
        $zinnen = explode('.', $beschrijving);
        if (!empty($zinnen)) {
            $eerste_zin = $zinnen[0];
            return $eerste_zin;
        } else {
            $eerste_zin = substr($beschrijving, 15);
            return $eerste_zin;
        }
    }

    /**
     * Join profiel met alle mogelijke tabellen
     * @param $profiel
     */
    private function add_alles($profiel)
    {
        $this->add_profiel_foto($profiel);
        $this->add_foto_array($profiel);
        $this->add_geslacht($profiel);
        $this->add_persoonlijkheids_type($profiel);
        $this->add_merk_array($profiel);
        $this->add_persoonlijkheids_type_voorkeur($profiel);
    }
}
