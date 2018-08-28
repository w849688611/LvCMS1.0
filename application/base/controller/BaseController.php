<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/20
 * Time: 下午12:51
 */

namespace app\base\controller;


use app\base\enum\ClientTypeEnum;
use app\lib\exception\TokenException;
use app\service\TokenService;
use think\Controller;
use think\facade\Request;

class BaseController extends Controller
{
    /**
     * @param $clientType 验证client的类型
     * @param bool $isThrow 是否以抛出异常的方式，默认为true
     * @return bool
     * @throws TokenException
     */
    public static function checkClientPermission($clientType,$isThrow=true){
        $request=Request::instance();
        $result=TokenService::validClientToken($request->header('token'),$clientType);
        if(!$result){
            if($isThrow){
                throw new TokenException();
            }
            return false;
        }
        return true;
    }

    /**
     * 验证是否为管理员
     */
    public static function checkAdminPermission(){
        self::checkClientPermission(ClientTypeEnum::ADMIN);
    }

    /**
     * 验证是否为普通用户
     */
    public static function checkUserPermission(){
        self::checkClientPermission(ClientTypeEnum::USER);
    }
}