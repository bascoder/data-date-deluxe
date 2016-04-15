<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 */
class Merk extends CI_Model
{
    public function query_all()
    {
        $query = $this->db->from('Merk')->order_by('merk')->get();

        $merken = [];

        $row = $query->row();
        do {
            array_push($merken, $row);
        } while ($row = $query->next_row());

        return $merken;
    }
}
