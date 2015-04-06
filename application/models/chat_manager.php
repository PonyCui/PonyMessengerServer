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
    * @brief 获取给定用户的全部会话
    * @return array -> Chat_session_entity
    **/
    public function all_sessions(User_entity $user)
    {
        $session_ids = $this->_user_sessions_ids($user);
        $this->db->from('chat_session');
        $this->db->where_in('session_id', $session_ids);
        $results = $this->db->get()->result('Chat_session_entity');
        return $results;
    }

    /**
    * @brief 创建一个会话
    * @return Chat_session_entity
    **/
    public function create_session(Chat_session_entity $session)
    {
        if (count($session -> session_users) < 2) {
            return false;
        }
        else if (count($session -> session_users) == 2) {
            //双人会话
            
        }
        else {
            //多人会话
        }
    }

    /**
    * @brief 获取给定用户的最新的500条Record
    * @return array -> Chat_record_entity
    **/
    public function recent_records(User_entity $user)
    {
        $session_ids = $this->_user_sessions_ids($user);
        $this->db->from('chat_record');
        $this->db->where_in('session_id', $session_ids);
        $this->db->order_by('record_id', 'desc');
        $this->db->limit(500);
        return $this->db->get()->result('Chat_record_entity');
    }

    /**
    * @brief 获取给定Record之后的所有Record
    * @return array -> Chat_record_entity
    **/
    public function newer_records(User_entity $user, Chat_record_entity $record)
    {
        $session_ids = $this->_user_sessions_ids($user);
        $this->db->from('chat_record');
        $this->db->where('record_id >', $record->record_id);
        $this->db->where_in('session_id', $session_ids);
        return $this->db->get()->result('Chat_record_entity');
    }

    private function _user_sessions_ids(User_entity $user)
    {
        $this->db->from('chat_session_user');
        $this->db->select('session_id');
        $session_ids = array();
        foreach ($this->db->get()->result_array() as $row) {
            $session_ids[] = $row['session_id'];
        }
        return $session_ids;
    }

}
