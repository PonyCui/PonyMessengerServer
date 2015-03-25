<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PMS User Service
 */
class Token extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this -> load -> model('Token_manager', '', true);
        $this -> load -> model('Token_entity');
    }

    public function index()
    {
        $tokenObject = new Token_entity;
        $this -> Token_manager -> verify_entry($tokenObject);
    }
}
