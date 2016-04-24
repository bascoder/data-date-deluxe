<?php

/**
 * @property Profiel profiel
 * @property CI_Output output
 */
class Profielajax extends CI_Controller
{
    public function nickname($nickname)
    {
        $profiel = $this->profiel->query_by_nickname($nickname);
        $this->do_output($profiel);
    }

    public function email($email)
    {
        $profiel = $this->profiel->query_by_email(urldecode($email));
        $this->do_output($profiel);
    }

    private function after($out)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($out));
    }

    /**
     * @param $profiel
     */
    private function do_output($profiel)
    {
        if ($profiel) {
            $this->after(array('exist' => 1));
        } else {
            $this->after(array('exist' => 0));
        }
    }
}