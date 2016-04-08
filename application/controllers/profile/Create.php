<?php

/**
 * Controller om profile aan te maken na het registreren
 */
class Create extends CI_Controller {

  public function index() {
    $this->load->view('profile/create');
  }
}
