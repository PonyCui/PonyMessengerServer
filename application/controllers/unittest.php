<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 单元测试控制器，只允许在测试和开发环境进行单元测试
 * setup 注入测试数据
 * tearDown 删除测试数据
 */
class UnitTest extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (ENVIRONMENT == "production") {
            die('unit test is not allow under production environment.');
        }
        $this->load->database();
    }

    public function setUp()
    {
        {
            $this -> db -> query('ALTER TABLE pms_user_base AUTO_INCREMENT = 1001');
            $user = new User_entity;
            $user -> user_id = 100;
            $user -> email = @"unit@test.com";
            $user -> setPassword('123456');
            $this -> db -> insert('user_base', $user);
        }
        {
            $info = new User_info_entity;
            $info -> user_id = 100;
            $info -> nickname = "UnitTester";
            $info -> avatar = "http://tp4.sinaimg.cn/1961248227/180/5706181721/0";
            $this -> db -> insert('user_information', $info);
        }
    }

    public function tearDown()
    {
        {
            $this -> db -> query('delete from pms_user_base where user_id >= 100 and user_id < 1000');
            $this -> db -> query('delete from pms_user_information where user_id >= 100 and user_id < 1000');
        }
    }
}
