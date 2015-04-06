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
     * @brief 请求完整的session信息
     * @param  Chat_session_entity $session
     * @return Chat_session_entity
     */
    public function request_session(Chat_session_entity $session)
    {
        if (!empty($session->session_id) && empty($session->session_users)) {
            //请求users
            $this->db->from('chat_session_user');
            $this->db->where('session_id', $session->session_id);
            $this->session_users = $this->db->get()->result('Chat_session_user_entity');
            return $session;
        }
        else if (empty($session->session_id) && !empty($session->session_users)) {
            //请求session_id
            $session_ids = array();
            foreach ($session->session_users as $session_user) {
                $session_ids[] = $session_user->user_id;
            }
            $this->db->from('chat_session_user');
            $this->db->where_in('user_id', $session_ids);
            $this->db->order_by('session_id', 'asc');
            $result = $this->db->get()->result('Chat_session_user_entity');
            foreach ($result as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                else if ($value->session_id == $result[$key-1]->session_id) {
                    $session->session_id = $value->session_id;
                    break;
                }
            }
            return $session;
        }
        else {
            return $session;
        }
    }

    /**
    * @brief 创建一个会话
    * @return Chat_session_entity
    **/
    public function create_session(User_entity $user, Chat_session_entity $session)
    {
        if (count($session->session_users) < 2) {
            return $session;
        }
        else if (count($session->session_users) == 2) {
            //双人会话，先查询后创建
            $session = $this->request_session($session);
            if (!empty($session->session_id)) {
                return $session;
            }
        }
        //多人会话，直接创建
        $this->load->model('User_manager');
        if ($this->User_manager->check_user_relations($user, $session->session_users)) {
            $this->db->insert('chat_session', $session);
            if ($this->db->affected_rows() > 0) {
                $session->session_id = $this->db->insert_id();
            }
            foreach ($session->session_users as $user_entity) {
                $session_user = new Chat_session_user_entity;
                $session_user->session_id = $session->session_id;
                $session_user->user_id = $user_entity->user_id;
                $this->db->insert('chat_session_user', $session_user);
            }
        }
        return $session;
    }

    /**
     * @brief 创建一条言论
     * @param  Chat_record_entity $record
     * @return Bool
     */
    public function create_record(Chat_record_entity $record)
    {
        unset($record->record_id);
        $this->db->insert('chat_record', $record);
        $is_succeed = $this->db->affected_rows() > 0;
        if ($is_succeed) {
            $this->load->model('Pub_manager');
            //Notify session users
            foreach ($this->_session_users_ids($record->session_id) as $user_id) {
                $this->Pub_manager->addNotify($user_id, 'chat', 'didAddRecord');
            }
            return true;
        }
        else {
            return false;
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

    /**
     * @brief 获取指定user中的所有会话ID
     * @param  User_entity $user
     * @return array array->int
     */
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
