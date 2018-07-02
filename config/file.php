<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午1:52
 */
return [
    'uploadPath'=>\think\facade\Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'upload',
    'ext'=>'jpg,jpeg,png,gif,bmp,doc,docx,xls,xlsx,ppt,pptx,zip,rar,mp4,flv',
    'showPath'=>DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR,
    'basePath'=>\think\facade\Env::get('root_path').'public'
];