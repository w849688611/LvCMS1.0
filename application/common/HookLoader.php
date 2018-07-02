<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/7
 * Time: 22:48
 */

namespace app\common;


use think\Facade\Hook;
use think\Facade\Env;

class HookLoader
{
    public function run(){
        $this->init();
    }
    public function init(){
        $fileList=opendir(Env::get('app_path'));
        while($file=readdir($fileList)){
            if(is_dir(Env::get('app_path').$file)){
                if(is_dir(Env::get('app_path').$file.'/hook')){
                    $this->searchHookFile($file);
                }
            }
        }
    }
    public function searchHookFile($moduleDir){
        $hookFileList=opendir(Env::get('app_path').$moduleDir.'/hook/');
        while($file=readdir($hookFileList)){
            if(substr($file,-4)=='.php'){
                $namespace='app\\'.$moduleDir.'\\hook\\';
                $this->compileHookFile($namespace,$file);
            }
        }
    }
    public function compileHookFile($namespace,$hookFile){
        $hookFile=substr($hookFile,0,strrpos($hookFile,'.php'));
        $class=$namespace.basename($hookFile);
        $hookObj=new $class();
        foreach($hookObj->config as $item){
            Hook::add($item,$class);
        }
    }
}