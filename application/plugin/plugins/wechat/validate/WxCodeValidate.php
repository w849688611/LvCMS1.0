<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/28
 * Time: 下午10:49
 */

namespace app\plugin\plugins\wechat\validate;


use app\lib\validate\BaseValidate;

class WxCodeValidate extends BaseValidate
{
    protected $rule=[
        'code'=>'require'
    ];
}