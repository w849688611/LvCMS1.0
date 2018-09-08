<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/30
 * Time: 下午10:57
 */
use think\Facade\Route;
Route::rule('api/front/wechat/wxLogin','app\plugin\plugins\weChat\controller\WeChatFront@wxLogin');