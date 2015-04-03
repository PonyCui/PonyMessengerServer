<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Chat_manager extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Chat_entity');
    }

    /**
    * @brief 获取给定用户的最新的500条Record
    * @return array -> Record_entity
    **/
    public function recent_records(User_entity $user)
    {
        $this->db->from('chat_record');
        $this->db->where('to_user_id', $user->user_id);
        $this->db->order_by('record_id', 'desc');
        $this->db->limit(500);
        return $this->db->get()->result('Chat_record_entity');
    }

    /**
    * @brief 获取给定Record之后的所有Record
    * @return array -> Record_entity
    **/
    public function newer_records(User_entity $user, Chat_record_entity $record)
    {
        $this->db->from('chat_record');
        $this->db->where('record_id >', $record->record_id);
        $this->db->where('to_user_id', $user->user_id);
        $this->db->order_by('record_id', 'desc');
        return $this->db->get()->result('Chat_record_entity');
    }
    
}
