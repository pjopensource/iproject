<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_report_order extends mod_report_order {

	//默认浏览页
	function act_default() {
		//订单量统计
		$this->report = $this->order_num();
		$this->mode = fun_get::get("mode");
		return $this->get_view(); //显示页面
	}
}