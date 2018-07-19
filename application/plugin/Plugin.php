<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/30
 * Time: 上午9:45
 */

namespace app\plugin;


class Plugin
{
//    $config=[
//        'name'=>'test',
//        'status'=>'1',
//        'other'=>[
//            'type'=>'group',
//            'options'=>[
//                'a'=>[
//                    'options'=>[
//                        'c'=>['value'=>'a']
//                    ]
//                ],
//                'b'=>[
//                    'options'=>[
//                        'd'=>['value'=>'b']
//                    ]
//                ]
//            ]
//        ]
//    ];
    public $hookList;
    public $configFile;
    public $name;
    public $pluginPath;
    function __construct()
    {
        $this->name=$this->getName();
        $this->pluginPath=PLUGIN_PATH.$this->name.DIRECTORY_SEPARATOR;
        if(is_file($this->pluginPath.'config.php')){
            $this->configFile=$this->pluginPath.'config.php';
        }
        if(method_exists($this,'initialize')){
            $this->initialize();
        }
    }
    public function getConfig(){
        $config=[];
        if(is_file($this->configFile)){
            $tempArr=include $this->configFile;
            foreach ($tempArr as $key=>$value){
                if(is_array($value)&&array_key_exists('type',$value)&&$value['type']=='group'){
                    foreach ($value['options'] as $ikey=>$ivalue){
                        foreach ($ivalue['options'] as $jkey=>$jvalue){
                            $config[$jkey]=$jvalue['value'];
                        }
                    }
                }
                else{
                    $config[$key]=$value;
                }
            }
        }
        else{
            $config['name']=$this->name;
            $config['status']=-1;//却少配置文件
            $config['version']='0.0';
        }
        return $config;
    }
    public function getName(){
        $class=get_class($this);
        $class=explode('\\',$class);
        return strtolower(array_pop($class));
    }
    public static function install(){
        return true;
    }
    public static function uninstall(){
        return true;
    }
    public static function update(){
        return true;
    }
}