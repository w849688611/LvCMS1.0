<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/14
 * Time: 下午9:49
 */

namespace app\user\controller;


use app\base\controller\BaseController;
use app\lib\validate\IDPositive;
use app\service\ResultService;
use app\user\model\UserGroupModel;
use app\user\model\UserModel;
use app\user\validate\UserGroupAddValidate;
use think\Request;

class UserGroupBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];

    /**添加用户组
     * @param Request $request
     * @return \think\response\Json
     */
    public function add(Request $request){
        (new UserGroupAddValidate())->goCheck();
        $userGroup=new UserGroupModel($request->param());
        $userGroup->allowField(true)->save();
        return ResultService::success('添加用户组成功');
    }

    /**删除用户组
     * @param Request $request
     * @return \think\response\Json
     */
    public function delete(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $userGroup=UserGroupModel::where('id','=',$id)->find();
        if($userGroup){
            if(count($userGroup->user)>0){
                return ResultService::failure('用户组下仍有用户，无法删除');
            }
            $userGroup->delete();
            return ResultService::success('删除用户组成功');
        }
        else{
            return ResultService::failure('用户组不存在');
        }
    }

    /**更新用户组
     * @param Request $request
     * @return \think\response\Json
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $userGroup=UserGroupModel::where('id','=',$id)->find();
        if($request->has('name')){
            $userGroup->name=$request->param('name');
        }
        $userGroup->save();
        return ResultService::success('更新用户组成功');
    }

    /**获取用户组
     * @param Request $request
     * @return \think\response\Json
     */
    public function get(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $userGroup=UserGroupModel::where('id','=',$id)->find();
            if($userGroup){
                return ResultService::makeResult(ResultService::Success,'',$userGroup->toArray());
            }
            else{
                return ResultService::failure('用户组不存在');
            }
        }
        else{
            $userGroup=UserGroupModel::select();
            return ResultService::makeResult(ResultService::Success,'',$userGroup->toArray());
        }
    }

    /**获取用户组分页
     * @param Request $request
     * @return \think\response\Json
     */
    public function getByPage(Request $request){
        $pageResult=[];
        $userGroup=UserGroupModel::select()->toArray();
        $pageResult['total']=count($userGroup);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $userGroup=UserGroupModel::page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$userGroup;
        return ResultService::makeResult(ResultService::Success,'',$pageResult);
    }

    /**用户组下用户
     * @param Request $request
     * @return \think\response\Json
     */
    public function getUserOfGroup(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $pageResult=[];
        $users=UserModel::where('user_group_id','=',$id)->select();
        $pageResult['total']=count($users);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $users=UserModel::where('user_group_id','=',$id)->page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$users;
        return ResultService::makeResult(ResultService::Success,'',$pageResult);
    }
}