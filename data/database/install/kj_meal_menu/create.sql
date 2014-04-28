#-----------------创建表--- kj_meal_menu
DROP TABLE IF EXISTS `{DB_PRE}meal_menu`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}meal_menu` (
`menu_id` int(10) NOT NULL auto_increment,`menu_number` int(10) NOT NULL COMMENT '菜单',`menu_type` smallint(2) NOT NULL COMMENT '菜单类型',`menu_group_id` int(10) NOT NULL COMMENT '分组id',`menu_title` varchar(50) NOT NULL COMMENT '名称',`menu_intro` varchar(255) NOT NULL COMMENT '介绍',`menu_price` decimal(10,2) NOT NULL COMMENT '单价',`menu_unit` varchar(10) NOT NULL COMMENT '单位',`menu_pic` varchar(100) NOT NULL COMMENT '图片',`menu_pic_small` varchar(100) NOT NULL COMMENT '小图',`menu_addtime` int(10) NOT NULL COMMENT '添加时间',`menu_updatetime` int(10) NOT NULL COMMENT '改修时间',`menu_shop_id` int(10) NOT NULL COMMENT '店铺id',`menu_user_id` int(10) NOT NULL COMMENT '用户id',`menu_attribute` smallint(2) NOT NULL,`menu_num` int(10) NOT NULL,`menu_sold_time` int(11) NOT NULL COMMENT '最后售出时间',`menu_sold_today` int(10) NOT NULL COMMENT '今天售出份数',`menu_sold` int(10) default '0' COMMENT '总售出量',`menu_mode` smallint(2) NOT NULL COMMENT '预订模式',`menu_holiday` tinyint(1) NOT NULL COMMENT '节假日',`menu_date` varchar(100) NOT NULL,`menu_weekday` varchar(20) NOT NULL COMMENT '星期',`menu_state` smallint(2) NOT NULL COMMENT '状态',`menu_isdel` tinyint(1) default '0' COMMENT '回收站',`menu_area_id` int(10) NOT NULL,`menu_tj` tinyint(1) NOT NULL COMMENT '是否推荐',`menu_sort` int(10) NOT NULL COMMENT '排序',`menu_comment` int(10) default '0' COMMENT '评论分',`menu_comment_num` int(10) default '0' COMMENT '评论数',PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1

