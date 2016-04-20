<?php


/**
 * Override security met betere XSS beveiliging
 * @property CI_Config  config
 */
class DD_Security extends CI_Security
{

    /**
     * DD_Input constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Extend xss_clean method.
     *
     * @param string|string[] $str
     * @param bool $is_image
     * @return string
     */
    public function xss_clean($str, $is_image = FALSE)
    {
        if ($is_image === FALSE && isset($str)) {
            if (!empty($str)) {
                $ci =& get_instance();
                $str = htmlspecialchars($str, ENT_QUOTES, $ci->config->item('charset'));
            }
        }
        return parent::xss_clean($str, $is_image);
    }
}
