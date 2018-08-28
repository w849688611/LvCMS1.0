<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/22
 * Time: 上午11:44
 */

namespace app\mail\validate;



use app\lib\validate\BaseValidate;

class MailAddValidate extends BaseValidate
{
    protected $rule=[
        'smtp_host'=>'require',
        'smtp_port'=>'require|integer',
        'account'=>'require',
        'password'=>'require',
        'address'=>'require'
    ];
}