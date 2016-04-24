<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication library
 */
class Authentication
{
    const KEY = 'current_profiel';

    // privilege levels
    const ANONYMOUS = 1;
    const USER = 2;
    const ADMIN = 3;

    /**
     * @var CI_Controller|null
     */
    private $ci = NULL;

    /**
     * Authentication constructor.
     */
    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('profiel');
    }

    /**
     * @param $profiel StdClass profiel to login
     * @throws InvalidArgumentException als profiel invalid data bevat
     */
    public function login($profiel)
    {
        if (isset($profiel->pid)) {
            $this->ci->session->set_userdata(self::KEY, $profiel);
        } else {
            throw new InvalidArgumentException('Invalid Profiel object');
        }
    }

    public function logout()
    {
        $this->ci->session->unset_userdata(self::KEY);
    }

    /**
     * Authenticates and logs in user
     * @param $email string
     * @param $password string wachtwoord input van user
     * @return bool TRUE als authenticate succesvol is anders FALSE
     */
    public function authenticate($email, $password)
    {
        $this->ci->load->model('profiel');
        $profiel = $this->ci->profiel->query_by_email($email);
        if ($profiel === NULL) {
            return FALSE;
        }

        $hash = $profiel->password;
        if (password_verify($password, $hash)) {
            $this->login($profiel);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param bool $refresh refreshes data if true
     * @return null|stdClass Profiel als ingelogd anders NULL
     */
    public function get_current_profiel($refresh = TRUE)
    {
        if ($this->ci->session->has_userdata(self::KEY)) {
            if ($refresh) {
                $pid = $this->ci->session->{self::KEY}->pid;
                $profiel = $this->ci->profiel->query_by_id($pid);
                $this->login($profiel);
            }
            return $this->ci->session->{self::KEY};
        }
        return NULL;
    }

    public function is_authenticated()
    {
        $profiel = $this->get_current_profiel();
        return $profiel !== NULL && is_object($profiel) === TRUE;
    }

    /**
     * @return int constant die aangeeft wat de privileges van de huidige gebruiker zijn
     */
    public function get_privileges()
    {
        $profiel = $this->get_current_profiel(TRUE);
        if (!$this->is_authenticated()) {
            return self::ANONYMOUS;
        }
        if ((bool) intval($profiel->is_admin) === TRUE) {
            return self::ADMIN;
        }
        return self::USER;
    }
}
