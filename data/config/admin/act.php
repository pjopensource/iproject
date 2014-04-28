<?php
/* 数据字典
 * 数据库 -> 表 -> 字段
 * 值：大于 10 表示不参与用户配置 , 1表示显示，0表示不显示
 */
return array(
	"act.gift" => array(
		"gift_id" => array("val" => 0,"w" => 0), //id
		"gift_name" => array("val" => 1,"w" => 150), //名称
		"gift_total" => array("val" => 1,"w" => 50), //总数
		"gift_total_day" => array("val" => 1,"w" => 50), //每天数量
		"gift_num" => array("val" => 1,"w" => 50), //已兑换
		"gift_num_today" => array("val" => 1,"w" => 50), //今日兑换
		"gift_time" => array("val" => 1,"w" => 120), //最后兑换时间
		"gift_starttime" => array("val" => 1,"w" => 120), //开始时间
		"gift_endtime" => array("val" => 1,"w" => 120), //结束时间
		"gift_state" => array("val" => 1,"w" => 80), //状态
		"gift_group" => array("val" => 1,"w" => 120), //分组
	),
	"act.gift.record" => array(
		"record_id" => array("val" => 0,"w" => 0), //id
		"record_user_id" => array("val" => 0,"w" => 60), //用户id
		"user_name" => array("val" => 1,"w" => 80), //用户名
		"record_gift_id" => array("val" => 0,"w" => 60), //礼品id
		"gift_name" => array("val" => 1,"w" => 120), //礼品名
		"record_score" => array("val" => 1,"w" => 50), //消耗积分
		"record_datetime" => array("val" => 1,"w" => 120), //兑换时间
		"record_num" => array("val" => 1,"w" => 50), //兑换数量
		"record_ip" => array("val" => 0,"w" => 80), //IP
		"record_linkname" => array("val" => 1,"w" => 60), //收货人
		"record_tel" => array("val" => 1,"w" => 80), //联系电话
		"record_address" => array("val" => 1,"w" => 200), //地址
		"record_send_time" => array("val" => 1,"w" => 120), //发货时间
		"record_receive_time" => array("val" => 1,"w" => 120), //领取时间
	),
);