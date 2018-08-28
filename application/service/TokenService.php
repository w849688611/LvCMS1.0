<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 12:14
 */

namespace app\service;


use Cache;
use think\facade\Request;

class TokenService
{
    /**生成token
     * @param $payload
     * @return string
     */
    public static function generateToken($payload){
        $header=[
            'typ'=>'JWT',
            'alg'=>'md5'
        ];
        $encoding=base64_encode(json_encode($header)).base64_encode(json_encode($payload));
        return md5($encoding.config('security.salt'));
    }

    /**生成token并存入缓存
     * @param $payload
     * @return string
     */
    public static function initToken($payload){
        $token=self::generateToken($payload);
        Cache::set($token,$payload,7200);
        return $token;
    }

    /**获取token对应的payload中的变量
     * @param $token
     * @param $key
     * @return bool|mixed
     */
    public static function getCurrentVars($token,$key){
        $vars=Cache::get($token);
        if(!$vars){
            return false;
        }
        else{
            if(!is_array($vars)){
                $vars=json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }
            else{
                return false;
            }
        }
    }
    /**清除某一token
     * @param $token
     */
    public static function clearToken($token){
        Cache::rm($token);
    }

    /**验证token是否有效
     * @param $token
     * @return bool
     */
    public static function validToken($token){
        $payload=Cache::get($token);
        if($payload){
            return true;
        }
        return false;
    }

    /**验证访问者类型
     * @param $token
     * @param $clientType
     * @return bool
     */
    public static function validClientToken($token,$clientType){
        $payload=Cache::get($token);
        if($payload){
            return self::getCurrentVars($token,'clientType')==$clientType?true:false;
        }
    }

    /**验证是否为管理员大类
     * @param $token
     * @return bool
     */
    public static function validAdminToken($token){
        $payload=Cache::get($token);
        if($payload){
            return self::getCurrentVars($token,'isAdmin')=='1'?true:false;
        }
        return false;
    }

    /**验证是否为用户大类
     * @param $token
     * @return bool
     */
    public static function validUserToken($token){
        $payload=Cache::get($token);
        if($payload){
            return self::getCurrentVars($token,'isUser')=='1'?true:false;
        }
        return false;
    }
    public static function currentToken(){
        $request=Request::instance();
        return $request->header('token');
    }
}