<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class ctl_index extends mod_index{
	function act_default(){
		if( $this->area_id > 0 ) {
			$this->shop_list = $this->get_shop_list();
			if(!cls_obj::get("cls_user")->is_login()) {
				$this->verfifycode = cls_obj::get("cls_user")->is_verifycode();
			} else if(cls_obj::get("cls_user")->type=='shop') {
				//今日总订单
				$obj_rs = cls_obj::db()->get_one("select count(1) as 'num',sum(order_total_pay) as 'total' from " . cls_config::DB_PRE . "meal_order where order_shop_id='" . cls_obj::get("cls_user")->shop_id . "' and order_addtime>'" . strtotime(date("Y-m-d")) . "'");
				$this->today_ordernum = $obj_rs["num"];
				$this->today_ordermoney = (empty($obj_rs["total"])) ? 0 : $obj_rs["total"];
			} else {
				$this->loginuser_level = cls_obj::get("cls_user")->get_level();
				$this->loginuser_experience = cls_obj::get("cls_user")->get_experience();
			}
			$this->collect_tj = $this->get_shop_collect_tj();
			$shopid = array_merge($this->collect_tj['shopid'] , $this->shop_list['shopid']);
			$this->act_info = $this->get_shop_act_info($shopid , true);
			return $this->get_view();
		} else {
			return self::act_area();
		}
	}
	//最近光顾过的店铺
	function act_shophistory() {
		$this->shop_list = $this->get_shop_history(cls_obj::get("cls_user")->uid);
		$this->act_info = $this->get_shop_act_info($this->shop_list['shopid']);
		return $this->get_view(); //显示页面
	}
	//更多店铺列表
	function act_shopmore() {
			$page = (int)fun_get::get("page");
			$this->shop_list = $this->get_shop_list($page);
			$this->act_info = $this->get_shop_act_info($this->shop_list['shopid'] , true);
			return $this->get_view();
	}
	function act_shopbeta() {
		$id = (int)fun_get::get("id");
		$shopinfo = $this->get_shopinfo($id);
		$shopinfo['shop_dispatch_price'] = (float)$shopinfo['shop_dispatch_price'];
		$this->shopinfo = $shopinfo;
		return $this->get_view(); //显示页面
	}
	//地区列表样式一
	function act_area() {
		$default_id = $this->area_id;
		$this->arr_list = $this->get_area_1();
		$this->area_path = $this->get_path($default_id);
		return $this->get_view('area'); //显示页面
	}
	//子地区列表
	function act_area_next() {
		$pid = (int)fun_get::get("pid");
		if(empty($pid)) $pid = (int)cls_config::get("area_city_id","meal");
		$arr = $this->arr_list = $this->get_area_1($pid);
		return fun_format::json($arr);
	}
	//搜索
	function act_area_search() {
		$pid = (int)fun_get::get("pid");
		$skey = fun_get::get("skey");
		if(empty($pid)) $pid = (int)cls_config::get("area_city_id","meal");
		$arr = $this->arr_list = $this->get_area_2($pid , $skey);
		return fun_format::json($arr);
	}
	//店铺页
	function act_shop(){
		$this->shop_list = $this->get_shop_list();
		$id = (int)fun_get::get("id");
		$this->shop_id = $id;
		//首页默认分组
		$index_group = cls_config::get("index_group" , "view");
		$this->index_group = (empty($index_group)) ? "price" : $index_group;
		$this->arr_menu = $this->get_menu_list($id , $this->index_group);
		$this->shopinfo = $this->get_shopinfo($id);
		$this->cfg_opentime = tab_meal_shop::get_opentime($id);
		$act = 'shop.default';
		//活动公告
		$this->arr_activitie = $this->get_activitie($id);	
		if($this->shopinfo['shop_mode'] == 2 || $this->shopinfo['shop_mode'] == 3) $act = 'shop.optional';
		//幻灯广告
		$this->slide_ads = $this->get_slide_ads($id);
		//店铺活动
		$this->act_list = $this->get_shop_act_info($id);
		return $this->get_view($act);
	}
	//分组显示
	function act_grouplist() {
		$index_group = fun_get::get("index_group");
		if(empty($index_group)) $index_group = 'price';
		$shop_id = (int)fun_get::get('id');
		$this->index_group = $index_group;
		$this->arr_menu = $this->get_menu_list( $shop_id , $index_group);
		$this->cfg_opentime = tab_meal_shop::get_opentime($shop_id);
		$this->shopinfo = $this->get_shopinfo($shop_id);
		$act = 'default';
		if($this->shopinfo['shop_mode'] == 2 || $this->shopinfo['shop_mode'] == 3) {
			$act = 'optional';
		}
		return $this->get_view("grouplist." . $act);
	}
	//排序显示
	function act_sortlist() {
		$sortby = fun_get::get("sort");
		$sortval = fun_get::get("sortval");
		if(empty($sortby)) $sortby = 'price';
		if(empty($sortval)) {
			$sortval = 'asc';
			if($sortby != 'price') $sortval = 'desc';
		}
		$sort = "menu_" . $sortby;
		$this->sortby = $sortby;
		$this->sortval = $sortval;
		$shop_id = (int)fun_get::get('id');
		$this->arr_menu = $this->get_menu_list($shop_id , '' , $sort . " " . $sortval);
		$this->cfg_opentime = tab_meal_shop::get_opentime($shop_id);
		$act = 'default';
		$this->shopinfo = $this->get_shopinfo($shop_id);
		if($this->shopinfo['shop_mode'] == 2 || $this->shopinfo['shop_mode'] == 3) {
			$act = 'optional';
		}
		return $this->get_view("sortlist." . $act);
	}
	/* 购物车页
	 * 分单店与多店，当多店时，则先列出所有店及相关所点的菜品，然后选某店以单店的形式结算
	 */
	function act_cart(){
		$this->cart_list = $this->get_cart_list();
		$this->score_total = cls_obj::get("cls_user")->get_score();
		$this->verfifycode = cls_obj::get("cls_user")->is_verifycode();
		//积分选项
		$score_money_scale = cls_config::get("score_money_scale" , "meal");
		$arr_action = cls_config::get("meal_submit_order_ok" , 'user.action' , '' , '');
		$this->money_score = $arr_action["basescore"];
		$this->score_money = intval($this->score_total * $score_money_scale);
		$this->score_money_scale = cls_config::get("score_money_scale" , "meal");
		$score_mode = cls_config::get("score_mode" , "meal");
		$act = 'cart.default';
		$arr = explode("," , $this->cart_list["shopids"]);
		if(count($this->cart_list['menu'])>0 && !empty($arr[0])) {
			if(count($arr)== 1) {
				$this->dispatch_min_price = $this->cart_list['shop']['id_'.$arr[0]]['info']['shop_dispatch_price'];
				$this->addprice = $this->cart_list['shop']['id_'.$arr[0]]['info']['shop_addprice'];//配送费
				$this->this_info = $this->get_infolist($arr[0]);
				$this->areainfo = $this->get_area($arr[0]);
				if(empty($score_mode) || ($score_mode==2 && empty($this->cart_list['shop']['id_'.$arr[0]]['info']['shop_support_score']))) $this->score_money_scale = 0;
				$x = tab_sys_user_var::get("last.area.id" , cls_obj::get("cls_user")->uid);
				if(empty($x) && count($this->this_info['list'])>0) $x = $this->this_info['list'][0]['info_id'];
				$this->last_area_id = $x;
				$this->shop_submit = 1;//表示单店，可以结算
				//取付款方式
				$this->paymethod = cls_config::get("paymethod" , "meal");
				$this->arr_pay = cls_config::get("" , "pay" , array() , "");
				//取用户当前预付款
				$this->user_repayment = cls_obj::get("cls_user")->get_repayment();
				//取店铺营销活动
				$this->shop_act = $this->get_shop_cart_act($arr[0]);
				if($this->cart_list['shop']['id_'.$arr[0]]['info']['shop_mode'] == 2 || $this->cart_list['shop']['id_'.$arr[0]]['info']['shop_mode'] == 3) $act = 'cart.optional';
				$this->cart_beta = cls_config::get("cart_beta","view");
			} else {
				$this->shop_submit = 0;//表示多店，需要选择某店结算
				$this->shop_act = array();
				$act = "cart.shop";
			}
			$this->ticket_list =  cls_config::get("ticket_list","meal");
			return $this->get_view($act);
		} else {
			return $this->get_view("cart.null");
		}
	}

	//获取指定id收货信息
	function act_getinfo() {
		$id = (int)fun_get::get("id");
		$arr_info = cls_obj::db()->get_one("select * from " . cls_config::DB_PRE  . "meal_info where info_user_id='" . cls_obj::get("cls_user")->uid . "' and info_id='" . $id . "'");
		return fun_format::json($arr_info);
	}
	//登录页
	function act_login() {
		$jump_url = fun_get::get("jump_url");    //获取跳转地址
		if( empty($jump_url) && isset($_SERVER["HTTP_REFERER"]) ) $jump_url=$_SERVER["HTTP_REFERER"];
		$this->jump_fromurl = $jump_url;
		$this->verfifycode = cls_obj::get("cls_user")->is_verifycode();
		return $this->get_view(); //显示页面
	}
	//找回密码
	function act_findpwd() {
		return $this->get_view(); //显示页面
	}
	//邮件回调找回密码
	function act_findpwd_email() {
		$key = fun_get::get("key");
		//是否为邮件认证
		$arr = array("code"=>500,'msg' => '传递参数有误' ,'uid' =>0);
		if(!empty($key)) {
			$arr = tab_sys_verify::on_verify($key , 0 , 1);
			if($arr['code'] == 0) {
				$isverify = cls_obj::get("cls_session")->set('sms_verify' , $arr['uid']);//设置已验证标识
			}
		}
		$this->info = $arr;
		return $this->get_view(); //显示页面
	}
	//注册页
	function act_reg() {
		$jump_url = fun_get::get("jump_url");    //获取跳转地址
		if( empty($jump_url) && isset($_SERVER["HTTP_REFERER"]) ) $jump_url=$_SERVER["HTTP_REFERER"];
		if(stristr($jump_url,"app_act=reg") || stristr($jump_url,"app_act=login")) {
			$jump_url = '/';
		}
		$this->jump_fromurl = $jump_url;
		$this->reg_switch = cls_config::get("reg_switch" , "user");
		$this->reg_switch_info = cls_config::get("reg_switch_info" , "user");
		//取注册协议
		$this->reg_content = tab_article::get_bykey("regargreement");
		return $this->get_view(); //显示页面
	}
	//登录页
	function act_reg_shop() {
		$jump_url = fun_get::get("jump_url");    //获取跳转地址
		if( empty($jump_url) && isset($_SERVER["HTTP_REFERER"]) ) $jump_url=$_SERVER["HTTP_REFERER"];
		$this->jump_fromurl = $jump_url;
		$this->list_area = fun_kj::get_area();
		$this->arr_help = $this->get_folder_article('default');
		$this->reg_switch = cls_config::get("reg_switch" , "user");
		$this->reg_switch_info = cls_config::get("reg_switch_info" , "user");
		//取注册协议
		$this->reg_content = tab_article::get_bykey("shopregargreement");
		$this->arr_area = fun_kj::get_area();
		return $this->get_view(); //显示页面
	}
	//帮助
	function act_help() {
		$this->arr_help = $this->get_folder_article('default');
		//当前文章信息
		$id = (int)fun_get::get("id");
		if(empty($id)) $id = $this->arr_help[0]['article_id'];
		$this->id = $id;
		$this->thisinfo = $this->get_article($id);
		return $this->get_view(); //显示页面
	}
	//留言
	function act_msg() {
		$this->arr_help = $this->get_folder_article('default');
		$this->verfifycode = cls_obj::get("cls_user")->is_verifycode();
		$this->options = cls_config::get("msg_options","sys" , array());
		return $this->get_view(); //显示页面
	}

	//文章列表
	function act_shop_news() {
		$channel_id = (int)fun_get::get("channel_id");
		$channel_key = fun_get::get("channel_key");
		$shop_id = (int)fun_get::get("shop_id");
		$this->shopinfo = $this->get_shopinfo($shop_id);
		$this->cfg_opentime = tab_meal_shop::get_opentime($shop_id);
		$act = 'shop.default';
		$channel_name = '';
		$where = (empty($channel_key))? " where channel_id='" . $channel_id . "'" : " where channel_key='" . $channel_key . "'";
		$obj_rs = cls_obj::db()->get_one("select channel_name,channel_id from " . cls_config::DB_PRE . "article_channel" . $where);
		if(!empty($obj_rs)) {
			$channel_name = $obj_rs['channel_name'];
			$channel_id = $obj_rs['channel_id'];
		}
		//活动公告
		$this->arr_activitie = $this->get_activitie($shop_id);	
		$this->arr_help = $this->get_folder_article('default');
		$this->arr_list = $this->get_article_list($channel_id , $shop_id);
		$this->channel_name = $channel_name;
		return $this->get_view(); //显示页面
	}
	//文章列表
	function act_shop_view() {
		//当前文章信息
		$info = $this->get_article(fun_get::get("id"));
		$shop_id = (!empty($info))? $info["article_about_id"] : 0;
		if(empty($shop_id)) {
			cls_error::on_exit("exit" , "访问文章不存在，或已被删除");
		}
		$this->shopinfo = $this->get_shopinfo($shop_id);
		$this->cfg_opentime = tab_meal_shop::get_opentime($shop_id);
		//活动公告
		$this->arr_activitie = $this->get_activitie($shop_id);	
		$this->thisinfo = $info;
		$this->shop_id= $shop_id;
		return $this->get_view(); //显示页面
	}
	//文章列表
	function act_shop_info() {
		//当前文章信息
		$shop_id = fun_get::get("shop_id");
		$shop_info = $this->get_shopinfo($shop_id , array('shop_intro') );
		if(empty($shop_id)) {
			cls_error::on_exit("exit" , "访问店铺不存在，或已被删除");
		}
		$this->shopinfo = $shop_info;
		$this->cfg_opentime = tab_meal_shop::get_opentime($shop_id);
		//活动公告
		$this->arr_activitie = $this->get_activitie($shop_id);	
		$this->shop_id= $shop_id;
		return $this->get_view(); //显示页面
	}
	function act_comment() {
		//评论总计
		$obj_db = cls_obj::db();
		$id = (int)fun_get::get("menu_id");
		$obj_rs = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_menu_comment where comment_menu_id='" . $id . "' and comment_val=1");
		$this->goodnum = (!empty($obj_rs)) ? $obj_rs['num'] : 0;
		$obj_rs = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_menu_comment where comment_menu_id='" . $id . "' and comment_val=0");
		$this->generalnum = (!empty($obj_rs)) ? $obj_rs['num'] : 0;
		$obj_rs = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_menu_comment where comment_menu_id='" . $id . "' and comment_val=-1");
		$this->failnum = (!empty($obj_rs)) ? $obj_rs['num'] : 0;
		$this->arr_list = $this->get_comment_list($id);
		$shopid = (int)fun_get::get("shopid");
		if(!empty($shopid)) {
			$this->shopinfo = $this->get_shopinfo($shopid);
			$this->shopid = $shopid;
		}
		return $this->get_view();
	}
	function act_comment_shop() {
		//评论总计
		$obj_db = cls_obj::db();
		$id = (int)fun_get::get("shop_id");
		$obj_rs = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_order_comment where comment_shop_id='" . $id . "' and comment_val=1");
		$this->goodnum = (!empty($obj_rs)) ? $obj_rs['num'] : 0;
		$obj_rs = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_order_comment where comment_shop_id='" . $id . "' and comment_val=0");
		$this->generalnum = (!empty($obj_rs)) ? $obj_rs['num'] : 0;
		$obj_rs = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_order_comment where comment_shop_id='" . $id . "' and comment_val=-1");
		$this->failnum = (!empty($obj_rs)) ? $obj_rs['num'] : 0;
		$this->arr_list = $this->get_comment_shop_list($id);
		$this->shopinfo = $this->get_shopinfo($id);
		$this->shopid = $id;
		return $this->get_view();
	}
}