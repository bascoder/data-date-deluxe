<?php

/**
 * Init database als deze nog niet is aangemaakt
 */
class DatabaseHook extends CI_Hooks
{
    public function create_if_not_exists()
    {
        // kijk of de profiel tabel bestaat
        $ci =& get_instance();
        $count = $this->get_table_count($ci);

        // als hij niet bestaat
        if ($count !== 1) {
            log_message('info', 'Eerste init van database tables');
            $this->create_tables($ci);
        }
    }

    /**
     * @param $ci CI_Controller instance
     * @return int count
     */
    private function get_table_count($ci)
    {
        $query = $ci->db->query("SELECT COUNT(*) AS cnt FROM sqlite_master WHERE type='table' AND name LIKE 'profiel';");

        $count = 0;
        foreach ($query->result() as $row) {
            $count = $row->cnt;
        }
        return intval($count);
    }

    /**
     * Create tables and log result
     */
    private function create_tables()
    {
        // command = sqlite3 database.sqlite < dml.sql
        $command = 'sqlite3 ' . FCPATH . '/db/database.sqlite < ' . FCPATH . '/db/dml.sql';
        $output = array();
        $return_var = null;
        exec($command, $output, $return_var);

        // 0 is success status
        if($return_var !== 0) {
            show_error('Could not init database tables');
            log_message('error', var_dump_to_string($output));
        }
    }
}
