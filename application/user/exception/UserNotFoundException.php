<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/26
 * Time: 上午12:24
 */

namespace app\user\exception;



use app\lib\exception\BaseException;

class UserNotFoundException extends BaseException
{
    public $err='用户未找到';
    public $msg='30004';
}