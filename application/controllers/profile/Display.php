<?php

/**
 *
 * @property Authentication authentication
 * @property Profiel profiel
 * @property Like like
 * @property CI_DB_pdo_driver|CI_DB_query_builder db
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
            $this->load_view($profiel);
        }
    }

    /**
     * Route voor ander profiel
     * @param $profiel_identificatie integer|string profiel_id of nickname
     */
    public function van($profiel_identificatie)
    {
        $profiel_identificatie = urldecode($profiel_identificatie);
        $profiel = $this->lookup_profiel($profiel_identificatie);
        if ($profiel === NULL) {
            show_404();
        } else {
            $this->load_view($profiel);
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

    /**
     * @param $profiel
     */
    private function load_view($profiel)
    {
        $this->load->view('profile/display', array(
            'profiel' => $profiel,
            'like_status' => $this->get_like_status($profiel)));
    }

    private function get_like_status($profiel)
    {
        $logged_in_profiel = current_profiel();
        if ($logged_in_profiel === NULL || $profiel->pid === $logged_in_profiel->pid) {
            return FALSE;
        }
        $gegeven_likes = $this->like->query_mijn_gegeven_likes();
        foreach ($gegeven_likes as $like) {
            if ($like->pid === $profiel->pid) {
                return Like::GEGEVEN_LIKE;
            }
        }
        $wederzijds = $this->like->query_wederzijdse_likes();
        foreach ($wederzijds as $like) {
            if ($like->pid === $profiel->pid) {
                return Like::WEDERZIJDSE_LIKE;
            }
        }
        $ontvangen = $this->like->query_mijn_ontvangen_likes();
        foreach ($ontvangen as $like) {
            if ($like->pid === $profiel->pid) {
                return Like::ONTVANGEN_LIKE;
            }
        }

        return Like::GEEN_LIKE;
    }
}
