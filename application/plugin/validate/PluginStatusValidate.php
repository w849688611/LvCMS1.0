<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/3
 * Time: 上午11:27
 */

namespace app\plugin\validate;


use app\lib\validate\BaseValidate;
use app\plugin\enum\PluginStatusEnum;

class PluginStatusValidate extends BaseValidate
{
    protected $rule=[
        'status'=>'require|validStatus'
    ];
    protected $message=[
        'status.require'=>'插件状态不能为空',
        'status.validStatus'=>'插件状态不合法'
    ];
    public function validStatus($value){
        if($value==PluginStatusEnum::CLOSE||$value==PluginStatusEnum::OPEN||$value=PluginStatusEnum::UNINSTALL){
            return true;
        }
        return false;
    }
}