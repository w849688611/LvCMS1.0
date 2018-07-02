<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/2
 * Time: 下午5:50
 */

namespace app\plugin\exception;


use app\lib\exception\BaseException;

class PluginFileNotFoundException extends BaseException
{
    public $msg='插件主文件未找到';
    public $err='70001';
}