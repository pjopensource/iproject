<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_act_gift extends mod_act_gift{
	function act_default(){
		$this->arr_group = cls_config::get("gift_group","actgift");
		$this->arr_gift = $this->get_gift_all();
		return $this->get_view();
	}

	function act_view() {
		$this->arr_top10 = $this->get_top10();
		$id = (int)fun_get::get("id");
		$this->is_verify = cls_config::get("exchange_verify","actgift");
		$this->gift_info = $this->get_giftinfo($id);
		$this->record_info = $this->get_record_info();
		$this->userscore = cls_obj::get("cls_user")->get_score();
		return $this->get_view();
	}

	function act_change() {
		$arr = fun_format::json( $this->on_exchange() );
		return $arr;
	}

	function act_mygift() {
		//�����б�
		$this->record_list = $this->get_mygift();
		return $this->get_view();
	}
	function act_receive() {
	}
}