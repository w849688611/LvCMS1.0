<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/26
 * Time: 上午12:10
 */

namespace app\plugin\plugins\book\controller;


use app\base\controller\BaseController;
use app\base\enum\ClientTypeEnum;
use app\lib\validate\IDPositive;
use app\mail\model\MailModel;
use app\mail\validate\MailAddValidate;
use app\plugin\plugins\book\exception\MailNotBindException;
use app\plugin\plugins\book\validate\KindleMailValidate;
use app\portal\model\PostModel;
use app\service\MailService;
use app\service\ResultService;
use app\service\TokenService;
use app\user\exception\UserNotFoundException;
use app\user\model\UserModel;
use think\Request;

class BookFront extends BaseController
{
    protected $beforeActionList=['checkUserPermission'];

    /**发送书籍
     * @param Request $request
     * @return \think\response\Json
     * @throws MailNotBindException
     * @throws UserNotFoundException
     */
    public function sendBook(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $post=PostModel::where('id','=',$id)->find();
        $token=$request->header('token');
        $userId=TokenService::getCurrentVars($token,'id');
        $user=UserModel::get($userId);
        if(!$user){
            throw new UserNotFoundException();
        }
        if(!(is_array($user->more)&&array_key_exists('mailId',$user->more))){
            throw new MailNotBindException();
        }
        if(!array_key_exists('kindleMail',$user->more)){
            throw new MailNotBindException(['msg'=>'kindle邮箱未设置']);
        }
        $mailId=$user->more['mailId'];
        $mail=MailModel::get($mailId);
        $to=$user->more['kindleMail'];
        if(!$mail){
            throw new MailNotBindException();
        }
        if($post&&is_array($post->more)&&array_key_exists('fileList',$post->more)){
            $attachment=array();
            foreach ($post->more['fileList'] as $item){
                $attachment[]=$item['url'];
            }
            $result=MailService::sendMail($mail,$to,$post->title,$post->title,true,$attachment);
            return ResultService::success($result);
        }
        return ResultService::failure();
    }

    /**绑定邮箱信息
     * @param Request $request
     * @return \think\response\Json
     * @throws UserNotFoundException
     */
    public function bindMail(Request $request){
        $token=$request->header('token');
        $userId=TokenService::getCurrentVars($token,'id');
        $user=UserModel::get($userId);
        if(!$user){
            throw new UserNotFoundException();
        }
        $more=$user->more;
        if(!is_array($more)){
            $more=array();
        }
        if($request->has('address')){
            (new MailAddValidate())->goCheck();
            if(array_key_exists('mailId',$more)){
                $mail=MailModel::get($more['mailId']);
                if($request->has('smtp_host')){
                    $mail->smtp_host=$request->param('smtp_host');
                }
                if($request->has('smtp_port')){
                    $mail->smtp_port=$request->param('smtp_port');
                }
                if($request->has('account')){
                    $mail->account=$request->param('account');
                }
                if($request->has('password')){
                    $mail->password=$request->param('password');
                }
                if($request->has('address')){
                    $mail->address=$request->param('address');
                }
                $mail->save();
            }
            else{
                $mail=new MailModel($request->param());
                $mail->type=ClientTypeEnum::USER;
                $mail->allowField(true)->save();
                $more['mailId']=$mail->id;
            }
        }
        if($request->has('kindleMail')){
            (new KindleMailValidate())->goCheck();
            $more['kindleMail']=$request->param('kindleMail');
        }
        $user->more=$more;
        $user->save();
        return ResultService::success();
    }

    /**获取用户已绑定信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function getMailInfo(Request $request){
        $result=[
            'mail'=>'',
            'kindleMail'=>''
        ];
        $token=$request->header('token');
        $userId=TokenService::getCurrentVars($token,'id');
        $user=UserModel::get($userId);
        if(is_array($user->more)){
            if(array_key_exists('kindleMail',$user->more)){
                $result['kindleMail']=$user->more['kindleMail'];
            }
            if(array_key_exists('mailId',$user->more)){
                $mailId=$user->more['mailId'];
                $mail=MailModel::get($mailId);
                if($mail){
                    $result['mail']=$mail->toArray();
                }
            }
        }
        return ResultService::success('',$result);
    }
}