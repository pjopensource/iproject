<?php
class ctl_act_gift extends mod_act_gift {

	//Ĭ�����ҳ
	function act_default() {
		//��ҳ�б�
		$this->arr_list = $this->get_pagelist( );
		$this->arr_state = tab_act_gift::get_perms("state");
		$this->arr_group = cls_config::get("gift_group" , "actgift");
		return $this->get_view(); //��ʾҳ��
	}
	//�༭ ���� ҳ�� ,��idʱΪ�༭
	function act_edit() {

		$this->editinfo = $this->get_editinfo( fun_get::get('id') );
		$this->arr_state = tab_act_gift::get_perms("state");
		$this->arr_group = cls_config::get("gift_group" , "actgift");
		return $this->get_view();
	}

	//�������,����josn����
	function act_save() {
		$arr_return = $this->on_save();
		return fun_format::json($arr_return);
	}
	//����ɾ��,����josn����
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}
}
?>