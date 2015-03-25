<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Token_manager extends CI_Model
{

    /**
    * @return bool if YES 验证成功 else 验证失败
    **/
    public function verify_entry(Token_entity $entity)
    {
        $this -> db -> from('token');
        $this -> db -> where('user_id', $entity -> user_id);
        $this -> db -> where('session_token', $entity -> session_token);
        $count = $this -> db -> count_all_results();
        return $count > 0;
    }
}
