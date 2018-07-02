<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/1
 * Time: 下午11:48
 */

namespace app\plugin\validate;


use app\lib\validate\BaseValidate;

class PluginInfoValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require',
        'author'=>'require'
    ];
    protected $message=[
        'name.require'=>'插件名称不能为空',
        'author.require'=>'插件作者不能为空'
    ];
}