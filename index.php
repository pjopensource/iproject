<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
require "inc.php";
if(!file_exists(KJ_DIR_DATA."/install.inc")){
	fun_base::url_jump("install.php");
	exit;
}
$agent = fun_get::agent();
if(in_array($agent,array('','ipad'))) {
	$mod_dir = cls_obj::get("cls_session")->get_env('mod_dir');
	$view_dir = cls_obj::get("cls_session")->get_env('view_dir');
	cls_app::on_load($mod_dir , $view_dir);
} else {
	//目前手机支持的浏览器
	if(in_array($agent , array("qq","iphone","android"))) {
		cls_app::on_load("default" , "wap");
	} else {
		cls_app::on_load("default" , "wap" , array("app"=>'noagent' , "app_module"=>"" , "app_act"=>""));
	}
}