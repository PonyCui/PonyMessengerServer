<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Pub_manager extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this -> load -> model('Pub_entity');
    }

    public function messages()
    {
        $this -> db -> from('pub');
        $query = $this -> db -> get();
        $result = $query -> result('Pub_entity');
        $query -> free_result();
        return $result;
    }

    public function addMessage(Pub_entity $message)
    {
        $message -> pub_id = null;
        $this -> db -> insert('pub', $message);
    }
}
