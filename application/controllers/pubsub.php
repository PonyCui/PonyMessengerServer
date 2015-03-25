<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * PMS PubSub Service
 */
class PubSub extends CI_Controller implements MessageComponentInterface
{

    protected $clients;

    protected $services;

    public function __construct() {
        parent::__construct();
        $this->load->helpers('Pms_protocol');
        $this->load->model('Token_entity');
        $this->services = array(
            'sub' => new SubService
        );
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $service = pms_service($msg);
        $method = pms_method($msg);
        $params = pms_params($msg);
        if (isset($this->services[$service])) {
            if (method_exists($this->services[$service], $method)) {
                $this->services[$service] -> $method($from, $params);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->services['sub']->removeObserver($conn);
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

/**
 *
 */
class SubService extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sub_manager');
    }

    public function addObserver($conn, $params)
    {
        $tokenObject = new Token_entity;
        $tokenObject -> user_id = isset($params['user_id']) ? $params['user_id'] : '';
        $tokenObject -> session_token = isset($params['session_token']) ? $params['session_token'] : '';
        $result = $this->Sub_manager->addObserver($conn, $tokenObject);
        if ($result) {
            $this -> didAddObserver($conn);
        }
    }

    public function removeObserver($conn)
    {
        $this->Sub_manager->removeObserver($conn);
    }

    public function didAddObserver($conn)
    {

    }

    public function didRemoveObserver($conn)
    {

    }

    public function didReceivedError($conn, $error_description)
    {

    }
}
