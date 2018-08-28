<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/8/26
 * Time: 上午11:53
 */

namespace app\plugin\plugins\book\validate;


use app\lib\validate\BaseValidate;

class KindleMailValidate extends BaseValidate
{
    protected $rule=[
        'kindleMail'=>'require|email',
    ];
}