<?php

/**
 *
 * @property Authentication authentication
 * @property Profiel profiel
 */
class Display extends CI_Controller
{
    public function index()
    {
        // geen directe toegang
        show_404();
    }

    /**
     * Route voor eigen profiel
     */
    public function mijn()
    {
        $profiel = $this->authentication->get_current_profiel();
        if ($profiel === NULL) {
            sessie_verlopen_redirect();
        } else {
            $this->load->view('profile/display',
                array('profiel' => $profiel));
        }
    }

    /**
     * Route voor ander profiel
     * @param $profiel_identificatie integer|string profiel_id of nickname
     */
    public function van($profiel_identificatie)
    {
        $profiel = $this->lookup_profiel($profiel_identificatie);
        if ($profiel === NULL) {
            show_404();
        } else {
            $this->load->view('profile/display', array('profiel' => $profiel));
        }
    }

    /**
     * @param $profiel_identificatie
     * @return int|string|null
     */
    private function lookup_profiel($profiel_identificatie)
    {
        $this->load->model('profiel');

        // zoek voor ID als numeriek
        if (is_numeric($profiel_identificatie)) {
            $profiel = $this->profiel->query_by_id($profiel_identificatie);
            return $profiel;
            // als het een string is zoek voor nickname
        } elseif (is_string($profiel_identificatie)) {
            $profiel = $this->profiel->query_by_nickname($profiel_identificatie);
            return $profiel;
        }
        // anders return NULL (kan niet voorkomen)
        return NULL;
    }
}
