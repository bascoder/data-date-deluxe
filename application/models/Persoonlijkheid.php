<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 */
class Persoonlijkheid extends CI_Model
{
    public function query_all()
    {
        $query = $this->db->from('Persoonlijkheids_type')->order_by('name')->get();

        $typen = [];

        $row = $query->row();
        do {
            array_push($typen, $row);
        } while ($row = $query->next_row());

        return $typen;
    }
}
