<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication library
 */
class Authentication
{
    const KEY = 'current_profiel';
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
     * @param $nickname string
     * @param $password string wachtwoord input van user
     * @return bool TRUE als authenticate succesvol is anders FALSE
     */
    public function authenticate($nickname, $password)
    {
        $this->ci->load->model('profiel');
        $profiel = $this->ci->profiel->query_by_nickname($nickname);
        if($profiel === NULL) {
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
            if($refresh) {
                $pid = $this->ci->session->{self::KEY}->pid;
                $profiel = $this->ci->profiel->query_by_id($pid);
                $this->login($profiel);
            }
            return $this->ci->session->{self::KEY};
        }
        return NULL;
    }
    
    public function is_authenticated() {
        return $this->get_current_profiel() !== NULL; 
    }
}
