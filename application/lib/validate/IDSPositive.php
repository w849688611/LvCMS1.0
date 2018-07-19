<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/17
 * Time: 下午11:25
 */

namespace app\lib\validate;


class IDSPositive extends BaseValidate
{
    protected $rule=[
        'ids'=>'require|idsPositiveInt'
    ];
    public function idsPositiveInt($value){
        if(is_array($value)){
            foreach ($value as $id){
                if(!$this->positiveInt($id)){
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}