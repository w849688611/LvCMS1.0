<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/30
 * Time: 下午5:56
 */

namespace plugins\test\controller;


use think\Controller;

class Test extends Controller
{
    public function index(){
        echo 'hello plugin';
    }
}