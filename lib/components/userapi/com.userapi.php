<?php
class com_userapi {
	static $objapi = array();
	static function get_cfg($plat) {
		$arr_cfg = array(
			"qq" => array('key'=>cls_config::get("qq_key","userapi") , 'secret' => cls_config::get("qq_secret","userapi")),
			"weibo" => array('key'=>cls_config::get("weibo_key","userapi") , 'secret' => cls_config::get("weibo_secret","userapi"))
		);
		return isset($arr_cfg[$plat]) ? $arr_cfg[$plat] : array();
	}
	static function obj($plat) {
		if(!isset(self::$objapi[$plat])) {
			require dirname(__FILE__) . "/" . $plat . '/com.userapi.' . $plat . '.php';
			$objname = 'com_userapi_' . $plat;
			self::$objapi[$plat] = new $objname;
		}
		return self::$objapi[$plat];
	}
	function login($arr) {
		$jump_url = $arr['jump_url'];
		$plat = $arr['plat'];
		$arr_cfg = self::get_cfg($plat);
		self::obj($plat)->login($arr_cfg , $jump_url);
	}
	function login_token($arr) {
		$jump_url = $arr['jump_url'];
		$plat = $arr['plat'];
		$arr_cfg = self::get_cfg($plat);
		$arr = self::obj($plat)->login_token($arr_cfg , $jump_url);
		if($arr['code'] != 0) {
			cls_error::on_exit('exit',"授权失败，<a href='" . cls_config::get("url" , 'base') . "/common.php?app=api.login&plat=" . $plat . "&jump_url=" . urlencode($jump_url) . "'>点击重新授权</a>");
		} else {
			//授权成功，查看是否已经登录过
			$obj_rs = cls_obj::db()->get_one("select login_user_id from " . cls_config::DB_PRE . "userapi_login where login_name='" . $arr['openinfo']["name"] . "'");
			if(empty($obj_rs) || empty($obj_rs['login_user_id']) ) {
				cls_obj::db_w()->on_insert(cls_config::DB_PRE . "userapi_login" , array("login_name"=>$arr['openinfo']["name"],"login_addtime"=>TIME,"login_plat"=>$plat));
				$url = cls_config::get("url","base") . "/common.php?app=api.login&app_act=bind.login&jump_url=" . urlencode($jump_url);
				fun_base::url_jump($url);
				exit;
			}
			$arr_return = cls_obj::get("cls_user")->on_login(array('user_id'=>$obj_rs['login_user_id']) , 1);
			if($arr_return['code']!=0) {
				cls_error::on_exit('exit',array("tips"=>"登录时发生错误","debug"=>$arr_return['msg']));
			}
			fun_base::url_jump($jump_url);
		}
	}
}