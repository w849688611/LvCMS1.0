<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/30
 * Time: 下午4:26
 */

namespace plugins\test;


use app\plugin\Plugin;

class Mall extends Plugin
{
    public $hookList=[];
    public static function install()
    {
        parent::install(); // TODO: Change the autogenerated stub
        return true;
    }
    public function testStart(){
        echo 123;
    }
    public static function update()
    {
       return true;
    }
    public static function uninstall()
    {
        parent::uninstall(); // TODO: Change the autogenerated stub
        return true;
    }

}