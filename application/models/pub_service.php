<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pub_service extends CI_Model
{

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
            $this -> _intevalPush();
        });
    }

    private function _intevalPush()
    {
        echo "[IntevalPush]", "\n";
        $this -> Pub_manager -> messages();
    }

    private function send($message)
    {

    }
}
