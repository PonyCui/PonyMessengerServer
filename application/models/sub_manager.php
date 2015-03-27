<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Sub_manager extends CI_Model
{

    private $observers = array();

    public  $last_error = array(
        'error_code' => 0,
        'error_description' => '',
        'error_conn' => null
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Token_manager', '', true);
    }

    public function addObserver($conn, Token_entity $token)
    {
        if ($this->Token_manager->verify_entry($token)) {
            //验证通过
            $conn -> token = $token;
            $observers = null;

            //取出持久层数据
            if (defined("PUBSUB_CHANNEL")) {
                $mmc = memcache_init();
                $observers = memcache_get($mmc, 'channel.observers.'.(string)$token->user_id);
                if (empty($observers)) {
                    $observers = array();
                }
                else {
                    $observers = unserialize($observers);
                }
            }
            else {
                if (!isset($this -> observers[(string)$token->user_id])) {
                    $this -> observers[(string)$token->user_id] = array();
                }
                $observers = $this -> observers[(string)$token->user_id];
            }

            //检查超限
            if (count($observers) >= $this->config->item('pms')['sub']['user_max_connections']) {
                if ($this->config->item('pms')['sub']['user_over_connections_rule'] == 1) {
                    //踢走最早登录的连接
                    $conn_be_kicked = array_shift($observers);
                    $observers[] = $conn;
                    $this -> error(410, 'user connections over limit', $conn_be_kicked);
                }
                else if ($this->config->item('pms')['sub']['user_over_connections_rule'] == 2) {
                    //禁止创建新连接
                    $this -> error(410, 'user connections over limit');
                    return false;
                }
                else {
                    //未知的处理方式
                    $this -> error(410, 'user connections over limit');
                    return false;
                }
            }
            else {
                $observers[] = $conn;
                $this -> success();
            }

            //加入持久层
            if (defined("PUBSUB_CHANNEL")) {
                memcache_set($mmc, 'channel.observers.'.(string)$token->user_id, serialize($observers));
            }
            else {
                $this -> observers[(string)$token->user_id] = $observers;
            }

            return true;
        }
        else {
            $this -> error(403, 'invalid uid and token');
            return false;
        }
    }

    public function removeObserver($conn)
    {
        if (isset($conn -> token)) {
            $token = $conn -> token;
            if (defined("PUBSUB_CHANNEL")) {
                $mmc = memcache_init();
                $observers = memcache_get($mmc, 'channel.observers.'.(string)$token->user_id);
                if (!empty($observers)) {
                    $observers = unserialize($observers);
                    foreach ($observers as $key => $value) {
                        if ($conn->_connection_identifier == $value->_connection_identifier) {
                            unset($observers[$key]);
                            memcache_set($mmc, 'channel.observers.'.(string)$token->user_id, serialize($observers));
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
                return unserialize($observers);
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

    public function success()
    {
        $this -> last_error = array(
            'error_code' => 0,
            'error_description' => '',
            'error_conn' => null
        );
    }

    public function error($code, $description, $conn = null)
    {
        $this -> last_error['error_code'] = $code;
        $this -> last_error['error_description'] = $description;
        $this -> last_error['error_conn'] = $conn;
    }

}
