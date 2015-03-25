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
            if (!isset($this -> observers[(string)$token->user_id])) {
                $this -> observers[(string)$token->user_id] = array();
            }
            $this -> observers[(string)$token->user_id][] = $conn;
            $conn -> token = $token;
            return true;
        }
        else {
            return false;
        }
    }

    public function removeObserver(ConnectionInterface $conn)
    {
        if (property_exists($conn, "token")) {
            $token = $conn -> token;
            if (isset($this -> observers[(string)$token->user_id])) {
                foreach ($this -> observers[(string)$token->user_id] as $key => $value) {
                    if ($conn == $value) {
                        unset($this -> observers[(string)$token->user_id][$key]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function observers($user_id)
    {
        if (isset($this->observers[(string)$user_id])) {
            return $this->observers[(string)$user_id];
        }
        else {
            return array();
        }
    }
}
