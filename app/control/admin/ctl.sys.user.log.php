<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_sys_user_log extends mod_sys_user_log {

	//默认浏览页
	function act_default() {
		$this->arr_list = $this->get_list();
		return $this->get_view(); //显示页面
	}

	//清除日志
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}
}