<?php

/**
 * Laadt header en footer voor en na de controller
 */
class ViewHook extends CI_Hooks
{

    public function before()
    {
        $ci =& get_instance();
        $this->include_header($ci);
        $this->include_message($ci);

    }

    public function after()
    {
        $ci =& get_instance();
        $ci->load->view('footer');
    }

    /**
     * @param $ci
     */
    private function include_message($ci)
    {
        // haal optionele flash message op
        $message = $ci->session->flashdata('message');
        if (isset($message) && is_array($message)
            && isset($message['level']) && isset($message['message'])
        ) {
            $ci->load->view('partial/message', $message);
        }
        // anders doe niks
    }

    /**
     * @param $ci
     * @return CI_Controller
     */
    private function include_header($ci)
    {
        $is_auth = $ci->authentication->is_authenticated();
        $ci->load->view('header', array('is_auth' => $is_auth));
        return $ci;
    }
}
