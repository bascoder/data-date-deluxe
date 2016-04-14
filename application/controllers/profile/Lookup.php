<?php

/**
 *
 * @property Profiel profiel
 * @property CI_Output output
 * @property CI_Input input
 */
class Lookup extends CI_Controller
{
    public function index()
    {
        show_404();
    }

    /**
     * Json response met 6 random profielen
     */
    public function random()
    {
        $this->load->model('profiel');

        $this->output->set_content_type('application/json');
        try {
            $profielen = $this->profiel->query_random_profielen();

            if ($profielen !== NULL && is_array($profielen) && !empty($profielen)) {
                // wel profielen return profielen als json
                $this->output->set_output(json_encode($profielen));
            } else {
                // geen profielen return 404 not found
                $this->handle_404_json();
            }
        } catch (Exception $e) {
            // onverwachte exceptie loggen en returnen als error
            $this->handle_exception_json($e);
        }
    }

    public function page()
    {
        if (current_privileges() === Authentication::ANONYMOUS) {
            $this->load->view('profile/search');
        }
    }

    public function search()
    {
        $this->load->model('profiel');

        $geslacht_voorkeur = strtolower($this->input->post('geslacht_voorkeur'));

        $where_clauses = $this->populate_where_geslacht($geslacht_voorkeur);
        $leeftijd_min = intval($this->input->post('leeftijd_min'));
        $leeftijd_max = intval($this->input->post('leeftijd_max'));
        if (($leeftijd_min) >= 18 && ($leeftijd_max) >= $leeftijd_min) {
            array_push($where_clauses, array('field' => 'leeftijd_voorkeur_min >=', 'value' => $leeftijd_min));
            array_push($where_clauses, array('field' => 'leeftijd_voorkeur_max <=', 'value' => $leeftijd_max));
        }
        $merken_string = $this->input->post('merken');
        if ($merken_string !== NULL && !empty($merken_string)) {
            $merken = explode('|', $merken_string);
            // check merken
        }

        $profielen = $this->profiel->query_by_extra($where_clauses);
        $this->load->view('profile/result', array('profielen' => $profielen));
    }

    /**
     * @param Exception $e
     */
    private function handle_exception_json($e)
    {
        log_message('error', $e->getMessage());
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(array('error' => 'Een exception trad op: ' . $e->getMessage())));
    }

    private function handle_404_json()
    {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(array('error' => 'geen profielen gevonden')));
    }

    /**
     * @param $geslacht_voorkeur
     * @return mixed
     */
    private function populate_where_geslacht($geslacht_voorkeur)
    {
        $where_clauses = [];
        if ($geslacht_voorkeur === 'mannen') {
            array_push($where_clauses, array('field' => 'valt_op_man', 'value' => TRUE));
            return $where_clauses;
        } elseif ($geslacht_voorkeur === 'vrouwen') {
            array_push($where_clauses, array('field' => 'valt_op_vrouw', 'value' => TRUE));
            return $where_clauses;
        } elseif ($geslacht_voorkeur !== 'beiden') {
            show_error('Geslacht voorkeur is verplicht', 400);
            return [];
        }
        return [];
    }
}
