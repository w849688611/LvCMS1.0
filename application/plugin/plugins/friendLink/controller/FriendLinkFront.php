<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/19
 * Time: 下午4:22
 */

namespace app\plugin\plugins\friendLink\controller;


use app\plugin\plugins\friendLink\model\FriendLinkModel;
use app\service\ResultService;
use think\Controller;
use think\Request;

class FriendLinkFront extends Controller
{
    public function getFriendLink(Request $request){
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