<?php

/**
 *
 * @property Profiel profiel
 * @property CI_Output output
 * @property CI_Input input
 * @property CI_Pagination pagination
 */
class Lookup extends CI_Controller
{
    const PER_PAGE = 4;

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
        $this->load->view('profile/search');
    }

    public function search($page = 0)
    {
        $this->load->model('profiel');
        $this->load->library('pagination');

        if (!is_integer(intval($page))) {
            $page = 0;
        }

        $where_clauses = $this->make_where_clause();
        $joins = $this->make_joins();

        $view_params = [];
        if ($where_clauses) {

            $count = $this->profiel->count_where($where_clauses, $joins);
            $profielen = $this->profiel->query_by_extra($where_clauses, $joins, self::PER_PAGE, $page);

            if (!is_array($profielen)) {
                $profielen = [];
                $this->load->view('partial/message',
                    array('message' => '<p>Er konden geen profielen gevonden worden met uw zoek criteria.</p>',
                        'level' => 'error'));
            }

            $page_links = $this->calc_page_links($count);

            $view_params = array(
                'profielen' => $profielen,
                'page_links' => $page_links);
        }
        $this->load->view('profile/result', $view_params);
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
            $this->load->view('partial/message',
                array('message' => '<p>Het is verplicht om een geslacht mee te geven</p>',
                    'level' => 'error'));
            return FALSE;
        }
        return [];
    }

    /**
     * @return mixed
     */
    private function make_where_clause()
    {
        $geslacht_voorkeur = strtolower($this->input->get('geslacht_voorkeur'));

        if ($where_clauses = $this->populate_where_geslacht($geslacht_voorkeur)) {
            $leeftijd_min = intval($this->input->get('leeftijd_min'));
            $leeftijd_max = intval($this->input->get('leeftijd_max'));
            if (($leeftijd_min) >= 18 && ($leeftijd_max) >= $leeftijd_min) {
                array_push($where_clauses, array('field' => 'leeftijd_voorkeur_min >=', 'value' => $leeftijd_min));
                array_push($where_clauses, array('field' => 'leeftijd_voorkeur_max <=', 'value' => $leeftijd_max));
            }

            return $where_clauses;
        } else {
            return FALSE;
        }
    }

    /**
     * @param $count
     * @return string
     */
    private function calc_page_links($count)
    {
        $page_config['base_url'] = base_url() . 'index.php/profile/lookup/search/';
        $page_config['per_page'] = self::PER_PAGE;
        $page_config['total_rows'] = $count;
        $page_config['reuse_query_string'] = TRUE;
        $page_config['first_link'] = 'Eerste';
        $page_config['last_link'] = 'Laatste';
        $this->pagination->initialize($page_config);
        $page_links = $this->pagination->create_links();
        return $page_links;
    }

    private function make_joins()
    {
        $joins = [];
        $merken_string = $this->input->get('merken');
        if ($merken_string !== NULL && !empty($merken_string)) {
            $merken = json_decode($merken_string);

            // check json parse error
            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'invalid json: ' . json_last_error_msg());
                show_error('Invalid json passed voor merken parameter', 400);
            }

            foreach ($merken as $merk) {
                $merk_naam = $this->db->escape_str($merk->naam);
                array_push($joins,
                    array('table' => 'merk_voorkeur as ' . $merk_naam,
                        'condition' => "$merk_naam.profiel_id = pid AND $merk_naam.merk_id = " . intval($merk->mid)));
            }
        } else {
            show_error('Merk voorkeuren zijn verplicht', 400);
        }

        $persoonlijkheid_voorkeur = $this->input->get('persoonlijkheids_voorkeur');
        if ($persoonlijkheid_voorkeur !== NULL && !empty($persoonlijkheid_voorkeur)) {
            array_push($joins,
                array('table' => 'Persoonlijkheids_type',
                    'condition' => "ptid = persoonlijkheids_type_voorkeur_id AND name = $persoonlijkheid_voorkeur"));
        } else {
            show_error('Persoonlijkheid voorkeur is verplicht', 400);
        }

        return $joins;
    }
}
