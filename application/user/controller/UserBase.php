<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/30
 * Time: 上午12:10
 */

namespace app\user\controller;


use app\base\controller\BaseController;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\lib\validate\IDSPositive;
use app\service\ResultService;
use app\user\enum\UserStatusEnum;
use app\user\model\UserModel;
use app\user\validate\UserAddValidate;
use app\user\validate\UserPasswordValidate;
use app\user\validate\UserStatusValidate;
use think\Request;

/**管理员后台对用户操作
 * Class UserBase
 * @package app\user\controller
 */
class UserBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];
    /**添加用户
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        (new UserAddValidate())->goCheck();
        $user=new UserModel($request->param());
        $user->password=$request->param('password');
        $user->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        $user->allowField(true)->save();
        return ResultService::success('添加用户成功');
    }

    /**删除用户
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $user=UserModel::where('id','=',$id)->find();
            if($user){
                $user->delete();
                return ResultService::success('删除用户成功');
            }
            else{
                return ResultService::failure('用户不存在');
            }
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            UserModel::where('id','in',$ids)->delete();
            return ResultService::success('删除用户成功');
        }
        return ResultService::failure();
    }

    /**更新用户
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $user=UserModel::where('id','=',$id)->find();
        if($user){
            if($request->has('password')){
                (new UserPasswordValidate())->goCheck();
                $user->password=$request->param('password');
            }
            if($request->has('type')){
                $user->type=$request->param('type');
            }
            if($request->has('status')){
                (new UserStatusValidate())->goCheck();
                $status=$request->param('status');
                $user->status=$status;
                if($status==UserStatusEnum::NORMAL){
                    $user->error_count=0;
                }
            }
            if($request->param('more')){
                $user->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            if($request->has('user_group_id')){
                $user->user_group_id=$request->param('user_group_id');
            }
            $user->save();
            return ResultService::success('更新用户成功');
        }
        else{
            return ResultService::failure('用户不存在');
        }
    }

    /**获取用户信息
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $user=UserModel::where('id','=',$id)->with('userGroup')->find()->hidden();
            if($user){
                return ResultService::makeResult(ResultService::Success,'',$user->toArray());
            }
            else{
                return ResultService::failure('用户不存在');
            }
        }
        else{
            $users=UserModel::select();
            return ResultService::makeResult(ResultService::Success,'',$users->toArray());
        }
    }

    /**分页获取用户
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        $pageResult=[];
        $user=UserModel::with('userGroup')->select()->toArray();
        $pageResult['total']=count($user);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $user=UserModel::page($page,$pageSize)->with('userGroup')->select()->toArray();
        }
        $pageResult['pageData']=$user;
        return ResultService::makeResult(ResultService::Success,'',$pageResult);
    }
}