<?php
class Personalitytest extends CI_Controller
{

    public function index()
    {
        $this->load->view('personalitytest');

        $profiel = $this->authentication->get_current_profiel();
        if ($profiel === NULL) {
            $this->sessie_verlopen();
        }
    }


    public function submit()
    {
        redirect('/profile/fototool'); 
    }

    private function sessie_verlopen()
    {
        sessie_verlopen_redirect();
    }
}
?>