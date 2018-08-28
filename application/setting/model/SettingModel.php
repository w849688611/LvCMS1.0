<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/21
 * Time: 下午5:44
 */

namespace app\setting\model;


use think\Model;

class SettingModel extends Model
{
    protected $name='setting';

    public function items(){
        return $this->hasMany('SettingItemModel','setting_id');
    }
}