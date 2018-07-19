<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/19
 * Time: 上午9:57
 */

namespace app\plugin\plugins\friendLink\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\lib\validate\IDSPositive;
use app\plugin\plugins\friendLink\model\FriendLinkModel;
use app\plugin\plugins\friendLink\validate\FriendLinkAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class FriendLinkBase extends Controller
{
    /**添加友情链接
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new FriendLinkAddValidate())->goCheck();
        $friendLink=new FriendLinkModel($request->param());
        if($request->has('more')){
            $friendLink->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $friendLink->allowField(true)->save();
        return ResultService::success('添加友情链接成功');
    }

    /**删除友情链接
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $friendLink=FriendLinkModel::where('id','=',$id)->find();
            if($friendLink){
                $friendLink->delete();
                return ResultService::success('删除友情链接成功');
            }
            else{
                return ResultService::failure('友情链接不存在');
            }
        }
        else if($request->has('ids')){
            (new IDSPositive())->goCheck();
            $ids=$request->param('ids/a');
            FriendLinkModel::where('id','in',$ids)->delete();
            return ResultService::success('删除友情链接成功');
        }
        return ResultService::failure();
    }

    /**更新友情链接
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $friendLink=FriendLinkModel::where('id','=',$id)->find();
        if($friendLink){
            if($request->has('name')){
                $friendLink->name=$request->param('name');
            }
            if($request->has('url')){
                $friendLink->url=$request->param('url');
            }
            if($request->has('status')){
                $friendLink->status=$request->param('status');
            }
            if($request->has('list_order')){
                $friendLink->list_order=$request->param('list_order');
            }
            if($request->has('more')){
                $friendLink->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            $friendLink->save();
            return ResultService::success('更新友情链接成功');
        }
        return ResultService::failure('友情链接不存在');
    }

    /**获取友情链接
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $friendLink=FriendLinkModel::where('id','=',$id)->find();
            if($friendLink){
                return ResultService::makeResult(ResultService::Success,'',$friendLink->toArray());
            }
            else{
                return ResultService::failure('友情链接不存在');
            }
        }
        else{
            $friendLinks=FriendLinkModel::select();
            return ResultService::makeResult(ResultService::Success,'',$friendLinks->toArray());
        }
    }

    /**分页获取友情链接
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $pageResult=[];
        $friendLinks=FriendLinkModel::order('list_order','desc')->select();
        $pageResult['total']=count($friendLinks);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $friendLinks=FriendLinkModel::page($page,$pageSize)->order('list_order','desc')->select();
        }
        $pageResult['pageData']=$friendLinks;
        return ResultService::makeResult(ResultService::Success,'',$pageResult);
    }
}