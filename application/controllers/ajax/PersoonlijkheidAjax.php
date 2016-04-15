<?php

/**
 *
 * @property CI_Output output
 * @property Persoonlijkheid persoonlijkheid
 */
class PersoonlijkheidAjax extends CI_Controller
{
    public function all()
    {
        $this->load->model('persoonlijkheid');
        $persoonlijkheids_typen = $this->persoonlijkheid->query_all();

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($persoonlijkheids_typen));
    }
}
