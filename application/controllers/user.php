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

    public function signup()
    {
        $entity = new User_entity;
        $entity -> email = $this->input->post('email');
        $entity -> setPassword($this->input->post('password'));
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

    public function signin()
    {
        $entity = new User_entity;
        $entity -> email = $this->input->post('email');
        $entity -> setPassword($this->input->post('password'));
        $token = $this->User_manager->verify_user($entity);
        if (!empty($token)) {
            pms_output($token);
        }
        else {
            pms_output(null, -1, 'wrong email and password.');
        }
    }

    public function info()
    {
        $entity = new User_info_entity;
        $entity -> user_id = $this->input->get_post('user_id');
        $entity = $this->User_manager->request_user_information($entity);
        if (!empty($entity)) {
            pms_output($entity);
        }
        else {
            pms_output(null, -1, 'user not exist.');
        }
    }
}
