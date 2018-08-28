<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/18
 * Time: 下午5:10
 */

namespace app\dashboard\controller;


use app\admin\model\AdminModel;
use app\admin\model\RoleModel;
use app\base\controller\BaseController;
use app\base\enum\ClientTypeEnum;
use app\mail\model\MailModel;
use app\plugin\model\PluginModel;
use app\portal\model\PostModel;
use app\service\ResultService;
use app\service\TokenService;
use app\user\model\UserModel;
use think\Request;

class DashBoardBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];
    public function getBackDashBoard(Request $request){
        $token=$request->header('token');
        $admin=[
            'account'=>TokenService::getCurrentVars($token,'account'),
            'status'=>TokenService::getCurrentVars($token,'status'),
        ];
        $role=RoleModel::where('id','=',TokenService::getCurrentVars($token,'role_id'))->find()->toArray();
        $admin['role']=$role;
        $adminCount=AdminModel::count();
        $userCount=UserModel::count();
        $postCount=PostModel::count();
        $pluginCount=PluginModel::count();
        $newPost=PostModel::limit(10)->field(['title','published_time','author'])->order('create_time','desc')->select()->toArray();
        $mailCount=MailModel::where('client_type','=',ClientTypeEnum::ADMIN);
        $data=[
            'adminCount'=>$adminCount,
            'userCount'=>$userCount,
            'postCount'=>$postCount,
            'pluginCount'=>$pluginCount,
            'newPost'=>$newPost,
            'mailCount'=>$mailCount,
            'admin'=>$admin
        ];
        return ResultService::success('',$data);
    }
}