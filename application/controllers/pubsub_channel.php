<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class PubSub_Channel extends CI_Controller
{
    public function connected()
    {
        echo 'connected';
    }

    public function disconnected()
    {
        echo 'disconnected';
    }

    public function message()
    {
        echo 'message';
    }

}
