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
        $profiel = $this->authentication->get_current_profiel();
        if ($profiel === NULL) {
            $this->sessie_verlopen();
        }

        $this->load->model('persoonlijkheid');
        $answers = $this->input->post();
        $result = $this->persoonlijkheid->add_personality($answers);
        if($result === TRUE){
            redirect('/profile/fototool');
        } else if($result === FALSE){
            redirect('personalitytest');
        }
        else if ($result === "Retake") {
            redirect('home');
        }
    }

    private function sessie_verlopen()
    {
        sessie_verlopen_redirect();
    }
}
?>