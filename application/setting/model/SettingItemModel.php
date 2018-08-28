<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/21
 * Time: 下午5:44
 */

namespace app\setting\model;


use think\Model;

class SettingItemModel extends Model
{
    protected $name='setting_item';
    public function setting(){
        return $this->belongsTo('SettingModel','setting_id');
    }
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
}