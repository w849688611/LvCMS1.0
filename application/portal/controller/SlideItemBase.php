<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/15
 * Time: 下午3:59
 */

namespace app\portal\controller;


use app\base\controller\BaseController;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\lib\validate\IDSPositive;
use app\portal\model\SlideItemModel;
use app\portal\validate\slide\SlideItemAddValidate;
use app\service\ResultService;
use think\Request;

class SlideItemBase extends BaseController
{
    protected $beforeActionList=[
        'checkAdminPermission'
    ];
    /**添加幻灯片项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        (new SlideItemAddValidate())->goCheck();
        $slideItem=new SlideItemModel($request->param());
        if($request->has('more')){
            $slideItem->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $slideItem->allowField(true)->save();
        return ResultService::success('添加幻灯片项成功');
    }

    /**删除幻灯片项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $slideItem=SlideItemModel::where('id','=',$id)->find();
            if($slideItem){
                $slideItem->delete();
                return ResultService::success('删除幻灯片项成功');
            }
            return ResultService::failure('幻灯片项不存在');
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            SlideItemModel::where('id','in',$ids)->delete();
            return ResultService::success('删除幻灯片项成功');
        }
        return ResultService::failure();
    }

    /**更新幻灯片项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $slideItem=SlideItemModel::where('id','=',$id)->find();
        if($slideItem){
            if($request->has('name')){
                $slideItem->name=$request->param('name');
            }
            if($request->has('slide_id')){
                $slideItem->slide_id=$request->param('slide_id');
            }
            if($request->has('item_id')){
                $slideItem->item_id=$request->param('item_id');
            }
            if($request->has('link')){
                $slideItem->link=$request->param('link');
            }
            if($request->has('type')){
                $slideItem->type=$request->param('type');
            }
            if($request->has('img_url')){
                $slideItem->img_url=$request->param('img_url');
            }
            if($request->has('list_order')){
                $slideItem->list_order=$request->param('list_order');
            }
            if($request->has('more')){
                $slideItem->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            $slideItem->allowField(true)->save();
            return ResultService::success('更新幻灯片项成功');
        }
        else{
            return ResultService::failure('幻灯片项不存在');
        }
    }

    /**获取幻灯片项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $slideItem=SlideItemModel::where('id','=',$id)->with('slide')->find();
        if($slideItem){
            $slideItem->item=$slideItem->item;
            return ResultService::success('',$slideItem->toArray());
        }
        return ResultService::failure('幻灯片项不存在');
    }
}