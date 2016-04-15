<?php

/**
 *
 * @property Profiel profiel
 * @property CI_Output output
 * @property CI_Session session
 * @property Like like_model
 */
class Edit extends CI_Controller
{
    public function index()
    {
        show_404();
    }

    public function update()
    {

        $sql = "UPDATE Profiel SET ?=? WHERE pid=?";
        $fieldName = $this->input->post('field');
        if ($this->isAllowedField($fieldName)) {
            $newVal = $this->input->post('value');
            $profiel = $this->authentication->get_current_profiel();
            $this->db->query($sql, array($fieldName, $newVal, $profiel->pid));
            echo $newVal;
        } else {
            echo "<marquee style='color:red' class='message-error'>Unexpected fieldName, please reload the page. If you were not actively breaking the site please contact support.</marquee>";
        }

    }

    public function update_beschrijving()
    {
        $sql = "UPDATE Profiel SET beschrijving=? WHERE pid=?";
        $newVal = $this->input->post('value');
        $profiel = $this->authentication->get_current_profiel();
        $this->db->query($sql, array($newVal, $profiel->pid));
        echo $newVal;
    }

    public function update_sex_preference()
    {
        $sql = "UPDATE Profiel SET valt_op_man=?, valt_op_vrouw=? WHERE pid=?";
        $newVal = $this->input->post('value');
        $profiel = $this->authentication->get_current_profiel();
        $likesM = false;
        $likesF = false;
        if ($newVal == 'm' || $newVal == 'bi') {
            $likesM = true;
        }
        if ($newVal == 'v' || $newVal == 'bi') {
            $likesF = true;
        }
        $this->db->query($sql, array($likesM, $likesF, $profiel->pid));
        echo seksuele_voorkeur_display($likesM, $likesF);
    }

    public function like($who)
    {
        // load models
        $this->load->model('profiel');
        $this->load->model('like', 'like_model');

        // validate input
        if (!isset($who) || intval($who) === 0) {
            show_error('Pass het ID van het gelikede profiel', 400);
            return;
        }

        // query liked profiel and validate
        $liked = $this->profiel->query_by_id($who);
        if ($liked === NULL) {
            show_404('Het profiel kon niet gevonden worden in de database');
            return;
        }

        // obtain liker (huidige user)
        $liker = current_profiel();
        if ($liker === NULL) {
            $this->session->set_flashdata('message',
                array('message' => 'Authentication required',
                    'level' => 'error'));
            redirect('login');
        }

        // try insert
        try {
            $status = $this->like_model->insert($liker, $liked);
            if (!$status) {
                log_message('error', 'Insert failed: ' . $this->db->error());
                $this->session->set_flashdata('message',
                    array('message' => 'Er ging iets mis met het verwerken van de like',
                        'level' => 'error'));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('message',
                    array('message' => 'Woehoee! Love is in the air',
                        'level' => 'success'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        } catch (InvalidArgumentException $e) {
            show_error($e->getMessage(), 400);
        }
    }

    private function isAllowedField($fieldname)
    {
        $allowedFieldnames = array('beschrijving' => null);
        return array_key_exists($fieldname, $allowedFieldnames);
    }
}
