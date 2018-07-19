<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/19
 * Time: 上午10:02
 */

namespace app\plugin\plugins\friendLink\validate;


use app\lib\validate\BaseValidate;

class FriendLinkAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require',
        'url'=>'require|activeUrl'
    ];
    protected $message=[
        'name.require'=>'友情链接名称不能为空',
        'url.require'=>'友情链接不能为空',
        'url.activeUrl'=>'链接需为有效链接'
    ];
}