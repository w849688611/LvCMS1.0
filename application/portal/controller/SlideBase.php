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
use app\portal\model\SlideItemModel;
use app\portal\model\SlideModel;
use app\portal\validate\slide\SlideAddValidate;
use app\service\ResultService;
use think\Request;

class SlideBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];
    /**添加幻灯片组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        (new SlideAddValidate())->goCheck();
        $slide=new SlideModel($request->param());
        if($request->has('more')){
            $slide->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $slide->allowField(true)->save();
        return ResultService::success('添加幻灯片组成功');
    }

    /**删除幻灯片组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $slide=SlideModel::where('id','=',$id)->find();
            if($slide){
                SlideItemModel::where('slide_id','=',$slide->id)->delete();
                $slide->delete();
                return ResultService::success('删除幻灯片组成功');
            }
            else{
                return ResultService::failure('幻灯片组不存在');
            }
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            SlideModel::where('id','in',$ids)->delete();
            SlideItemModel::where('slide_id','in',$ids)->delete();
            return ResultService::success('删除幻灯片组成功');

        }
        return ResultService::failure();
    }

    /**更新幻灯片组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $slide=SlideModel::where('id','=',$id)->find();
        if($slide){
            if($request->has('name')){
                $slide->name=$request->param('name');
            }
            if($request->has('excerpt')){
                $slide->excerpt=$request->param('excerpt');
            }
            if($request->has('more')){
                $slide->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            $slide->allowField(true)->save();
            return ResultService::success('更新幻灯片组成功');
        }
        else{
            return ResultService::failure('幻灯片组不存在');
        }
    }

    /**获取幻灯片组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $slide=SlideModel::where('id','=',$id)->find();
            if($slide){
                $slide->item=SlideModel::generateItem($id);
                return ResultService::success('',$slide->toArray());
            }
            else{
                return ResultService::failure('幻灯片组不存在');
            }
        }
        else{
            $slides=SlideModel::select();
            return ResultService::success('',$slides->toArray());
        }
    }

    /**分页获取幻灯片组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        $pageResult=[];
        $slides=SlideModel::select()->toArray();
        $pageResult['total']=count($slides);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $slides=SlideModel::page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$slides;
        return ResultService::success('',$pageResult);
    }

    /**获取幻灯片组下的幻灯片想
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getItemOfSlide(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $items=SlideModel::generateItem($id);
        return ResultService::success('',$items);
    }
}