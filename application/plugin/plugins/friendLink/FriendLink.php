<?php

/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/18
 * Time: 下午3:19
 */
namespace app\plugin\plugins\friendLink;

use app\plugin\Plugin;
use think\Db;
use think\facade\Config;

class FriendLink extends Plugin
{
    public static function install()
    {
        $tableName=Config::get('database.prefix').'friend_link';
        $result=Db::execute("create table if not exists  ".$tableName."(
id int  auto_increment not null,
url text,
name text,
more text,
status tinyint default 1,
list_order int default 0,
primary key(id)
)");
        if($result||$result==0){
            return true;
        }
        return false;
    }
    public static function uninstall()
    {
        $tableName=Config::get('database.prefix').'friend_link';
        $result=Db::execute("drop table if exists ".$tableName);
        if($result||$result==0){
            return true;
        }
        return false;
    }
}