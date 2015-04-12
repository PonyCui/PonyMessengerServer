<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class User_manager extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Token_manager', '', true);
    }

    /**
     * @brief 新增一个用户
     * @return Bool
     **/
    public function add_user(User_entity $entity)
    {
        $entity->user_id = null;
        $this->db->insert('user_base', $entity);
        if ($this->db->affected_rows() > 0) {
            $entity->user_id=$this->db->insert_id();
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @brief 校验用户
     * @return 返回Token_entity
     **/
    public function verify_user(User_entity $entity)
    {
        $this->db->from('user_base');
        $this->db->where('email', $entity->email);
        $this->db->where('password', $entity->password);
        $entity = $this->db->get()->row('0', 'User_entity');
        if (!empty($entity) && $entity->user_id > 0) {
            $this->load->model('Token_manager');
            return $this->Token_manager->request_token($entity);
        }
        return false;
    }

    /**
     * @brief 搜索用户
     * @return array -> user_id
     **/
    public function search_user($search_keyword)
    {
        $results = array();
        $this->db->from('user_base');
        $this->db->where('email', $search_keyword);
        foreach ($this->db->get()->result('User_entity') as $row) {
            $results[] = $row -> user_id;
        }
        return $results;
    }

    public function request_user(User_entity $entity)
    {
        $this->db->from('user_base');
        $this->db->where('user_id', $entity->user_id);
        return $this->db->get()->row(0, 'User_entity');
    }

    /**
     * @brief 请求用户信息
     * @return User_information_entity
     **/
    public function request_user_information(User_info_entity $entity)
    {
        $this->db->from('user_information');
        $this->db->where('user_id',$entity->user_id);
        $entity = $this->db->get()->row('0', 'User_info_entity');
        return $entity;
    }

    /**
     * @brief 请求多个用户信息
     * @return array -> User_information_entity
     **/
    public function request_users_information($ids)
    {
        $this->db->from('user_information');
        $this->db->where_in('user_id', $ids);
        return $this->db->get()->result('User_info_entity');
    }

    /**
     * @brief 查询用户关系
     * @return array -> User_relation_entity
     **/
    public function request_user_relations(User_entity $from_user_entity, User_entity $to_user_entity = null)
    {
        $this->db->from('user_relation');
        $this->db->where('from_user_id', $from_user_entity->user_id);
        if (!empty($to_user_entity)) {
            $this->db->where('to_user_id', $to_user_entity->user_id);
        }
        $results = $this->db->get()->result('User_relation_entity');
        if (!empty($results)) {
            if (!empty($to_user_entity)) {
                return array_pop($results);
            }
            else {
                return $results;
            }
        }
        else {
            return array();
        }
    }

    /**
     * 检查给定用户是否与后者均存在关系
     * @param  User_entity $from_user_entity
     * @param  Array       $to_user_entities array -> User_entity
     * @return bool
     */
    public function check_user_relations($from_user_entity, $to_user_entities)
    {
        $user_ids = array();
        if (is_numeric($to_user_entities[0])) {
            $user_ids = $to_user_entities;
        }
        else {
            foreach ($to_user_entities as $user_entity) {
                if ($user_entity->user_id == $from_user_entity->user_id) {
                    continue;
                }
                $user_ids[] = $user_entity->user_id;
            }
        }
        if (in_array($from_user_entity->user_id, $user_ids)) {
            unset($user_ids[array_search($from_user_entity->user_id, $user_ids)]);
        }
        $this->db->from('user_relation');
        $this->db->where('from_user_id', $from_user_entity->user_id);
        $this->db->where_in('to_user_id', $user_ids);
        if (count($user_ids) == $this->db->count_all_results()) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @brief 请求添加关系
     * @return int {
     * Need user agree = 1,
     * Succeed = 0
     * User not exist = -1
     * User disallow add contact = -3,
     * Unknown error = -99
     * }
     **/
    public function add_relation(User_entity $from_user_entity, User_entity $to_user_entity)
    {
        $exist_relation_entity = $this->request_user_relations($from_user_entity, $to_user_entity);
        $exist_user_entity = $this->request_user($to_user_entity);
        if (empty($exist_user_entity)) {
            return -1;
        }
        else if (!empty($exist_relation_entity)) {
            return 0;
        }
        else if ($this->_user_default($to_user_entity)->privacy_contact_need_agree) {
            //Need user agree
            return -2;
        }
        else {
            $relation_entity = new User_relation_entity;
            $relation_entity->from_user_id = $from_user_entity->user_id;
            $relation_entity->to_user_id = $to_user_entity->user_id;
            $this->db->insert('user_relation', $relation_entity);
            if ($this->db->affected_rows() > 0) {
                $this->load->model('Pub_manager', '', true);
                $this->Pub_manager->addNotify($from_user_entity->user_id, 'user', 'didChangeRelation');
                $this->Pub_manager->addNotify($to_user_entity->user_id, 'user', 'didChangeRelation');
                return 0;
            }
            else {
                return -99;
            }
        }
    }

    private function _user_default(User_entity $user_entity)
    {
        $this->db->from('user_default');
        $this->db->where('user_id', $user_entity->user_id);
        $result = $this->db->get()->row(0, 'User_default_entity');
        if (!empty($result)) {
            return $result;
        }
        else {
            $entity = new User_default_entity;
            $entity->user_id = $user_entity->user_id;
            return $entity;
        }
    }

}
