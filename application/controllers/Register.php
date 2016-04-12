<?php

/**
 *
 * @property Profiel profiel
 */
class Register extends CI_Controller
{
    public function index()
    {
        $this->load->view('register');
    }

    public function submit()
    {
//        var_dump($this->input->post());
//        $values = [];
//        $values['voornaam'] = $this->input->post('voornaam');
//        $values['achternaam']  = $this->input->post('achternaam');
//        $values['email']  = $this->input->post('mail');
//        $geslachtId = $this->input->post('gender');
//        $values['geslacht_id']  = $geslachtId;
//        $values['geboorte_datum']  = strtotime($this->input->post('geboorte_datum'));
//        $values['leeftijd_voorkeur_min']  = $this->input->post('leeftijd_voorkeur_min');
//        $values['leeftijd_voorkeur_max']  = $this->input->post('leeftijd_voorkeur_max');
//        $pref = strtolower($this->input->post('sex_preference'));
//        $values['valt_op_man']  = $pref == 'bi' || ($pref == 'hetero' && $geslachtId == 2) || ($pref == 'homo' && $geslachtId == 1);
//        $values['valt_op_vrouw']  = $pref == 'bi' || ($pref == 'hetero' && $geslachtId == 1) || ($pref == 'homo' && $geslachtId == 2);
//        $values['nickname']  = $this->input->post('nickname');
//        $values['password']  = md5($this->input->post('password'));
//        $values['beschrijving']  = '';
//        $values['is_admin']  = FALSE;
//        $values['profiel_foto_id']  = $geslachtId;

        $this->load->model('profiel');
        try {
            $fields = $this->profiel->make_fields($this->input->post());
            $status = $this->profiel->insert_entry($fields);

            if (!$status) {
                log_message('error', var_dump_to_string($this->db->error()));
                show_error('Er ging iets mis met het verwerken van uw gegevens', 500, 'Database error');
            }
            $this->load->view('home');
        } catch (InvalidArgumentException $e) {
            show_error($e->getMessage(), 500);
        }

    }
}
