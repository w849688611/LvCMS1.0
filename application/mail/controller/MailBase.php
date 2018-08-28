<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/22
 * Time: 上午11:38
 */

namespace app\mail\controller;


use app\base\controller\BaseController;
use app\lib\validate\IDPositive;
use app\lib\validate\IDSPositive;
use app\mail\model\MailModel;
use app\mail\validate\MailAddValidate;
use app\service\MailService;
use app\service\ResultService;
use think\Request;

class MailBase extends BaseController
{
    protected $beforeActionList=['checkAdminPermission'];

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function add(Request $request){
        (new MailAddValidate())->goCheck();
        $mail=new MailModel($request->param());
        $mail->allowField(true)->save();
        return ResultService::success('添加系统邮箱成功');
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function delete(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $mail=MailModel::where('id','=',$id)->find();
            if($mail){
                $mail->delete();
                return ResultService::success('删除邮箱配置成功');
            }
            return ResultService::failure('邮箱配置未找到');
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            MailModel::where('id','in',$ids)->delete();
            return ResultService::success('删除邮箱配置成功');
        }
        return ResultService::failure();
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $mail=MailModel::where('id','=',$id)->find();
        if($mail){
            if($request->has('smtp_host')){
                $mail->smtp_host=$request->param('smtp_host');
            }
            if($request->has('smtp_port')){
                $mail->smtp_port=$request->param('smtp_port');
            }
            if($request->has('connect_type')){
                $mail->stmp_host=$request->param('connect_type');
            }
            if($request->has('account')){
                $mail->stmp_host=$request->param('account');
            }
            if($request->has('password')){
                $mail->password=$request->param('password');
            }
            if($request->has('address')){
                $mail->address=$request->param('address');
            }
            if($request->has('name')){
                $mail->name=$request->param('name');
            }
            if($request->has('client_type')){
                $mail->client_type=$request->param('client_type');
            }
            $mail->save();
            return ResultService::success('更新邮箱配置成功');
        }
        return ResultService::failure('邮箱配置未找到');
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function get(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $mail=MailModel::where('id','=',$id)->find();
            if($mail){
                return ResultService::makeResult(ResultService::Success,'',$mail->toArray());
            }
            else{
                return ResultService::failure('邮箱配置未找到');
            }
        }
        else{
            $mail=MailModel::select();
            return ResultService::makeResult(ResultService::Success,'',$mail->toArray());
        }
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function getByPage(Request $request){
        $pageResult=[];
        $query=new MailModel();
        $temp=new MailModel();
        if($request->has('clientType')){
            $query=$query->where('client_type','=',$request->param('clientType'));
            $temp=$temp->where('client_type','=',$request->param('clientType'));

        }
        if($request->has('address')){
            $query=$query->where('address','like','%'.$request->param('address').'%');
            $temp=$temp->where('address','like','%'.$request->param('address').'%');
        }
        $mails=$query->select();
        $pageResult['total']=count($mails);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $mails=$temp->page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$mails;
        return ResultService::success('',$pageResult);
    }

    /**发送邮件
     * @param Request $request
     * @return bool|\think\response\Json
     */
    public function sendMail(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $mail=MailModel::where('id','=',$id)->find();
        $to=$request->param('to/a');
        $subject='';
        if($request->has('subject')){
            $subject=$request->param('subject');
        }
        $content=$request->param('content');
        $attachment=array();
        if($request->has('attachment')){
            $attachment=$request->param('attachment/a');
        }
        if($mail){
            if(MailService::sendMail($mail,$to,$subject,$content,true,$attachment)){
                return ResultService::success('发送完成');
            }
        }
        return ResultService::failure('邮箱配置未找到');
    }
}