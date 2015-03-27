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
        if ($entity->user_id > 0) {
            $this->load->model('Token_manager');
            return $this->Token_manager->request_token($entity);
        }
        return false;
    }

}
