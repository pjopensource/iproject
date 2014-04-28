var jsshopadmin = new function() {
	this.html = [];
	this.act_default = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app=shop','id':'shopadmin','type':'iframe','title':'店铺首页','w':750,'max_h':600,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_info = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app=shop&app_act=info','id':'shopadmin','type':'iframe','title':'店铺资料','w':750,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_extend = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app=shop&app_act=extend','id':'shopadmin','type':'iframe','title':'订餐配置','w':750,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_menu = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app_module=shop&app=menu','id':'shopadmin','type':'iframe','title':'菜品管理','w':980,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_menu_today = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app_module=shop&app=menu.today','id':'shopadmin','type':'iframe','title':'当天菜品','w':750,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_menu_group = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app_module=shop&app=menu.group','id':'shopadmin','type':'iframe','title':'菜品分组','w':750,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_order = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app_module=shop&app=order','id':'shopadmin','type':'iframe','title':'订单管理','w':980,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_dispatch = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app_module=shop&app=dispatch','id':'shopadmin','type':'iframe','title':'送餐范围','w':750,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_report = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app_module=shop&app=report','id':'shopadmin','type':'iframe','title':'报表统计','w':980,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
	this.act_shop_act = function() {
		kj.dialog.close("#winshopadmin");
		kj.dialog({'url':kj.cfg('baseurl') + '/index.php?app_module=shop&app=act','id':'shopadmin','type':'iframe','title':'营销活动','w':980,'max_h':650,'showbtnmax':false,'showbtnhide':false});
	}
}