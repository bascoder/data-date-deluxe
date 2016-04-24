<?php

/**
 *
 * @property Authentication authentication
 * @property CI_Input input
 * @property CI_Session session
 */
class Login extends CI_Controller
{
    public function index()
    {
        $this->load->view('login');
    }

    public function submit()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('hash');
        if ($this->authentication->authenticate($email, $password)) {
            $this->session->set_flashdata('message',
                array('message' => 'Succesvol ingelogd!',
                    'level' => 'success'));
            redirect('home');
        } else {
            $this->session->set_flashdata('message',
                array('message' => 'De gebruikersnaam en/of wachtwoord komen niet overeen.',
                    'level' => 'error'));
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->set_flashdata('message',
            array('message' => 'Succesvol uitgelogd!',
                'level' => 'success'));
        $this->authentication->logout();
        redirect('home');
    }
}
