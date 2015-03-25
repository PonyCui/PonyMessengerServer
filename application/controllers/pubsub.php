<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * PMS PubSub Service
 */
class PubSub extends CI_Controller implements MessageComponentInterface
{

    protected $clients;

    public function __construct() {
        parent::__construct();
        $this->load->helpers('Pms_protocol');
        $this->load->model('Sub_manager');
        $this->load->model('Token_entity');
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $service = pms_service($msg);
        $method = pms_method($msg);
        $params = pms_params($msg);
        if ($service == 'sub') {
            if ($method == 'addObserver') {
                $tokenObject = new Token_entity;
                $tokenObject -> user_id = isset($params['user_id']) ? $params['user_id'] : '';
                $tokenObject -> session_token = isset($params['session_token']) ? $params['session_token'] : '';
                $this->Sub_manager->addObserver($from, $tokenObject);
            }
        }

        // foreach ($this->clients as $client) {
        //     if ($from != $client) {
        //         $client->send($msg);
        //     }
        // }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
