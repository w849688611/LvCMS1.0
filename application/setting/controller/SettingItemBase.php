<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/21
 * Time: 上午10:31
 */

namespace app\setting\controller;


use app\base\controller\BaseController;
use app\lib\validate\IDPositive;
use app\lib\validate\IDSPositive;
use app\service\ResultService;
use app\setting\model\SettingItemModel;
use app\setting\validate\SettingItemAddValidate;
use think\Request;

class SettingItemBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];

    /**添加配置项
     * @param Request $request
     * @return \think\response\Json
     */
    public function add(Request $request){
       
        (new SettingItemAddValidate())->goCheck();
        $settingItem=new SettingItemModel($request->param());
        if($request->has('more')){
            $settingItem->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $settingItem->allowField(true)->save();
        return ResultService::success('添加配置项成功');
    }

    /**删除配置项
     * @param Request $request
     * @return \think\response\Json
     */
    public function delete(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $settingItem=SettingItemModel::where('id','=',$id)->find();
            if($settingItem){
                $settingItem->delete();
                return ResultService::success('删除配置项成功');
            }
            return ResultService::failure('配置项不存在');
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            SettingItemModel::where('id','in',$ids)->delete();
            return ResultService::success('删除配置项成功');
        }
        return ResultService::failure();
    }

    /**更新配置项
     * @param Request $request
     * @return \think\response\Json
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $settingItem=SettingItemModel::where('id','=',$id)->find();
        if($settingItem){
            if($request->has('name')){
                $settingItem->name=$request->param('name');
            }
            if($request->has('type')){
                $settingItem->type=$request->param('type');
            }
            if($request->has('content')){
                $settingItem->content=$request->param('content');
            }
            if($request->has('setting_id')){
                $settingItem->setting_id=$request->param('setting_id');
            }
            if($request->has('status')){
                $settingItem->status=$request->param('status');
            }
            if($request->has('more')){
                $settingItem->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            $settingItem->allowField(true)->save();
            return ResultService::success('更新配置项成功');
        }
        else{
            return ResultService::failure('配置项不存在');
        }
    }

    /**获取配置项
     * @param Request $request
     * @return \think\response\Json
     */
    public function get(Request $request){
        (new IDPositive())->goCheck();
        if($request->has('id')){
            $id=$request->param('id');
            $settingItem=SettingItemModel::where('id','=',$id)->with('setting')->find();
            if($settingItem){
                return ResultService::success('',$settingItem->toArray());
            }
            return ResultService::failure('配置项不存在');
        }
        else{
            $settingItems=SettingItemModel::with('setting')->select();
            return ResultService::success('',$settingItems->toArray());
        }
    }

    /**分页获取配置项
     * @param Request $request
     * @return \think\response\Json
     */
    public function getByPage(Request $request){
        $query=new SettingItemModel();
        $temp=new SettingItemModel();
        if($request->has('name')){
            $query=$query->where('name','like','%'.$request->param('name').'%');
            $temp=$temp->where('name','like','%'.$request->param('name').'%');
        }
        if($request->has('type')){
            $query=$query->where('type','=',$request->param('type'));
            $temp=$temp->where('type','=',$request->param('type'));
        }
        if($request->has('setting_id')){
            $query=$query->where('setting_id','=',$request->param('setting_id'));
            $temp=$temp->where('setting_id','=',$request->param('setting_id'));
        }
        if($request->has('status')){
            $query=$query->where('status','=',$request->param('status'));
            $temp=$temp->where('status','=',$request->param('status'));
        }
        $pageResult=[];
        $settingItems=$query->select();
        $pageResult['total']=count($settingItems);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $settingItems=$temp->page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$settingItems;
        return ResultService::success('',$pageResult);
    }
}