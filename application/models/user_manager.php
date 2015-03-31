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

    public function request_user_information(User_info_entity $entity)
    {
        $this->db->from('user_information');
        $this->db->where('user_id',$entity->user_id);
        $entity = $this->db->get()->row('0', 'User_info_entity');
        return $entity;
    }

    public function request_users_information($ids)
    {
        $this->db->from('user_information');
        $this->db->where_in('user_id', $ids);
        return $this->db->get()->result_array('User_info_entity');
    }

    public function request_user_relations(User_entity $from_user_entity, User_entity $to_user_entity = null)
    {
        $this->db->from('user_relation');
        $this->db->where('from_user_id', $from_user_entity->user_id);
        if (!empty($to_user_entity)) {
            $this->where('to_user_id', $to_user_entity->user_id);
        }
        $results = $this->db->get()->result_array('User_relation_entity');
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

}
