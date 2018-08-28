<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;
use think\facade\Hook;

//后台相关接口
/**
 * 管理员相关接口
 */
Route::rule('api/admin/add','admin/AdminBase/add');
Route::rule('api/admin/delete','admin/AdminBase/delete');
Route::rule('api/admin/update','admin/AdminBase/update');
Route::rule('api/admin/get','admin/AdminBase/get');
Route::rule('api/admin/getByPage','admin/AdminBase/getByPage');
Route::rule('api/admin/login','admin/AdminBase/login');
Route::rule('api/admin/logout','admin/AdminBase/logout');
Route::rule('api/admin/checkLogin','admin/AdminBase/checkLogin');
/**
 * 管理员角色相关接口
 */
Route::rule('api/role/add','admin/RoleBase/add');
Route::rule('api/role/delete','admin/RoleBase/delete');
Route::rule('api/role/update','admin/RoleBase/update');
Route::rule('api/role/get','admin/RoleBase/get');
Route::rule('api/role/getByPage','admin/RoleBase/getByPage');
Route::rule('api/role/bindAuth','admin/RoleBase/bindAuth');
Route::rule('api/role/checkRoleOwnAuth','admin/RoleBase/checkRoleOwnAuth');
/**
 * 管理员权限相关接口
 */
Route::rule('api/auth/add','admin/AuthBase/add');
Route::rule('api/auth/delete','admin/AuthBase/delete');
Route::rule('api/auth/update','admin/AuthBase/update');
Route::rule('api/auth/get','admin/AuthBase/get');
Route::rule('api/auth/getByPage','admin/AuthBase/getByPage');
Route::rule('api/auth/getTree','admin/AuthBase/getTree');
Route::rule('api/auth/getParent','admin/AuthBase/getParent');
Route::rule('api/auth/getChildren','admin/AuthBase/getChildren');
Route::rule('api/auth/getTreeOfRole','admin/AuthBase/getTreeOfRole');
/**
 * 栏目相关接口
 */
Route::rule('api/category/add','portal/CategoryBase/add');
Route::rule('api/category/delete','portal/CategoryBase/delete');
Route::rule('api/category/update','portal/CategoryBase/update');
Route::rule('api/category/get','portal/CategoryBase/get');
Route::rule('api/category/getByPage','portal/CategoryBase/getByPage');
Route::rule('api/category/getTree','portal/CategoryBase/getTree');
Route::rule('api/category/getPostOfCategory','portal/CategoryBase/getPostOfCategory');
/**
 * 内容相关接口
 */
Route::rule('api/post/add','portal/PostBase/add');
Route::rule('api/post/delete','portal/PostBase/delete');
Route::rule('api/post/update','portal/PostBase/update');
Route::rule('api/post/get','portal/PostBase/get');
Route::rule('api/post/getByPage','portal/PostBase/getByPage');
Route::rule('api/post/search','portal/PostBase/search');
Route::rule('api/post/getCommentOfPost','portal/PostBase/getCommentOfPost');
/**
 * 管理评论相关接口
 */
Route::rule('api/comment/get','portal/CommentBase/get');
Route::rule('api/comment/update','portal/CommentBase/update');
Route::rule('api/comment/delete','portal/CommentBase/delete');
Route::rule('api/comment/search','portal/CommentBase/search');
/**
 * 单页相关接口
 */
Route::rule('api/single/add','portal/SingleBase/add');
Route::rule('api/single/delete','portal/SingleBase/delete');
Route::rule('api/single/update','portal/SingleBase/update');
Route::rule('api/single/get','portal/SingleBase/get');
Route::rule('api/single/getByPage','portal/SingleBase/getByPage');
/**
 * 模版相关接口
 */
Route::rule('api/template/add','portal/TemplateBase/add');
Route::rule('api/template/delete','portal/TemplateBase/delete');
Route::rule('api/template/update','portal/TemplateBase/update');
Route::rule('api/template/get','portal/TemplateBase/get');
Route::rule('api/template/getCategoryTemplate','portal/TemplateBase/getCategoryTemplate');
Route::rule('api/template/getSingleTemplate','portal/TemplateBase/getSingleTemplate');
Route::rule('api/template/getPostTemplate','portal/TemplateBase/getPostTemplate');
/**
 * 导航相关接口
 */
Route::rule('api/nav/add','portal/NavBase/add');
Route::rule('api/nav/delete','portal/NavBase/delete');
Route::rule('api/nav/update','portal/NavBase/update');
Route::rule('api/nav/get','portal/NavBase/get');
Route::rule('api/nav/getByPage','portal/NavBase/getByPage');
Route::rule('api/nav/getItemOfNav','portal/NavBase/getItemOfNav');
Route::rule('api/nav/item/add','portal/NavItemBase/add');
Route::rule('api/nav/item/delete','portal/NavItemBase/delete');
Route::rule('api/nav/item/update','portal/NavItemBase/update');
Route::rule('api/nav/item/get','portal/NavItemBase/get');
/**
 * 幻灯片相关接口
 */
Route::rule('api/slide/add','portal/SlideBase/add');
Route::rule('api/slide/delete','portal/SlideBase/delete');
Route::rule('api/slide/update','portal/SlideBase/update');
Route::rule('api/slide/get','portal/SlideBase/get');
Route::rule('api/slide/getByPage','portal/SlideBase/getByPage');
Route::rule('api/slide/getItemOfSlide','portal/SlideBase/getItemOfSlide');
Route::rule('api/slide/item/add','portal/SlideItemBase/add');
Route::rule('api/slide/item/delete','portal/SlideItemBase/delete');
Route::rule('api/slide/item/update','portal/SlideItemBase/update');
Route::rule('api/slide/item/get','portal/SlideItemBase/get');

/**
 * 管理用户相关接口
 */
Route::rule('api/user/add','user/UserBase/add');
Route::rule('api/user/delete','user/UserBase/delete');
Route::rule('api/user/update','user/UserBase/update');
Route::rule('api/user/get','user/UserBase/get');
Route::rule('api/user/getByPage','user/UserBase/getByPage');
/**
 * 用户组相关接口
 */
Route::rule('api/userGroup/add','user/UserGroupBase/add');
Route::rule('api/userGroup/delete','user/UserGroupBase/delete');
Route::rule('api/userGroup/update','user/UserGroupBase/update');
Route::rule('api/userGroup/get','user/UserGroupBase/get');
Route::rule('api/userGroup/getByPage','user/UserGroupBase/getByPage');
Route::rule('api/userGroup/getUserOfGroup','user/UserGroupBase/getUserOfGroup');
/**
 * 文件相关接口
 */
Route::rule('api/file/add','file/FileBase/add');
Route::rule('api/file/delete','file/FileBase/delete');
Route::rule('api/file/get','file/FileBase/get');
Route::rule('api/file/getByPage','file/FileBase/getByPage');
Route::rule('api/file/getByMd5','file/FileBase/getByMd5');
Route::rule('api/file/getBySha1','file/FileBase/getBySha1');
/**
 * 工具相关接口
 */
Route::rule('api/util/captcha/get','util/CaptchaBase/get');
Route::rule('api/util/captcha/check','util/CaptchaBase/check');
/**
 * 插件相关接口
 */
Route::rule('api/plugin/get','plugin/PluginBase/get');
Route::rule('api/plugin/install','plugin/PluginBase/install');
Route::rule('api/plugin/uninstall','plugin/PluginBase/uninstall');
Route::rule('api/plugin/update','plugin/PluginBase/update');
Route::rule('api/plugin/toggle','plugin/PluginBase/toggle');
Route::rule('api/plugin/help','plugin/PluginBase/help');
/**
 * 全局配置管理相关接口
 */
Route::rule('api/setting/add','setting/SettingBase/add');
Route::rule('api/setting/delete','setting/SettingBase/delete');
Route::rule('api/setting/update','setting/SettingBase/update');
Route::rule('api/setting/get','setting/SettingBase/get');
Route::rule('api/setting/getByPage','setting/SettingBase/getByPage');
Route::rule('api/setting/getItemOfSetting','setting/SettingBase/getItemOfSetting');
Route::rule('api/setting/item/add','setting/SettingItemBase/add');
Route::rule('api/setting/item/delete','setting/SettingItemBase/delete');
Route::rule('api/setting/item/update','setting/SettingItemBase/update');
Route::rule('api/setting/item/get','setting/SettingItemBase/get');
Route::rule('api/setting/item/getByPage','setting/SettingItemBase/getByPage');
/**
 * 邮箱服务接口
 */
Route::rule('api/mail/add','mail/MailBase/add');
Route::rule('api/mail/delete','mail/MailBase/delete');
Route::rule('api/mail/update','mail/MailBase/update');
Route::rule('api/mail/get','mail/MailBase/get');
Route::rule('api/mail/getByPage','mail/MailBase/getByPage');
Route::rule('api/mail/send','mail/MailBase/sendMail');
/**
 * 其他接口
 */
Route::rule('api/dashboard/getBackDashBoard','dashboard/DashBoardBase/getBackDashBoard');
//前台相关接口
/**
 * 前台用户接口
 */
Route::rule('api/front/user/login','user/UserFront/login');
Route::rule('api/front/user/logout','user/UserFront/logout');
Route::rule('api/front/user/checkLogin','user/UserFront/checkLogin');
Route::rule('api/front/user/register','user/UserFront/register');
Route::rule('api/front/user/update','user/UserFront/update');
Route::rule('api/front/user/get','user/UserFront/get');
/**
 * 前端内容相关接口
 */
Route::rule('api/front/portal/getCategory','portal/PortalFront/getCategory');
Route::rule('api/front/portal/getPostOfCategory','portal/PortalFront/getPostOfCategory');
Route::rule('api/front/portal/getPost','portal/PortalFront/getPost');
Route::rule('api/front/portal/searchPost','portal/PortalFront/searchPost');
Route::rule('api/front/portal/getSingle','portal/PortalFront/getSingle');
Route::rule('api/front/portal/getCommentOfPost','portal/PortalFront/getCommentOfPost');
Route::rule('api/front/portal/addComment','portal/PortalFront/addComment');
Route::rule('api/front/portal/deleteComment','portal/PortalFront/deleteComment');
Route::rule('api/front/portal/getNav','portal/PortalFront/getNav');
Route::rule('api/front/portal/getSlide','portal/PortalFront/getSlide');
/**
 * setting
 */
Route::rule('api/front/setting/get','setting/SettingFront/get');

Hook::listen('plugin_route');

return [


];
