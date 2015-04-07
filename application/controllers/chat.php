<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Chat extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Chat_manager', '', true);
        $this->load->model('User_manager', '', true);
        $this->load->model('Token_manager', '', true);
        $this->load->helper('Pms_output');
    }

    public function sessions()
    {
        if (!pms_verify_token($this, $token_entity)) {
            pms_output(null, -1, 'invalid token.');
        }
        else {
            $user_entity = new User_entity;
            $user_entity->user_id = $token_entity->user_id;
            $sessions = $this->Chat_manager->all_sessions($user_entity);
            pms_output($sessions);
        }
    }

    public function records()
    {
        $etag = intval($this->input->get_post('etag'));
        if (!pms_verify_token($this, $token_entity)) {
            pms_output(null, -1, 'invalid token.');
        }
        else {
            $user_entity = new User_entity;
            $user_entity->user_id = $token_entity->user_id;
            $records = array();
            if (empty($etag)) {
                $records = $this->Chat_manager->recent_records($user_entity);
            }
            else {
                $record_entity = new Chat_record_entity;
                $record_entity->record_id = $etag;
                $records = $this->Chat_manager->newer_records($user_entity, $record_entity);
            }
            pms_output($records);
        }
    }

    /**
     * @brief 发起一组会话
     **/
    public function raise()
    {
        if (!pms_verify_token($this, $token_entity)) {
            pms_output(null, -1, 'invalid token.');
        }
        else {
            $user_entity = new User_entity;
            $user_entity->user_id = $token_entity->user_id;
            $user_ids = $this->input->get_post('ids');
            $user_ids .= ','.$user_entity->user_id;
            $session_users = array();
            foreach (explode(',', $user_ids) as $user_id) {
                $session_user = new Chat_session_user_entity;
                $session_user -> user_id = $user_id;
                $session_users[] = $session_user;
            }
            $session = new Chat_session_entity;
            $session->session_users = $session_users;
            $session = $this->Chat_manager->create_session($user_entity, $session);
            if (!empty($session->session_id)) {
                pms_output($session);
            }
            else {
                pms_output(null, -2, 'fail to create sesssion.');
            }

        }

    }

    /**
     * @brief 发表一条言论
     */
    public function post()
    {
        if (!pms_verify_token($this, $token_entity)) {
            pms_output(null, -1, 'invalid token.');
        }
        else {
            $this->load->helper('pms_input_helper');
            $user_entity = new User_entity;
            $user_entity->user_id = $token_entity->user_id;
            $record = pms_input($this, 'Chat_record_entity');
            $record->from_user_id = $token_entity->user_id;
            $record->record_time = time();
            $isSucceed = $this->Chat_manager->create_record($user_entity, $record);
            if ($isSucceed) {
                pms_output(null);
            }
            else {
                pms_output(null, -2, 'unknown error.');
            }
        }
    }
}
