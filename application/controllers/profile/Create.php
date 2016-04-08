<?php

/**
 * Controller om profile aan te maken na het registreren
 */
class Create extends CI_Controller {

  public function index() {
    $this->load->view('header');
    $this->load->view('profile/create');
    $this->load->view('footer');
  }
}
