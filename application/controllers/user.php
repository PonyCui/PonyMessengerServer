<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class User extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_manager', '', true);
        $this->load->helper('Pms_output');
    }

    public function signup($email, $password)
    {
        $entity = new User_entity;
        $entity -> email = $email;
        $entity -> setPassword($password);
        if ($this->User_manager->add_user($entity)) {
            $token = $this->User_manager->verify_user($entity);
            if (!empty($token)) {
                pms_output($token);
            }
            else {
                pms_output(null, -2, 'request token failed.');
            }
        }
        else {
            pms_output(null, -1, 'email has been use.');
        }
    }

    public function singin($email, $password)
    {
        $entity = new User_entity;
        $entity -> email = $email;
        $entity -> setPassword($password);
        $token = $this->User_manager->verify_user($entity);
        if (!empty($token)) {
            pms_output($token);
        }
        else {
            pms_output(null, -1, 'wrong email and password.');
        }
    }
}
