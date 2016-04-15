<?php

/**
 * @property Merk merk
 * @property CI_Output output
 */
class MerkAjax extends CI_Controller
{

    public function all()
    {
        $this->load->model('merk');
        $merken = $this->merk->query_all();

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($merken));
    }
}
