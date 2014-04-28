<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_js extends inc_mod_default {
	function act_shop_hit() {
		$shop_id = (int)fun_get::get("shop_id");
		cls_obj::db_w()->on_exe("update " . cls_config::DB_PRE . "meal_shop set shop_hits=shop_hits+1 where shop_id='" . $shop_id . "'");
		return "";
	}
}