<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pub_service extends CI_Model
{

    public $super_services = array();

    public function __construct()
    {
        parent::__construct();
        $this -> load -> model('Pub_manager', '', true);
        $this -> _configureTimer();
    }

    private function _configureTimer()
    {
        global $runloop;
        $runloop -> addPeriodicTimer(5, function() {
            $this -> _intervalPush();
        });
    }

    private function _intervalPush()
    {
        $messages = $this -> Pub_manager -> messages();
        foreach ($messages as $message) {
            $this -> post($message);
        }
    }

    public function post(Pub_entity $message)
    {
        $conns = $this->super_services['sub']->observers($message->sub_user_id);
        foreach ($conns as $conn) {
            $msg = pms_message($message->sub_service, $message->sub_method, json_decode($message->sub_params, true));
            print_r($msg);
            $conn -> send($msg);
        }
    }
}
