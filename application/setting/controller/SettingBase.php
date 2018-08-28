<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/21
 * Time: 上午10:30
 */

namespace app\setting\controller;


use app\base\controller\BaseController;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\lib\validate\IDSPositive;
use app\service\ResultService;
use app\setting\model\SettingItemModel;
use app\setting\model\SettingModel;
use app\setting\validate\SettingAddValidate;
use think\Request;

class SettingBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];
    /**添加配置组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        (new SettingAddValidate())->goCheck();
        $setting=new SettingModel($request->param());
        $setting->allowField(true)->save();
        return ResultService::success('添加配置组成功');
    }

    /**删除配置组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $setting=SettingModel::where('id','=',$id)->find();
            if($setting){
                SettingItemModel::where('setting_id','=',$setting->id)->delete();
                $setting->delete();
                return ResultService::success('删除配置组成功');
            }
            else{
                return ResultService::failure('配置组不存在');
            }
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            SettingModel::where('id','in',$ids)->delete();
            SettingItemModel::where('setting_id','in',$ids)->delete();
            return ResultService::success('删除配置组成功');

        }
        return ResultService::failure();
    }

    /**更新配置组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $setting=SettingModel::where('id','=',$id)->find();
        if($setting){
            if($request->has('name')){
                $setting->name=$request->param('name');
            }
            if($request->has('excerpt')){
                $setting->excerpt=$request->param('excerpt');
            }
            $setting->allowField(true)->save();
            return ResultService::success('更新配置组成功');
        }
        else{
            return ResultService::failure('配置组不存在');
        }
    }

    /**按id获取配置组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $setting=SettingModel::where('id','=',$id)->with('items')->find();
            if($setting){
                return ResultService::success('',$setting->toArray());
            }
            return ResultService::failure('配置组不存在');
        }
        else{
            $setting=SettingModel::select();
            return ResultService::success('',$setting->toArray());
        }
    }

    /**分页获取配置组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        $pageResult=[];
        $setting=SettingModel::select()->toArray();
        $pageResult['total']=count($setting);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $setting=SettingModel::page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$setting;
        return ResultService::success('',$pageResult);
    }

    /**获取配置组下配置项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getItemOfSetting(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $items=SettingItemModel::where('setting_id','=',$id)->select();
        return ResultService::success('',$items);
    }
}