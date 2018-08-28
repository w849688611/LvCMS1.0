<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/21
 * Time: 下午5:42
 */

namespace app\setting\validate;


use app\lib\validate\BaseValidate;
use app\setting\model\SettingModel;

class SettingAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require|nameValid'
    ];
    public function nameValid($value,$rule,$data){
        $item=SettingModel::where('name','=',$value)
            ->find();
        if(!$item){
            return true;
        }
        return false;
    }
}