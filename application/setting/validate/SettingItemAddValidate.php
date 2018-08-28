<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/21
 * Time: 下午10:33
 */

namespace app\setting\validate;


use app\lib\validate\BaseValidate;
use app\setting\enum\SettingItemTypeEnum;
use app\setting\model\SettingItemModel;

class SettingItemAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require|nameValid',
        'type'=>'require|typeValid',
        'setting_id'=>'positiveInt',
    ];
    protected $message=[
        'name.require'=>'配置项名称不能为空',
        'name.nameValid'=>'配置项名称重复',
        'type.require'=>'配置项类型不能为空',
        'type.typeValid'=>'配置项类型不合法',
        'setting_id.positiveInt'=>'配置组id必须为有效正整数'
    ];
    public function nameValid($value,$rule,$data){
        //含有组名则在组内查重
        if(is_array($data)&&array_key_exists('setting_id',$data)){
            $item=SettingItemModel::where('name','=',$value)
                ->where('setting_id',$data['setting_id'])
                ->find();
            if(!$item){
                return true;
            }
        }
        //不含组名则在默认组查重
        else{
            $item=SettingItemModel::whereOr('setting_id','=','0')
                ->whereOr('setting_id',"null")
                ->find();
            if(!$item){
                return true;
            }
        }
        return false;
    }
    public function typeValid($value){
        if($value==SettingItemTypeEnum::TEXT||$value==SettingItemTypeEnum::TEXTAREA||$value==SettingItemTypeEnum::IMAGE||$value==SettingItemTypeEnum::FILE){
            return true;
        }
        return false;
    }
}