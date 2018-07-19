<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/30
 * Time: 下午4:30
 */

namespace app\plugin\model;


use app\plugin\enum\PluginStatusEnum;
use app\plugin\exception\PluginConfigException;
use app\plugin\exception\PluginFileNotFoundException;
use think\Model;

class PluginModel extends Model
{
    protected $name='plugin';

    /**检查插件是否安装
     * @param $name
     * @param $author
     * @return bool
     */
    public static function isPluginInstall($name,$author){
        $plugin=self::where('name','=',$name)
            ->where('author','=',$author)
            ->find();
        return $plugin?true:false;
    }

    /**插件是否开启
     * @param $name
     * @param $author
     * @return bool
     */
    public static function isPluginOpen($name,$author){
        $plugin=self::where('name','=',$name)
            ->where('author','=',$author)
            ->find();
        if($plugin&&$plugin->status==PluginStatusEnum::OPEN){
            return true;
        }
        return false;
    }

    /**查找获取插件类名
     * @param $name
     * @param $author
     * @return string
     * @throws PluginConfigException
     * @throws PluginFileNotFoundException
     */
    public static function getPlugin($name,$author){
        foreach (glob(PLUGIN_PATH.'*/*.php') as $pluginFile){
            //获取插件文件信息
            $info=pathinfo($pluginFile);
            //读到的是配置文件则直接下一循环
            if(strtolower($info['filename'])=='config'){
                continue;
            }
            //获取目录信息
            $dirName=pathinfo($info['dirname'],PATHINFO_FILENAME);
            if(strtolower($info['filename'])==strtolower($dirName)){
                //获取配置信息
                $config=[];
                if(is_file(PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR."config.php")){
                    $config=include PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR."config.php";
                }
                if(array_key_exists('name',$config)&&array_key_exists('author',$config)){
                    if($config['name']==$name&&$config['author']==$author){
                        return PLUGIN_NAMESPACE.$dirName."\\".$info['filename'];
                    }
                }
            }
        }
        throw new PluginFileNotFoundException();
    }

    /**获取帮助文档内容
     * @param $name
     * @param $author
     * @return bool|string
     * @throws PluginFileNotFoundException
     */
    public static function getHelp($name,$author){
        foreach (glob(PLUGIN_PATH.'*') as $pluginDir){
            if(is_dir($pluginDir)){
                $info=pathinfo($pluginDir);
                if(strtolower($info['basename'])==strtolower($name)){
                    $content='';
                    if(is_file($pluginDir.DIRECTORY_SEPARATOR.PLUGIN_HELP_PATH.DIRECTORY_SEPARATOR.'help.html')){
                        $content=file_get_contents($pluginDir.DIRECTORY_SEPARATOR.PLUGIN_HELP_PATH.DIRECTORY_SEPARATOR.'help.html');
                    }
                    return $content;
                }
            }
        }
        throw new PluginFileNotFoundException();
    }

    /**获取所有插件
     * @return array
     */
    public static function getAllPlugin(){
        $plugins=[];
        foreach (glob(PLUGIN_PATH.'*/*.php') as $pluginFile){
            $pluginInfo=[];
            //获取插件文件信息
            $info=pathinfo($pluginFile);
            //读到的是配置文件则直接下一循环
            if(strtolower($info['filename'])=='config'){
                continue;
            }
            //获取目录信息
            $dirName=pathinfo($info['dirname'],PATHINFO_FILENAME);
            if(is_file(PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR."config.php")){
                $config=include PLUGIN_PATH.$dirName.DIRECTORY_SEPARATOR."config.php";
                $pluginInfo['name']=array_key_exists('name',$config)?$config['name']:$info['filename'];
                $pluginInfo['author']=array_key_exists('author',$config)?$config['author']:'';
                $pluginInfo['description']=array_key_exists('description',$config)?$config['description']:'';
            }
            else{
                $pluginInfo['name']=$pluginInfo['filename'];
                $pluginInfo['author']='';
            }
            if(self::isPluginInstall($pluginInfo['name'],$pluginInfo['author'])){
                $pluginInfo['status']=self::isPluginOpen($pluginInfo['name'],$pluginInfo['author'])?PluginStatusEnum::OPEN:PluginStatusEnum::CLOSE;
            }
            else{
                $pluginInfo['status']=PluginStatusEnum::UNINSTALL;
            }
            $plugins[]=$pluginInfo;
        }
        return $plugins;
    }
}