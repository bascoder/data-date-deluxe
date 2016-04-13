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
            $this->add_profiel_foto($profiel);
            $this->add_foto_array($profiel);
            $this->add_geslacht($profiel);

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
            if(!$this->is_ouder_dan_18($fields['geboorte_datum'])) {
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
        $hetero_vrouw = $pref == 'hetero' && $geslachtId == self::GESLACHT_ID_VROUW;
        $homo_man = $pref == 'homo' && $geslachtId == self::GESLACHT_ID_MAN;
        return $pref == 'bi' || $hetero_vrouw || $homo_man;
    }

    /**
     * @param $pref string
     * @param $geslachtId integer
     * @return bool true if valt op vrouw
     */
    private function valt_op_vrouw($pref, $geslachtId)
    {
        $hetero_man = $pref == 'hetero' && $geslachtId == self::GESLACHT_ID_MAN;
        $homo_vrouw = $pref == 'homo' && $geslachtId == self::GESLACHT_ID_VROUW;
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

    private function add_geslacht($profiel)
    {
        $query = $this->db->get_where('Geslacht', array('gid' => $profiel->geslacht_id));
        $geslacht = $query->row();
        if (isset($geslacht)) {
            $profiel->geslacht = $geslacht;
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
}
