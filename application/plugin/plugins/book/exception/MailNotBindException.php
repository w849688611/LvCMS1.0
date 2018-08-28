<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/26
 * Time: 上午12:28
 */

namespace app\plugin\plugins\book\exception;


use app\lib\exception\BaseException;

class MailNotBindException extends BaseException
{
    public $msg='邮箱未绑定';
}