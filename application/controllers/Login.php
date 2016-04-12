<?php

/**
 *
 * @property Authentication authentication
 */
class Login extends CI_Controller
{
    public function index()
    {
        $this->load->view('login');
    }

    public function submit()
    {
        $nickname = $this->input->post('nickname');
        $password = $this->input->post('password');
        if ($this->authentication->authenticate($nickname, $password)) {
            $this->load->view('partial/message/success',
                array('message' => 'Succesvol ingelogd!'));
            $this->load->view('home');
        } else {
            $this->load->view('partial/message/error',
                array('message' => 'De gebruikersnaam en/of wachtwoord komen niet overeen.'));
            $this->load->view('login');
        }
    }
}
