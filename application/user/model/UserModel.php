<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/30
 * Time: 上午12:27
 */

namespace app\user\model;


use think\Model;

class UserModel extends Model
{
    protected $name='user';
    protected $autoWriteTimestamp=true;
    protected $json=['more'];
    protected $jsonAssoc = true;

    public function setPasswordAttr($value){
        return md5(config('security.salt').$value);
    }
    public function checkPassword($password){
        if(md5(config('security.salt').$password)==$this->password){
            return true;
        }
        return false;
    }
    public function userGroup(){
        return $this->belongsTo('UserGroupModel','user_group_id');
    }
}