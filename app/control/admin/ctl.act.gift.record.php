<?php
/* KLKKDJè®¢é¤ä¹‹å¤šåº—ç‰ˆ
 * ç‰ˆæœ¬å·ï¼š3.3
 * å®˜ç½‘ï¼šhttp://www.klkkdj.com
 * 2013-05-06
 */
class ctl_act_gift_record extends mod_act_gift_record {

	//Ä¬ÈÏä¯ÀÀÒ³
	function act_default() {
		//·ÖÒ³ÁĞ±í
		$this->arr_list = $this->get_pagelist( );
		return $this->get_view(); //ÏÔÊ¾Ò³Ãæ
	}
	//·¢»õ
	function act_send() {
		$arr_return = $this->on_send();
		return fun_format::json($arr_return);
	}

}