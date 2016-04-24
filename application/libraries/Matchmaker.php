<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Globale sort functie voor uasort
 * @param $a stdClass profiel A
 * @param $b stdClass profiel B
 * @return int > 1, < -1, = 0
 */
function global_sort_function($a, $b)
{
    if ($a->aantrekkelijkheid === $b->aantrekkelijkheid) return 0;
    return $a->aantrekkelijkheid > $b->aantrekkelijkheid ? 1 : -1;
}

/**
 * Library for match making
 */
class MatchMaker
{
    const TYPE_CHARACTERS = 4;

    /**
     * Sorteer bij aantrekkelijkheid attribuut aflopend
     * @param $profielen array(stdClass) profielen met het aantrekkelijkheid attribuut
     * @throws Exception als sorteren faalt
     */
    public function order_by_aantrekkelijkheid(&$profielen)
    {
        if (uasort($profielen, array('self', 'sort_function')) === FALSE) {
            throw new Exception('Sorting failed');
        }
    }

    /**
     * Sort functie voor uasort
     * @param $a stdClass profiel A
     * @param $b stdClass profiel B
     * @return int < 1, > -1, = 0
     */
    static function sort_function($a, $b)
    {
        if ($a->aantrekkelijkheid === $b->aantrekkelijkheid) return 0;
        return $a->aantrekkelijkheid < $b->aantrekkelijkheid ? 1 : -1;
    }

    /**
     * Bepaal afstand door aantrekkelijkheid attribuut toe te voegen aan alle profielen.
     *
     * @param stdClass $current_profiel Profiel object van huidige gebruiker
     * @param array $profielen array met profielen waarvan de aantrekkelijkheid bepaald moet worden
     * @throws Exception als current profiel geen persoonlijkheid heeft
     */
    public function bepaal_aantrekkelijkheid($current_profiel, &$profielen)
    {
        $mijn = $current_profiel;
        if(!isset($mijn->persoonlijkheids_type)) {
            throw new Exception('Je moet een persoonlijkheids voorkeur hebben');
        }
        $mijn_type = $mijn->persoonlijkheids_type->type;
        $mijn_merken = $mijn->merken;
        $mijn_merken_count = count($mijn_merken);
        if (strlen($mijn_type) !== self::TYPE_CHARACTERS) {
            throw new Exception('Persoonlijkheids type moet 4 characters zijn');
        }

        // iterate over profielen om aantrekkelijkheid per persoon te bepalen
        foreach ($profielen as $ander) {
            // gewicht is aantrekkelijkheid, schaal 0-100
            $gewicht = $this->bepaal_gewicht($ander, $mijn_type, $mijn_merken, $mijn_merken_count);

            // voeg gewicht toe als attribuut
            $ander->aantrekkelijkheid = $gewicht;
        }
    }

    /**
     * Return een query die profielen filtert die sowieso aan de criteria voldoen van current_profiel en andersom.
     *
     * @param $current_profiel stdClass profiel object
     * @param bool $count if TRUE count instead of query
     * @return string query string
     */
    public function make_query($current_profiel, $count = FALSE)
    {
        $ci =& get_instance();

        // geslacht
        $where = $this->heeft_geslacht($current_profiel);

        // geslacht voorkeur
        $where = $this->heeft_geslacht_voorkeur($current_profiel, $ci, $where);

        // leeftijd voorkeur
        $where = $this->heeft_leeftijd_voorkeur($current_profiel, $ci, $where);

        $sql = ($count === FALSE ? "SELECT *" :
                "SELECT COUNT(*) as cnt ")
            . "\nFROM Profiel P
                INNER JOIN Geslacht G ON P.geslacht_id = G.gid
                WHERE $where AND P.pid <> " . $ci->db->escape($current_profiel->pid) . "\n";

        return $sql;
    }

    /**
     * @param $current_profiel
     * @param $ci
     * @param $where
     * @return string
     */
    private function heeft_leeftijd_voorkeur($current_profiel, $ci, $where)
    {
        $leeftijd_min = intval($current_profiel->leeftijd_voorkeur_min);
        $leeftijd_max = intval($current_profiel->leeftijd_voorkeur_max);
        $where .= " \nAND (p.geboorte_datum / 31556926) BETWEEN $leeftijd_min AND $leeftijd_max ";
        return $where;
    }

    /**
     * @param $current_profiel
     * @param $ci
     * @param $where
     * @return string
     */
    private function heeft_geslacht_voorkeur($current_profiel, $ci, $where)
    {
        $geslacht = ($current_profiel->geslacht->geslacht);
        if ($geslacht === 'man') {
            $veld = 'valt_op_man';
        } else {
            $veld = 'valt_op_vrouw';
        }
        $where .= " \n P.$veld = 1";
        return $where;
    }

    /**
     * @param $current_profiel
     * @return string
     */
    private function heeft_geslacht($current_profiel)
    {
        if ($current_profiel->valt_op_man && !$current_profiel->valt_op_vrouw) {
            $where = "G.geslacht = 'man'";
            return $where;
        } elseif ($current_profiel->valt_op_vrouw && !$current_profiel->valt_op_man) {
            $where = "G.geslacht = 'vrouw'";
            return $where;
        }
        return '';
    }

    /**
     * @param stdClass $ander profiel object
     * @param string $mijn_type
     * @param array $mijn_merken array met mijn merken
     * @param int $mijn_merken_count
     * @return int
     */
    private function bepaal_gewicht($ander, $mijn_type, $mijn_merken, $mijn_merken_count)
    {
        $gewicht = 0;

        $type_ander = $ander->persoonlijkheids_type->type;
        if (strlen($type_ander) !== self::TYPE_CHARACTERS) {
            return 0;
        }

        // check tegengestelde persoonlijkheid waarbij max 50 punten te behalen zijn
        $gewicht = $this->bepaal_type_gewicht($mijn_type, $type_ander, $gewicht);

        // gewicht = percentage overeenkomsten / 2 (max 50)
        $merken_gewicht = $this->bepaal_merken_gewicht($ander, $mijn_merken, $mijn_merken_count);
        $gewicht += intval($merken_gewicht);

        return $gewicht;
    }

    /**
     * @param $mijn_type
     * @param $type_ander
     * @param $gewicht
     * @return int
     */
    private function bepaal_type_gewicht($mijn_type, $type_ander, $gewicht)
    {
        if ($type_ander === $mijn_type) {
            // 40 punten + 10 bonus
            $gewicht = 50;
            return $gewicht;
        } else {
            // anders max 30 punten
            for ($i = 0; $i < self::TYPE_CHARACTERS; $i++) {
                if ($type_ander[$i] !== $mijn_type[$i]) {
                    $gewicht += 10;
                }
            }
            return $gewicht;
        }
    }

    /**
     * @param $ander
     * @param $mijn_merken
     * @param $mijn_merken_count
     * @return float
     */
    private function bepaal_merken_gewicht($ander, $mijn_merken, $mijn_merken_count)
    {
        $merken_ander = $ander->merken;
        $merken_overeenkomsten = 0;
        foreach ($mijn_merken as $mijn_merk) {
            if (in_array($mijn_merk, $merken_ander)) {
                $merken_overeenkomsten++;
            }
        }
        $merken_gewicht = round((floatval($merken_overeenkomsten) / floatval($mijn_merken_count)) * 50.0);
        return $merken_gewicht;
    }

    
    public function distanceOrderdPersons($eVal, $nVal, $tVal, $jVal){
        $profiles = $this->dbx_query("SELECT * FROM Persoonlijkheids_type");
        $result = array();
        while ($row = $profiles->next_row()) {
            $temp = pow(doubleval($row->eType) - $eVal,2);
            $temp += pow(doubleval($row->nType) - $nVal,2);
            $temp += pow(doubleval($row->tType) - $tVal,2);
            $temp += pow(doubleval($row->jType) - $jVal,2);
            $temp = sqrt($temp);
            $result[$profiles->ptid] = $temp;
        }
        return asort($result);
    }
}
