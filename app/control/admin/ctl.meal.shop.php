<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_meal_shop extends mod_meal_shop {

	//默认浏览页
	function act_default() {
		//分页列表
		$this->arr_list = $this->get_pagelist();
		$this->area = fun_kj::get_area();
		$this->arr_state = tab_meal_shop::get_perms("state");
		$this->s_shop_mode = fun_get::get("s_shop_mode",array());

		return $this->get_view(); //显示页面
	}
	//编辑 新增 页面 ,有id时为编辑
	function act_edit() {

		$this->editinfo = $this->get_editinfo( fun_get::get('id') );
		$this->arr_state = tab_meal_shop::get_perms("state");
		$this->arr_sms = tab_meal_shop::get_perms("sms");
		return $this->get_view();
	}

	//保存操作,返回josn数据
	function act_save() {
		$arr_return = $this->on_save();
		return fun_format::json($arr_return);
	}
	//彻底删除,返回josn数据
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}
	//设置状态
	function act_state() {
		$arr_return = $this->on_state();
		return fun_format::json($arr_return);
	}
	//设置经营模式
	function act_mode() {
		$arr_return = $this->on_mode();
		return fun_format::json($arr_return);
	}

}