<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_act_gift_record extends mod_act_gift_record {

	//Ĭ�����ҳ
	function act_default() {
		//��ҳ�б�
		$this->arr_list = $this->get_pagelist( );
		return $this->get_view(); //��ʾҳ��
	}
	//����
	function act_send() {
		$arr_return = $this->on_send();
		return fun_format::json($arr_return);
	}

}