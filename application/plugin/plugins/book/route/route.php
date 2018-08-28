<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/26
 * Time: 下午3:46
 */
use think\Facade\Route;
Route::rule('api/front/book/sendBook','app\plugin\plugins\book\controller\BookFront@sendBook');
Route::rule('api/front/book/bindMail','app\plugin\plugins\book\controller\BookFront@bindMail');
Route::rule('api/front/book/getMailInfo','app\plugin\plugins\book\controller\BookFront@getMailInfo');