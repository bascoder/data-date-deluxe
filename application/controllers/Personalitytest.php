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
        $this->load->model('persoonlijkheid');
        $answers = $this->input->post();
        if($this->persoonlijkheid->add_personality($answers)){
            redirect('/profile/fototool');
        } else {
            redirect('personalitytest');
        }
    }

    private function sessie_verlopen()
    {
        sessie_verlopen_redirect();
    }
}
?>