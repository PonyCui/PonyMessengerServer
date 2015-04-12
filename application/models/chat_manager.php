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
    public function all_sessions(User_entity $user, $etag = 0)
    {
        $session_ids = $this->_user_sessions_ids($user);
        $this->db->from('chat_session');
        $this->db->where_in('session_id', $session_ids);
        if (!empty($etag)) {
            $this->db->where('session_last_update >=',$etag);
        }
        $sessions = $this->db->get()->result('Chat_session_entity');
        $this->db->from('chat_session_user');
        $this->db->where_in('session_id', $session_ids);
        $session_users = $this->db->get()->result('Chat_session_user_entity');

        $tmp = array();
        foreach ($sessions as $key => $value) {
            $tmp[$value->session_id] = $key;
        }

        foreach ($session_users as $key => $value) {
            if (isset($tmp[$value->session_id])) {
                $sessions[$tmp[$value->session_id]] -> session_users[] = $value;
                $sessions[$tmp[$value->session_id]] -> session_user_ids[] = $value->user_id;
            }
        }
        return $sessions;
    }

    /**
    * @brief 获取单个会话信息
    * @return Chat_session_entity
    **/
    public function session_with_id(User_entity $user, $session_id)
    {
        $this->db->from('chat_session');
        $this->db->where('session_id', $session_id);
        $session = $this->db->get()->row(0,'Chat_session_entity');
        $this->db->from('chat_session_user');
        $this->db->where('session_id', $session_id);
        $session->session_users = $this->db->get()->result('Chat_session_user_entity');
        foreach ($session->session_users as $key => $value) {
            $session->session_user_ids[] = $value->user_id;
        }
        if (in_array($user->user_id, $session->session_user_ids)) {
            return $session;
        }
        else {
            return false;
        }
    }

    /**
    * @brief 根据用户ID数组，获取单个会话信息
    * @return Chat_session_entity
    **/
    public function session_with_user_ids(User_entity $user, $user_ids)
    {
        $this->db->from('chat_session_user');
        $this->db->where_in('user_id', $user_ids);
        $this->db->order_by('session_id', 'asc');
        $session_users = $this->db->get()->result('Chat_session_user_entity');
        $session_users_count = array();
        foreach ($session_users as $key => $value) {
            if (!isset($session_users_count[$value->session_id])) {
                $session_users_count[$value->session_id] = 0;
            }
            $session_users_count[$value->session_id]++;
        }
        $session_id = array_search(count($user_ids), $session_users_count);
        if (!empty($session_id)) {
            return $this->session_with_id($user, $session_id);
        }
        else {
            if ($this->create_session_with_user_ids($user, $user_ids)) {
                return $this->session_with_user_ids($user, $user_ids);
            }
            else {
                return false;
            }
        }
    }

    /**
    * @brief 根据用户ID数组，创建一个会话
    * @return Bool
    **/
    public function create_session_with_user_ids(User_entity $user, $user_ids)
    {
        $this->load->model('User_manager');
        $this->load->model('Pub_manager');
        if ($this->User_manager->check_user_relations($user, $user_ids)) {
            $session = new Chat_session_entity;
            if (count($user_ids) == 2) {
                $session->session_type = 1;//对话
            }
            else if (count($user_ids) > 2) {
                $session->session_type = 2;//群聊
            }
            $session->session_last_update = time();
            $session->session_user_ids = $user_ids;
            $this->db->insert('chat_session', $session);
            if ($this->db->affected_rows() > 0) {
                $session->session_id = $this->db->insert_id();
                foreach ($session->session_user_ids as $key => $value) {
                    $session_user = new Chat_session_user_entity;
                    $session_user->session_id = $session->session_id;
                    $session_user->user_id = $value;
                    $this->db->insert('chat_session_user', $session_user);
                    if ($this->db->affected_rows() > 0) {
                        $this->Pub_manager->addNotify($session_user->user_id, 'chat', 'didAddSession');
                    }
                    else {
                        return false;
                    }
                }
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function update_session_with_post(Chat_session_entity $session, $post_string)
    {
        if (!empty($session)) {
            $session->session_last_update = time();
            $session->session_last_post = $post_string;
            $this->db->where('session_id', $session->session_id);
            $this->db->update('chat_session', $session);
            if ($this->db->affected_rows() > 0) {
                $this->load->model('Pub_manager');
                foreach ($session->session_users as $session_user) {
                    $this->Pub_manager->addNotify($session_user->user_id, 'chat', 'didUpdateSession', array('session_id', $session->session_id));
                }
            }
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
     * @brief 创建一条言论
     * @param  Chat_record_entity $record
     * @return Bool
     */
    public function create_record(User_entity $user, Chat_record_entity $record)
    {
        unset($record->record_id);
        $sessions = $this->_user_sessions_ids($user);
        if (in_array($record->session_id, $sessions)) {
            $this->db->insert('chat_record', $record);
            if ($this->db->affected_rows() > 0) {
                $this->load->model('Pub_manager');
                $session = $this->session_with_id($user, $record->session_id);
                $this->Pub_manager->addNotify($record->from_user_id, 'chat', 'didAddRecord');
                foreach ($session->session_users as $session_user) {
                    $this->Pub_manager->addNotify($session_user->user_id, 'chat', 'didAddRecord');
                }
                $this->update_session_with_post($session, $record->record_title);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
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
        $this->db->where('user_id', $user->user_id);
        $session_ids = array();
        foreach ($this->db->get()->result_array() as $row) {
            $session_ids[] = $row['session_id'];
        }
        return $session_ids;
    }

}
