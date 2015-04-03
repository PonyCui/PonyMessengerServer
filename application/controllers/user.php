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
        $this->load->model('Token_manager', '', true);
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

    public function infos()
    {
        $ids = explode(',',$this->input->get_post('ids'));
        $results = $this->User_manager->request_users_information($ids);
        if (!empty($results)) {
            pms_output($results);
        }
        else {
            pms_output(null, -1, 'users not exist.');
        }
    }

    //仅允许用户获取与自己相关的关系链
    public function relation()
    {
        if (!pms_verify_token($this, $token_entity)) {
            pms_output(null, -1, 'invalid token.');
        }
        else {

            $from_user_entity = new User_entity;
            $from_user_entity->user_id = $token_entity->user_id;
            $result = $this->User_manager->request_user_relations($from_user_entity);
            pms_output($result);
        }
    }

    public function search()
    {
        if (!pms_verify_token($this)) {
            pms_output(null, -1, 'invalid token.');
        }
        else {
            $keyword = $this->input->get_post('keyword');
            pms_output($this->User_manager->search_user($keyword));
        }
    }

    public function relation_add()
    {
        if (!pms_verify_token($this, $token_entity)) {
            pms_output(null, -1, 'invalid token.');
        }
        else {
            $from_user_entity = new User_entity;
            $from_user_entity->user_id = $token_entity->user_id;
            $to_user_entity = new User_entity;
            $to_user_entity->user_id = $this->input->get_post('user_id');
            $code = $this->User_manager->add_relation($from_user_entity, $to_user_entity);
            if ($code >= 0) {
                pms_output($code);
            }
            else {
                pms_output(null, $code, 'received error.');
            }
        }
    }
}
