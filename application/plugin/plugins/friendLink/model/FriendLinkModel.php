<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/19
 * Time: 上午10:00
 */

namespace app\plugin\plugins\friendLink\model;


use think\Model;

class FriendLinkModel extends Model
{
    protected $name='friend_link';

    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
}