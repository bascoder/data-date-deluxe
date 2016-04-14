<?php

/**
 *
 * @property Profiel profiel
 * @property CI_Output output
 */
class Edit extends CI_Controller
{
    public function index()
    {
        show_404();
    }

    public function update(){

        $sql = "UPDATE Profiel SET ?=? WHERE pid=?";
        $fieldName = $this->input->post('field');
        $newVal = $this->input->post('value');
        $profiel = $this->authentication->get_current_profiel();
        $this->db->query($sql,array($fieldName,$newVal,$profiel->pid));
        echo $newVal; 

    }
}
