<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/30
 * Time: 上午9:49
 */

namespace app\plugin;


use app\plugin\model\PluginModel;
use think\facade\Env;
use think\facade\Hook;
use think\Loader;

/**
 * 插件加载器主要工作有
 * 1.扫描指定目录下的插件
 * 2.扫描时候对照数据库，查看插件是否已经安装
 * 3.对已经安装的插件，将对应的方法挂载进对应的钩子
 * Class PluginLoader
 * @package app\plugin
 */
class PluginLoader
{
    public function run(){
        $this->init();
    }
    public function init(){
        define('PLUGIN_PATH',Env::get('app_path').'plugin'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR);
        //define('PLUGIN_PATH',Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR);
        define('PLUGIN_NAMESPACE',"\\app\\plugin\\plugins\\");
        //define('PLUGIN_NAMESPACE',"\\plugins\\");
        define('PLUGIN_HELP_PATH','help');//帮助文档的文件夹名称
        foreach (glob(PLUGIN_PATH.'*/*.php') as $pluginFile){
            //获取插件文件信息
            $info=pathinfo($pluginFile);
            //获取目录信息
            $dirName=pathinfo($info['dirname'],PATHINFO_FILENAME);
            //获取配置信息
            $config=[];
            if(is_file(PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR."config.php")){
                $config=include PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR."config.php";
            }
            $base=get_class_methods(Plugin::class);//基类所包含的方法
            //配置文件中有名字与作者才为合法插件
            if(array_key_exists('name',$config)&&array_key_exists('author',$config)){
                //名称与目录相同的文件为插件主文件
                if(strtolower($info['filename'])==strtolower($dirName)){
                    //检查插件是否安装并且开启，若未安装或未开启则跳过加载
                    if(PluginModel::isPluginOpen($config['name'],$config['author'])){
                        $pluginClass=PLUGIN_NAMESPACE.$dirName."\\".$info['filename'];
                        //插件类不存在则直接跳过
                        if(!class_exists($pluginClass)){
                            continue;
                        }
                        $plugin=new $pluginClass();
                        //插件有手动配置钩子列表
                        if($plugin->hookList!=null&&is_array($plugin->hookList)&&count($plugin->hookList)>0){
                            $hookList=$plugin->hookList;
                        }
                        //插件未手动配置则自动检测
                        else{
                            $pluginFunc=get_class_methods(PLUGIN_NAMESPACE.$dirName."\\".$info['filename']);
                            $hookList=array_map(function($item){return Loader::parseName($item,0);},array_diff($pluginFunc,$base));
                        }
                        foreach ($hookList as $hookName){
                            Hook::add($hookName,$pluginClass);
                        }
                        //加载插件路由
                        if(is_file(PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR.'route'.DIRECTORY_SEPARATOR.'route.php')){
                            include PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR.'route'.DIRECTORY_SEPARATOR.'route.php';
                        }
                    }
                }
            }
        }
    }
}