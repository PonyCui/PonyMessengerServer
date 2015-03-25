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
        foreach ($query->result('Pub_entity') as $row) {
            print_r($row);
        }
    }
}
