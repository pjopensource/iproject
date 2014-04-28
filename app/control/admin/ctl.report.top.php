<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_report_top extends mod_report_top {

	//默认浏览页
	function act_default() {
		$this->mode = fun_get::get("mode");
		//店铺销售排行榜
		$this->arr_shop_top = $this->get_shop_top();
		//菜品销售排行榜
		//用户消费排行榜
		$this->arr_user_top = $this->get_user_top();
		//订单转化率
		return $this->get_view(); //显示页面
	}
}