<?php

/**
 * Laadt header en footer voor en na de controller
 */
class ViewHook extends CI_Hooks {

  public function before() {
    $ci =& get_instance();
    $ci->load->view('header');
  }

  public function after() {
    $ci =& get_instance();
    $ci->load->view('footer');
  }
}
