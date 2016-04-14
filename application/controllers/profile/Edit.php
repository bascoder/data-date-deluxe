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
        if($this->isAllowedField($fieldName)){
            $newVal = $this->input->post('value');
            $profiel = $this->authentication->get_current_profiel();
            $this->db->query($sql,array($fieldName,$newVal,$profiel->pid));
            echo $newVal; 
        }
        else{
            echo "<marquee style='color:red' class='message-error'>Unexpected fieldName, please reload the page. If you were not actively breaking the site please contact support.</marquee>";
        }

    }

    private function isAllowedField($fieldname){
        $allowedFieldnames = array('beschrijving' => null );
        return array_key_exists($fieldname, $allowedFieldnames);
    }
}
