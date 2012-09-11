<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


/**
 * Form token library. Avoids sending two times a forms when a user reloads a page.
 *
 * @author      Miguel Ayllón
 * @package     Form_token
 * @category    Libraries
 * @version     1.0.1
 * @url         https://github.com/laperla/codeigniter-Form_token 
 */
class Form_token
{
    const TOKEN_NAME = 'form_token';

    private $last_token;
    private $CI;


    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('form');

        $this->init();
    }
    

    /**
     * Initialize library
     *
     */
    private function init()
    {
        // Get last token and generate a new one
        $this->last_token = $this->CI->session->userdata(static::TOKEN_NAME);
        $this->CI->session->set_userdata(static::TOKEN_NAME, md5(uniqid(rand(), TRUE)));
    }


    /**
     * Gets the generated token
     *
     * @return string
     */
    public function get_token()
    {
        return $this->CI->session->userdata(static::TOKEN_NAME);
    }


    /**
     * Checks if last token is valid.
     */
    public function check()
    {
        $token = $this->CI->input->post(static::TOKEN_NAME);
        return ($this->last_token && $token && ($token != $this->last_token)) ? FALSE : TRUE;
    }


    /**
     * Sometimes the form token shouldn't be generated again, like on ajax requests
     * in a form. Call this function inside the controller function to restore the
     * previous form token.
     */
    public function restore()
    {
        $this->CI->session->set_userdata(static::TOKEN_NAME, $this->last_token);
    }


    /**
     * Obtains html for insert in forms
     * 
     * @return string
     */
    public function render()
    {
        return form_hidden(static::TOKEN_NAME, $this->get_token());
    }


}

// END Form_token class
/* End of file Form_token.php */