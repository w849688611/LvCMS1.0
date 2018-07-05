<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/30
 * Time: 上午12:12
 */

namespace app\plugin\controller;


use app\lib\exception\TokenException;
use app\plugin\enum\PluginStatusEnum;
use app\plugin\model\PluginModel;
use app\plugin\validate\PluginInfoValidate;
use app\plugin\validate\PluginStatusValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class PluginBase extends Controller
{
    /**获取插件列表
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $plugins=PluginModel::getAllPlugin();
        return ResultService::success('',$plugins);
    }

    /**安装插件
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function install(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new PluginInfoValidate())->goCheck();
        $name=$request->param('name');
        $author=$request->param('author');
        if(PluginModel::isPluginInstall($name,$author)){
            return ResultService::failure('插件已安装无需重复安装');
        }
        $pluginClass=PluginModel::getPlugin($name,$author);
        $result=$pluginClass::install();
        if($result){
            $plugin=new PluginModel($request->param());
            $plugin->version=(new $pluginClass())->getConfig()['version'];
            $plugin->allowField(true)->save();
            return ResultService::success('插件安装成功');
        }
        return ResultService::failure('插件未安装成功:'.$result);
    }

    /**卸载插件
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function uninstall(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new PluginInfoValidate())->goCheck();
        $name=$request->param('name');
        $author=$request->param('author');
        if(!PluginModel::isPluginInstall($name,$author)){
            return ResultService::failure('插件未安装,无需卸载');
        }
        $pluginClass=PluginModel::getPlugin($name,$author);
        $result=$pluginClass::uninstall();
        if($result){
            PluginModel::where('name','=',$name)
                ->where('author','=',$author)
                ->delete();
            return ResultService::success('卸载插件成功');
        }
        return ResultService::failure('插件未卸载成功:'.$result);
    }

    /**更新插件
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new PluginInfoValidate())->goCheck();
        $name=$request->param('name');
        $author=$request->param('author');
        if(!PluginModel::isPluginInstall($name,$author)){
            return ResultService::failure('插件未安装,无法更新');
        }
        $pluginClass=PluginModel::getPlugin($name,$author);
        $config=(new $pluginClass())->getConfig();
        $newVersion=array_key_exists('version',$config)?$config['version']:0;
        $oldVersion=PluginModel::where('name','=',$name)
            ->where('author','=',$author)
            ->find()->version;
        if(floatval($newVersion)>floatval($oldVersion)){
            $result=$pluginClass::update();
            if($result){
                $plugin=PluginModel::where('name','=',$name)
                    ->where('author','=',$author)->find();
                $plugin->version=$newVersion;
                $plugin->save();
                return ResultService::success('更新插件成功');
            }
           return ResultService::failure('更新插件未成功');
        }
        else{
            return ResultService::failure('当前版本较高，无需更新');
        }
    }

    /**切换插件开关状态
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function toggle(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new PluginInfoValidate())->goCheck();
        (new PluginStatusValidate())->goCheck();
        $name=$request->param('name');
        $author=$request->param('author');
        $status=$request->param('status');
        if(!PluginModel::isPluginInstall($name,$author)){
            return ResultService::failure('插件未安装,无法使用开关');
        }
        $plugin=PluginModel::where('name','=',$name)
            ->where('author','=',$author)
            ->find();
        $plugin->status=$status;
        $plugin->save();
        return ResultService::success('切换插件状态成功');
    }
}