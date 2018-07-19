<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/7/19
 * Time: 下午12:21
 */

/**
 * 后端接口
 */
\think\facade\Route::rule('api/plugin/friendLink/add','app\plugin\plugins\friendLink\controller\FriendLinkBase@add');
\think\facade\Route::rule('api/plugin/friendLink/delete','app\plugin\plugins\friendLink\controller\FriendLinkBase@delete');
\think\facade\Route::rule('api/plugin/friendLink/update','app\plugin\plugins\friendLink\controller\FriendLinkBase@update');
\think\facade\Route::rule('api/plugin/friendLink/get','app\plugin\plugins\friendLink\controller\FriendLinkBase@get');
\think\facade\Route::rule('api/plugin/friendLink/getByPage','app\plugin\plugins\friendLink\controller\FriendLinkBase@getByPage');

/**
 * 前端接口
 */
\think\facade\Route::rule('api/front/plugin/getFriendLink','app\plugin\plugins\friendLink\controller\FriendLinkFront@getFriendLink');