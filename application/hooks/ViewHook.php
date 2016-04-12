<?php

/**
 * Laadt header en footer voor en na de controller
 */
class ViewHook extends CI_Hooks {

  public function before() {
    $ci =& get_instance();
    $is_auth = $ci->authentication->is_authenticated();
    $ci->load->view('header', array('is_auth' => $is_auth));
  }

  public function after() {
    $ci =& get_instance();
    $ci->load->view('footer');
  }
}
