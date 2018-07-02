<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/2
 * Time: 下午5:55
 */

namespace app\plugin\exception;


use app\lib\exception\BaseException;

class PluginConfigException extends BaseException
{
    public $msg='配置文件异常';
    public $err='70002';
}