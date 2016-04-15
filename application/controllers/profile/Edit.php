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

    public function update_beschrijving(){
        $sql = "UPDATE Profiel SET beschrijving=? WHERE pid=?";
        $newVal = $this->input->post('value');
        $profiel = $this->authentication->get_current_profiel();
        $this->db->query($sql,array($newVal,$profiel->pid));
        echo $newVal; 
    }

    public function update_sex_preference(){
        $sql = "UPDATE Profiel SET valt_op_man=?, valt_op_vrouw=? WHERE pid=?";
        $newVal = $this->input->post('value');
        $profiel = $this->authentication->get_current_profiel();
        $likesM = false;
        $likesF = false;
        if($newVal == 'm' || $newVal == 'bi' ) {
            $likesM = true;
        }
        if ($newVal == 'v' || $newVal == 'bi') {
            $likesF = true;
        }
        $this->db->query($sql,array($likesM,$likesF,$profiel->pid));
        echo seksuele_voorkeur_display($likesM, $likesF);
    }

}
