<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/28
 * Time: 下午10:47
 */

namespace app\plugin\plugins\wechat\controller;


use app\base\controller\BaseController;
use app\plugin\plugins\wechat\validate\WxCodeValidate;
use app\service\ResultService;
use app\service\TokenService;
use app\user\model\UserModel;
use think\Request;

class WeChatFront extends BaseController
{
    private $appId='wx7b21b45a6bf45284';
    private $appSecret='59dd8d3e04fe685d7530cd795cf9b0e1';
    public function wxLogin(Request $request){
        (new WxCodeValidate())->goCheck();
        $code=$request->param('code');
        //获取openid
        $url='https://api.weixin.qq.com/sns/jscode2session?appid='.$this->appId.'&secret='.$this->appSecret.'&js_code='.$code.'&grant_type=authorization_code';
        $wxResult=json_decode(curl_get($url),true);
        if(!$wxResult||!array_key_exists('openid',$wxResult)){
            return ResultService::failure('获取openid不成功');
        }
        $openId=$wxResult['openid'];
        $user=UserModel::where('more','like','%'.$openId.'%')->find();
        if(!$user){
            $user=new UserModel();
            $user->account=$openId;
            $user->password='000000';
            $more=array();
            $more['openId']=$openId;
            $user->more=$more;
            $user->allowField(true)->save();
            $user=UserModel::get($user->id);
        }
        $info=json_decode(htmlspecialchars_decode($request->param('info')),true);
        if(is_array($info)){
            $more=$user->more;
            if(!is_array($more)){
                $more=array();
            }
            $more['info']=$info;
            $user->more=$more;
            $user->isUpdate(true)->save();
        }
        $payload=[
            'id'=>$user->id,
            'account'=>$user->account,
            'type'=>$user->type,
            'status'=>$user->status,
            'errorCount'=>$user->error_count,
            'isUser'=>'1',
            'clientType'=>$user->type
        ];
        $token=TokenService::generateToken($payload);
        return ResultService::success('',['token'=>$token]);
    }
}