<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午6:33
 */

namespace app\portal\controller;


use app\base\controller\BaseController;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\lib\validate\IDSPositive;
use app\portal\model\NavItemModel;
use app\portal\model\NavModel;
use app\portal\validate\nav\NavAddValidate;
use app\service\ResultService;
use think\Request;

class NavBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];
    /**添加导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        (new NavAddValidate())->goCheck();
        $nav=new NavModel($request->param());
        if($request->has('more')){
            $nav->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $nav->allowField(true)->save();
        return ResultService::success('添加导航组成功');
    }

    /**删除导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $nav=NavModel::where('id','=',$id)->find();
            if($nav){
                NavItemModel::where('nav_id','=',$nav->id)->delete();
                $nav->delete();
                return ResultService::success('删除导航组成功');
            }
            return ResultService::failure('导航组不存在');
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            NavModel::where('id','in',$ids)->delete();
            NavItemModel::where('nav_id','in',$ids)->delete();
            return ResultService::success('删除导航组成功');
        }
        return ResultService::failure();

    }

    /**更新导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $nav=NavModel::where('id','=',$id)->find();
        if($nav){
            if($request->has('name')){
                $nav->name=$request->param('name');
            }
            if($request->has('excerpt')){
                $nav->excerpt=$request->param('excerpt');
            }
            if($request->has('more')){
                $nav->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            $nav->allowField(true)->save();
            return ResultService::success('更新导航组成功');
        }
        else{
            return ResultService::failure('导航组不存在');
        }
    }

    /**获取导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $nav=NavModel::where('id','=',$id)->find();
            if($nav){
                $nav->item=NavModel::generateItemTree($id);
                return ResultService::success('',$nav->toArray());
            }
            else{
                return ResultService::failure('导航组不存在');
            }
        }
        else{
            $navs=NavModel::select();
            return ResultService::success('',$navs->toArray());
        }
    }

    /**分页获取导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        $pageResult=[];
        $navs=NavModel::select()->toArray();
        $pageResult['total']=count($navs);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $navs=NavModel::page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$navs;
        return ResultService::success('',$pageResult);
    }

    /**获取导航组下的导航项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getItemOfNav(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $items=NavModel::generateItemTree($id);
        return ResultService::success('',$items);
    }
}