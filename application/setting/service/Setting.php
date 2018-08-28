<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/24
 * Time: 上午10:39
 */

namespace app\setting\service;


use app\setting\enum\SettingItemStatusEnum;
use app\setting\enum\SettingItemTypeEnum;
use app\setting\model\SettingItemModel;
use app\setting\model\SettingModel;
use think\facade\Env;

class Setting
{
    public static function get($groupName,$itemName,$onlyContent=false){
        if($groupName&&$groupName!=''){
            //有组名则按组关联查找
            $query=new SettingModel();
            $query=$query->where('name','=',$groupName);
            if($itemName&&$itemName!=''){
                $query=$query->with(['items'=>function($query)use($itemName){
                    $query->where('name','=',$itemName)->where('status','=',SettingItemStatusEnum::OPEN);
                }]);
                $result=$query->find();
                $result=$result?$result->toArray():[];
                if($result &&is_array($result['items']) && count($result['items'])>0 &&$onlyContent){
                    $content=$result['items'][0]['content'];
                    if($result['items'][0]['type']==SettingItemTypeEnum::IMAGE||$result['items'][0]['type']==SettingItemTypeEnum::FILE){
                        $path=Env::get('root_path').DIRECTORY_SEPARATOR.'public'.$content;
                        if(is_file($path)){
                            $content=file_get_contents($path);
                            if($result['items'][0]['type']==SettingItemTypeEnum::IMAGE){
                                $ext=pathinfo($path,PATHINFO_EXTENSION);
                                $content=response($content, 200, ['Content-Length' => strlen($content)])->contentType('image/'.$ext);
                            }
                        }
                    }
                    return $content;
                }
                else{
                    $result=json($result);
                }
            }
            else{
                $query=$query->with(['items'=>function($query){
                    $query->where('status','=',SettingItemStatusEnum::OPEN);
                }]);
                $result=$query->find();
                $result=json($result);
            }
            return $result;
        }
        else{
            //无组名则按项名直接查找
            $query=new SettingItemModel();
            if($itemName&&$itemName!=''){
                $query=$query->where('name','=',$itemName);
            }
            $query=$query->where('status','=',SettingItemStatusEnum::OPEN);
            $result=$query->select()->toArray();
            return json($result);
        }
    }
}