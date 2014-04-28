<?php
class ctl_api_login extends cls_base {
	function act_default() {
		$jump_url = fun_get::get("jump_url");    //获取跳转地址
		$plat = fun_get::get("plat");
		if( empty($jump_url) && isset($_SERVER["HTTP_REFERER"]) ) $jump_url=$_SERVER["HTTP_REFERER"];
		$jump_url = cls_config::get("url" , 'base')."/common.php?app=api.login&app_act=token&plat=" . $plat . "&url=".urlencode($jump_url);
		$arr = cls_obj::get('cls_com')->userapi("login" , array("jump_url"=>$jump_url , "plat" => $plat));
	}
	function act_token() {
		$jump_url = fun_get::get("url");
		$plat = fun_get::get("plat");
		if(empty($jump_url)) $jump_url = cls_config::get("url" , 'base');
		$arr = cls_obj::get('cls_com')->userapi("login_token" , array("jump_url"=>$jump_url , "plat" => $plat));
	}
	/* 从第三方登录回来后所跳转到的已有登录账号绑定页面
	 *
	 */
	function act_bind_login() {
		$jump_url = fun_get::get("jump_url");    //获取跳转地址
		if( empty($jump_url) && isset($_SERVER["HTTP_REFERER"]) ) $jump_url=$_SERVER["HTTP_REFERER"];
		$this->jump_fromurl = $jump_url;
		$view = "bind.login";
		if(fun_is::wap()) $view .= ".wap";
		return $this->get_view($view); //显示页面
	}
	/* 从第三方登录回来后所跳转到的注册账号绑定页面
	 *
	 */
	function act_bind_reg() {
		$jump_url = fun_get::get("jump_url");    //获取跳转地址
		if( empty($jump_url) && isset($_SERVER["HTTP_REFERER"]) ) $jump_url=$_SERVER["HTTP_REFERER"];
		$this->jump_fromurl = $jump_url;
		$this->reg_switch = cls_config::get("reg_switch" , "user");
		$this->reg_switch_info = cls_config::get("reg_switch_info" , "user");
		$view = "bind.reg";
		if(fun_is::wap()) $view .= ".wap";
		return $this->get_view($view); //显示页面
	}
	//绑定当前账号
	function act_bind() {
		$openinfo = cls_obj::get("cls_session")->get("userapi");
		if(empty($openinfo) || !isset($openinfo['name']) ) {
			cls_error::on_exit('exit',array("tips"=>"第三方登录信息丢失"));
		}
		$jump_url = fun_get::get("jump_url");    //获取跳转地址
		if( empty($jump_url) ) $jump_url = cls_config::get("url" , "base");
		cls_obj::db_w()->on_exe("update " . cls_config::DB_PRE . "userapi_login set login_user_id='" . cls_obj::get("cls_user")->uid . "' where login_name='" . $openinfo['name'] . "'");
		fun_base::url_jump($jump_url);
	}
}