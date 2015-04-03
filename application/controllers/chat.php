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
            if (!empty($etag)) {
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
}
