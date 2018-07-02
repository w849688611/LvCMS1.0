<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 12:28
 */

namespace app\lib\validate;

use app\lib\exception\ParamException;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**自定义统一验证
     * @throws ParamException
     */
    public function goCheck(){
        $request=Request::instance();
        $result=$this->batch(true)->check($request->param());
        if(!$result){
            $e= new ParamException(['msg'=>$this->getError()]);
            throw $e;
        }
    }

    /**简单封装一下check 配合抛出异常
     * @param array $data
     * @throws ParamException
     */
    public function singleCheck($data=[]){
        $result=$this->batch(true)->check($data);
        if(!$result){
            $e=new ParamException(['msg'=>$this->getError()]);
            throw $e;
        }
    }
    public function positiveInt($value){
        if(is_numeric($value)&&is_int(intval($value))&&intval($value)>=0){
            return true;
        }
        return false;
    }
}