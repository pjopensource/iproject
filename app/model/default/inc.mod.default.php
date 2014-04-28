<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class inc_mod_default extends cls_base{

	/**
	 * admin 目录 初始类，启动 : 登录检查，权限检查
	 */
	function __construct($arr_v) {
		parent::__construct($arr_v);
		$this->init();
		$this->this_login_user = cls_obj::get("cls_user");

	}
	//初始化通用变量
	function init() {
		//当前地区
		$this->area_id = (int)cls_session::get_cookie("area_id");
		$this->area_name = cls_session::get_cookie("area_name");
	}
	//取当前地区下级地区,拼按首字母分类
	function get_area_1($id = -1) {
		$arr_return = array();
		if($id<0) $id = (int)cls_config::get("area_city_id","meal");
		$obj_db = cls_obj::db();
		$obj_result = $obj_db->select("select area_id,area_name,area_val,area_jian,area_depth,area_pic,area_childs from " .cls_config::DB_PRE . "sys_area where area_pid='" . $id . "'  order by area_sort");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			if(!empty($obj_rs['area_pic'])) $obj_rs['area_pic'] = fun_get::html_url($obj_rs['area_pic']);
			if(!empty($obj_rs['area_val'])) $obj_rs['area_name'] = $obj_rs['area_val'];
			unset($obj_rs['area_val']);
			$arr_return[] = $obj_rs;
		}
		return $arr_return;
	}
	//搜索分类
	function get_area_2($id = -1 , $skey = '') {
		$arr_return = array('pids' => array() ,'list' => array() );
		if($id<0) $id = (int)cls_config::get("area_city_id","meal");
		$depth = (int)cls_config::get('area_depth' , 'meal');
		$obj_db = cls_obj::db();
		$where = " where " . $obj_db->concat("," , "area_pids" , ",") . " like '%," . $id . ",%' and (area_name like '%" . $skey . "%' or area_jian like '%" . strtoupper($skey) . "%') and area_depth<" . $depth;
		$arr_pids = array();
		$obj_result = $obj_db->select("select area_id,area_name,area_val,area_jian,area_depth,area_pids,area_childs from " .cls_config::DB_PRE . "sys_area" . $where);
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			if(!empty($obj_rs['area_val'])) $obj_rs['area_name'] = $obj_rs['area_val'];
			unset($obj_rs['area_val']);
			$obj_rs['type'] = 'area';
			$arr_return['list'][] = $obj_rs;
			$arr = explode("," , $obj_rs['area_pids']);
			unset($arr[count($arr)-1]);
			if(count($arr)>0) $arr_pids[] = implode("," , $arr);
		}
		if(!empty($arr_pids)) {
			$arr_pids = array_unique($arr_pids);
			$ids = implode("," , $arr_pids);
			$obj_result = $obj_db->select("select area_id,area_name,area_val,area_jian,area_depth,area_pids,area_childs from " .cls_config::DB_PRE . "sys_area where area_id in(" . $ids . ")");
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$arr_return['pids']['id_' . $obj_rs['area_id']] = $obj_rs;
			}
		}
		//搜索店铺
		$obj_result = $obj_db->select("select shop_name,shop_id from " .cls_config::DB_PRE . "meal_shop where shop_name like '%" . $skey . "%' or shop_jian like '%" . $skey . "%'");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$obj_rs['type'] = 'shop';
			$arr_return['list'][] = $obj_rs;
		}

		return $arr_return;
	}

	//取地区路径
	function get_path($pid) {
		$city_id = (int)cls_config::get("area_city_id","meal");
		$arr_return = array();
		$str_sql="select area_id,area_name,area_pid from " . cls_config::DB_PRE . "sys_area where area_id='".$pid."'";
		$obj_result = cls_obj::db()->query($str_sql);
		if($obj_rs = cls_obj::db()->fetch_array($obj_result))	{
			$arr_return[] = $obj_rs;
			if($obj_rs['area_id']!=$city_id) {
				$arr = $this->get_path($obj_rs['area_pid']);
				if(!empty($arr)) $arr_return = array_merge($arr,$arr_return);
			}
		}
		return $arr_return;
	}
	/* 将购物车数据转换成数组格式 shopid:1,2,3|2,3
	 *
	 */
	function format_cart($cart_ids) {
		$arr_return = array("shop_ids" => array() , "menu_ids" => array() );
		$arr = explode("||" , $cart_ids);
		foreach($arr as $item) {
			$arr_cart = $arr_cart_id = array();
			$arr_1 = explode(":" , $item);
			if(count($arr_1)<2) continue;
			$arr_2 = explode("|" , $arr_1[1]);
			foreach($arr_2 as $menu) {
				$arr_3 = explode("," , $menu);
				$arr_cart_id = array_merge($arr_cart_id , $arr_3);
				$arr_cart[] = $arr_3;
			}
			$arr_cart_id = array_unique($arr_cart_id);
			$arr_return["shop_" . $arr_1[0]] = array("ids" => $arr_cart_id , "cart" => $arr_cart ,"shop_id" => $arr_1[0]);
			$arr_return["shop_ids"][] = $arr_1[0];
			$arr_return["menu_ids"] = array_merge($arr_cart_id , $arr_return["menu_ids"]);
		}
		return $arr_return;
	}
	//取当前登录用户所有收货信息
	function get_infolist($shop_id) {
		$obj_db = cls_obj::db();
		$arr_info = array("list"=>array() , "isover" => 0) ;
		$arr_area_id = array();
		$lng_uid = cls_obj::get("cls_user")->uid;
		if(empty($lng_uid)) $lng_uid = -1;
		$obj_result = $obj_db->query("select * from ".cls_config::DB_PRE."meal_info where info_user_id='" . $lng_uid . "' and info_area_id in (select area_id from " . cls_config::DB_PRE . "meal_dispatch a left join " . cls_config::DB_PRE . "sys_area b on " . $obj_db->concat("," , 'b.area_pids' , ',') . " like " . $obj_db->concat(",%" , "a.dispatch_area_id" , "%,") . " where a.dispatch_shop_id='" . $shop_id . "')");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$arr_info["list"][] = $obj_rs;
		}
		return $arr_info;
	}
	/**
	 * 统一获取分页样式
	 * arr_info : 数组 , 值为 : 
	 * 返回 : 分页html字符串
	 */
	function get_pagebtns( $arr_info ) {
		if($arr_info['total'] < 1) return '';
		$prepg = $arr_info['page']-1;
		$nextpg = $arr_info['page']+1;//$page == $pages ? 0 : ($page+1);
		$str_left="";
		$str_right="";
		$agent = fun_get::agent();
		$pagenav ='<li class="info">共:<font color="#ff6600">'.$arr_info['total'].'</font> 条&nbsp;页 '.$arr_info['page'].'/'.$arr_info['pages'];
		if(in_array($agent,array('','ipad'))) $pagenav .= '&nbsp;&nbsp;页大小<input type="text" name="url_pagesize" value="' . $arr_info['pagesize'] . '" style="width:20px;color:#888888" onkeyup="kj.page.size(event,\''.$this->app_module . "." . $this->app . "." . $this->app_act . '\',\''.$this->app_dir.'\');" id="id_page_size">';
		$pagenav .= '</li>';
		$str_x="";
		if($arr_info["pages"] > 10) {
			if($arr_info['page']>5){
				$lng_pre=$arr_info['page']-5;
				$lng_next=$arr_info['page']+5;
				$str_left="<li><a href='javascript:kj.page.go(1);'>首页</a></li>";
				$str_right="<li><a href='javascript:kj.page.go(".$arr_info['pages'].");'>尾页</a></li>";
			}else{
				$lng_pre=1;
				$lng_next=10;
				$str_right="<li><a href='javascript:kj.page.go(".$arr_info['pages'].");'>尾页</a></li>";
			}
		}else{
			$lng_pre=1;
			$lng_next=$arr_info['pages'];
		}
		if($lng_next>=$arr_info['pages']){
			$lng_next=$arr_info['pages'];
			$str_right="";
		}
		for($i=$lng_pre;$i<=$lng_next;$i++){
			$str_sel="";
			if($i==$arr_info['page']) $str_sel=" class='x_sel'";
			$str_x.="<li".$str_sel."><a href='javascript:kj.page.go(".$i.");'>[".$i."]</a></li>";
		}
		$pagenav.=$str_left.$str_x.$str_right;
		if(in_array($agent,array('','ipad'))) $pagenav .= "<li class='x_go'><input type='text' name='go_page' id='id_go_page' value='' class='pTxt1 x_txt' onkeyup='kj.page.page_keyup(event);'>&nbsp;&nbsp;<a href=\"javascript:kj.page.go(kj.obj('#id_go_page').value);\">跳转</a></li>";
		return $pagenav;
	}
	//获取指定目录文章列表
	function get_folder_article($key , $limit = '') {
		$arr_return = array();
		$arr_where = array();
		$where = ' where article_state>0 and article_isdel=0';
		if(!empty($key)) $where .= " and channel_key='" . $key . "'";
		$obj_result = cls_obj::db()->select("select article_id,article_title,article_addtime from " . cls_config::DB_PRE . "article_channel a left join " . cls_config::DB_PRE . "article b on a.channel_id=b.article_channel_id" . $where . $limit);
		while($obj_rs = cls_obj::db()->fetch_array($obj_result)) {
			$obj_rs["addtime"] = date("Y/m/d" , $obj_rs["article_addtime"]);
			$arr_return[] = $obj_rs;
		}
		return $arr_return;
	}
	/* 取店铺当天经营菜品条件
	 *
	 */
	function get_menu_today_where($shop_id) {
		$obj_db = cls_obj::db();
		$where = " where menu_shop_id='" . $shop_id . "' and menu_state>0 and menu_isdel=0";
		//取每天自定义菜品
		$arr_opentime = tab_meal_shop::get_opentime($shop_id);
		$date_period = $arr_opentime['nowindex'];
		if($date_period>0) {
			$where_x = " and (today_date_period='".$date_period."' or today_date_period=0)";
		} else {
			$where_x = " and today_date_period='".$date_period."'";
		}
		$arr_x = array();
		$obj_today = $obj_db->select("select today_menu_id from " . cls_config::DB_PRE . "meal_menu_today where today_shop_id='" . $shop_id . "' and today_date='" . strtotime(date('Y-m-d' , TIME)) . "'" . $where_x);
		while($obj_rs = $obj_db->fetch_array($obj_today)) {
			$arr_x[] = $obj_rs["today_menu_id"];
		}
		$str_ids = implode("," , $arr_x);
		if(!empty($str_ids)) {
			$where .=" and (menu_mode!=2 or menu_id in(" . $str_ids . "))";
		} else {
			$where .=" and menu_mode!=2";
		}
		return $where;
	}
	//获取店铺营销活动
	function get_shop_act($shop_id , $price , $num) {
		$arr_return = array();
		$date = date("Y-m-d H:i:s");
		$i = 1;
		$arr_where = $arr_num_where = $arr_time_num_where = array();
		$obj_result = cls_obj::db()->select("select act_id,act_name,act_where,act_method,act_where_val,act_method_val from " . cls_config::DB_PRE . "meal_act where act_isdel=0 and act_state>0 and act_shop_id='" . $shop_id . "' and act_starttime<='" . $date . "' and act_endtime>='" . $date . "'");
		while($obj_rs = cls_obj::db()->fetch_array($obj_result)) {
			$obj_rs["index"] = $i;
			$i++;
			if($obj_rs['act_where']==1 && $price>=(float)$obj_rs['act_where_val']) {//大于指定金额
				$obj_rs['where_val'] = (float)$obj_rs['act_where_val'];
				$arr_return[] = $obj_rs;
			} else if($obj_rs['act_where']==2) {//指定时间
				$arr = explode("," , $obj_rs['act_where_val']);
				$time1 = (int)$arr[0];
				$time2 = (count($arr)>1) ? (int)$arr[1] : 0;
				$time = (int)date("Hi");
				if($time>=$time1 && $time<$time2) {
					$x = (int)substr($time,0,-2);
					$x1 = (int)substr($time2,0,-2);
					if($x == $x1) {
						$x = (int)substr($time,-2);
						$x1 = (int)substr($time2,-2);
						$obj_rs['time'] = ($x1-$x)*60*1000;
					} else {
						$obj_rs['time'] = ($x1-$x)*60*60*1000;
					}
					$arr_return[] = $obj_rs;
				}
			} else if($obj_rs['act_where']==3 && $num>=(int)$obj_rs['act_where_val']) {//达到指定数量
				$obj_rs['where_val'] = (int)$obj_rs['act_where_val'];
				$arr_return[] = $obj_rs;
			} else if($obj_rs['act_where']==4) {//指定时间，达到指定数量
				$arr = explode("," , $obj_rs['act_where_val']);
				$time1 = (int)$arr[0];
				$time2 = (count($arr)>1) ? (int)$arr[1] : 0;
				$obj_rs['where_val'] = $lng_num = (count($arr)>2) ? (int)$arr[2] : 0;
				$time = (int)date("Hi");
				if($time>=$time1 && $time<$time2 && $num>=$lng_num) {
					$x = (int)substr($time,0,-2);
					$x1 = (int)substr($time2,0,-2);
					if($x == $x1) {
						$x = (int)substr($time,-2);
						$x1 = (int)substr($time2,-2);
						$obj_rs['time'] = ($x1-$x)*60*1000;
					} else {
						$obj_rs['time'] = ($x1-$x)*60*60*1000;
					}
					$arr_return[] = $obj_rs;
				}
			}
		}
		return $arr_return;
	}
	//取满足指定地区送餐条件的店铺
	function get_area_shop($area_id) {
		$cache_time = (int)cls_config::get("area_shoplist","cache");
		$cache_key = "area_" . $area_id . "_shopid";
		$arr_shop = cls_cache::get($cache_key,"area_shop",$cache_time);
		if($arr_shop === null) {
			$obj_db = cls_obj::db();
			$arr_shop = array('id'=>array(),'list'=>array());
			$obj_rs = $obj_db->get_one("select area_pids from " . cls_config::DB_PRE . "sys_area where area_id='" . $area_id . "'");
			if(!empty($obj_rs)) {
				$sql = "select dispatch_shop_id,dispatch_price,dispatch_time from " . cls_config::DB_PRE . "meal_dispatch where dispatch_area_id in(" . $obj_rs['area_pids'] . ")";
				$obj_result = $obj_db->select($sql);
				while($obj_rs = $obj_db->fetch_array($obj_result)) {
					$arr_shop['id'][] = $obj_rs['dispatch_shop_id'];
					if((int)$obj_rs['dispatch_price']==$obj_rs['dispatch_price']) $obj_rs['dispatch_price'] = (int)$obj_rs['dispatch_price'];
					$arr_shop['list']['id_' . $obj_rs['dispatch_shop_id']] = $obj_rs;
				}
			}
			cls_cache::set($arr_shop,$cache_key,"area_shop");
		}
		return $arr_shop;
	}
}