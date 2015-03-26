<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

    public function addObserver($conn, Token_entity $token)
    {
        if ($this->Token_manager->verify_entry($token)) {
            //验证通过
            if (defined("PUBSUB_CHANNEL")) {
                $mmc = memcache_init();
                $observers = memcache_get($mmc, 'channel.observers.'.(string)$token->userid);
                if (empty($observers)) {
                    $observers = array();
                }
                $observers[] = $conn;
                memcache_set($mmc, 'channel.observers.'.(string)$token->userid, $observers);
            }
            else {
                if (!isset($this -> observers[(string)$token->user_id])) {
                    $this -> observers[(string)$token->user_id] = array();
                }
                $this -> observers[(string)$token->user_id][] = $conn;
            }
            $conn -> token = $token;
            return true;
        }
        else {
            return false;
        }
    }

    public function removeObserver($conn)
    {
        if (isset($conn -> token)) {
            $token = $conn -> token;
            if (defined("PUBSUB_CHANNEL")) {
                $mmc = memcache_init();
                $observers = memcache_get($mmc, 'channel.observers.'.(string)$token->userid);
                if (!empty($observers)) {
                    foreach ($observers as $key => $value) {
                        if ($conn == $value) {
                            unset($observers[$key]);
                            memcache_set($mmc, 'channel.observers.'.(string)$token->userid, $observers);
                            return true;
                        }
                    }
                }
            }
            else {
                if (isset($this -> observers[(string)$token->user_id])) {
                    foreach ($this -> observers[(string)$token->user_id] as $key => $value) {
                        if ($conn == $value) {
                            unset($this -> observers[(string)$token->user_id][$key]);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function observers($user_id)
    {
        if (defined("PUBSUB_CHANNEL")) {
            $mmc = memcache_init();
            $observers = memcache_get($mmc, 'channel.observers.'.(string)$user_id);
            if (!empty($observers)) {
                return $observers;
            }
            else {
                return array();
            }
        }
        else {
            if (isset($this->observers[(string)$user_id])) {
                return $this->observers[(string)$user_id];
            }
            else {
                return array();
            }
        }
    }
}
