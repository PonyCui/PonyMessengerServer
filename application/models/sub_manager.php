<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 *
 */
class Sub_manager extends CI_Model
{

    private $observers = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Token_manager', '', true);
    }

    public function addObserver(ConnectionInterface $conn, Token_entity $token)
    {
        if ($this->Token_manager->verify_entry($token)) {
            //验证通过
            print_r($token);
            return true;
        }
        else {
            print_r('fail');
            return false;
        }
    }

    public function removeObserver(ConnectionInterface $conn, Sub_entity $observer)
    {

    }
}
