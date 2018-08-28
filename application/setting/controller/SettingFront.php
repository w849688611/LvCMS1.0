<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/22
 * Time: 上午8:42
 */

namespace app\setting\controller;


use app\base\controller\BaseController;
use app\setting\service\Setting;
use think\Request;

class SettingFront extends BaseController
{
    /**前端获取配置项
     * @param Request $request
     * @return array|\think\response\Json
     */
    public function get(Request $request){
        $groupName=$request->has('groupName')?$request->param('groupName'):'';
        $itemName=$request->has('itemName')?$request->param('itemName'):'';
        $onlyContent=$request->has('onlyContent')?$request->param('onlyContent'):'';
        return Setting::get($groupName,$itemName,$onlyContent);
    }
}