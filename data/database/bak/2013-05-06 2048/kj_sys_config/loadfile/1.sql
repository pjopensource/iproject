4	upload_allow	1	是否允许上传	0		bool	other	0	
5	upload_onlogin	1	上传是否需要登陆	0		bool	other	0	
6	upload_count	5	一次上传数量	0		int	other	0	
7	upload_filesize	1	单个文件上传大小(M)	0		int	other	0	
8	upload_allowext	jpeg|jpg|gif|bmp|rar|doc|mp3|rm|wma|txt|zip|swf	允许上传类型	0		array	other	0	
9	upload_noallowext	exe|php|html|htm|js|jsp|asp|aspx	禁止上传类型	0		array	other	0	
193	shop_default_group	1	店铺会员注册默认组，必须为店铺指定组并授予相关权限，否则店主无法管理店铺（此处的id为用户-&gt;管理组中的 id)	0		int	user	0	
26	user_state	1	新注册会员默认状态	0	1=&gt;通过
0=&gt;待审	list	user	0	
25	ads_position	首页A=&gt;A
首页B=&gt;B
首页C=&gt;C
首页D=&gt;D
首页E=&gt;E
站点顶部=&gt;webA
个人中心头部=&gt;userA
个人中心提醒=&gt;userB
企业中心头部=&gt;companyA
企业中心提醒=&gt;companyB	广告位	0		array	other	0	
62	allow_resize	1	是否生成缩略图	0		bool	other	0	
98	menu_unit	份
盘
碗
杯
锅
碟	菜品单位	0		array	meal	0	
99	allow_maxsize	2	上传大小限制(单位M)	0		int	upload	0	
100	allow_ext	jpg
gif
bmp
jpeg
rar
zip
mp3
png	允许上传的文件类型	0		array	upload	0	
101	allow_no_ext	exe
php
js
htm
html
asp
aspx
jsp
cgi	不允许上传的文件类型(优先于允许的类型)	0		array	upload	0	
102	pic_autosmall	0	是否生成缩略图	0		bool	upload	0	
103	pic_autosmall_w	100	默认缩略图宽(px)	0		int	upload	0	
104	pic_autosmall_h	80	默认缩略图高(px)	0		int	upload	0	
105	pic_max_w	0	上传图片最大宽度，超过则自动缩成设置的值，0 表示不限	0		int	upload	0	
106	pic_watermark	1	图片是否加水印	0		bool	upload	0	
107	pic_watermark_pos	9	水印位置(取值范围1-9)	0		int	upload	0	
108	pic_watermark_type	font	水印类型	0	pic=&gt;图片
font=&gt;文字	list	upload	0	
109	pic_watermark_font	klkkdj.com	水印文字(水印类型选择文字时有效)	0		str	upload	0	
110	food_race_default	11	默认加饭id (当非套餐的情况下默认自动添饭，如果未设置则不会自动加)	0		int	meal	0	
111	attribute	头条
最新
热门	文章属性	0		array	article	0	
112	pm_starttime	15:00:00	下午开始时间(当日菜单区分上下午时用到)	0		str	meal	0	
114	ticket_list	0=&gt;暂不需要发票
100=&gt;100元发票 - 需要消耗100积分
200=&gt;200元发票 - 需要消耗200积分
300=&gt;300元发票 - 需要消耗300积分
400=&gt;400元发票 - 需要消耗400积分
500=&gt;500元发票 - 需要消耗500积分	积分换发票	0		array	meal	0	
115	day_opentime	900,1300
1500,1930	每天开放时间,可以设多个时间段900,1300 表示9点到13点	0		array	meal	0	
116	score_money_scale	0.01	积分兑换现金比例(0则表示关闭积分兑换功能)	0		int	meal	0	
117	area_default_id	0	默认地区id	0		int	meal	0	
118	arrive_time	1115=&gt;11：15 之前
1130=&gt;11：30 之前
1145=&gt;11：45 之前
1200=&gt;12：00 之前
1215=&gt;12：15 之前
1230=&gt;12：30 之前
1245=&gt;12：45 之前
1300=&gt;13：00 之前
1400=&gt;14：00 之前
1730=&gt;17：30 之前
1800=&gt;18：00 之前
1830=&gt;18：30 之前
1900=&gt;19：00 之前
1930=&gt;19：30 之前
2000=&gt;20：00 之前
2030=&gt;20：30 之前	送餐时间	0		array	meal	0	
119	admin_log	10	管理日志保留天数	0		int	cache	0	
120	sys_log	5	系统日志保留份数	0		int	cache	0	
121	data_back	2	备份数据保留份数	0		int	cache	0	
122	area_city_id	0	当前城市id	0		str	meal	0	
123	asseter_main_group_id	3	总资产管理组对应id	0		int	erp	0	
124	asseter_group_id	4	资产管理员对应id	0		int	erp	0	
127	shop_user_state	1	新注册店铺会员状态	0	1=&gt;通过
0=&gt;待审	list	user	0	
125	login_verify	show_code=&gt;1
stop_num=&gt;3
stop_time=&gt;3
stop_unit=&gt;i	登录验证配置：show_code=错误多少次需要验证码登录，stop_num=错误多少次禁止登录，stop_time=禁止多长时间，stop_unit=时间单位(d:天,h:时,i:分,s:秒)	0		array	user	0	
126	area_depth	3	默认地区深度	0		int	meal	0	
128	shop_state	1	新注册店铺默认状态	0	1=&gt;通过
0=&gt;待审	list	meal	0	
129	keywords	网上订餐系统,网上订餐多店版,在线订餐系统,订餐cms系统,php订餐系统多店版,KLKKDJ订餐多店版	网站seo关健词	0		str	sys	4	
130	description	KLKKDJ订餐之多店版在单店版的基础上增加了强大的店铺管理，支持各店铺独立绑定域名及个性化模板	网站seo描述	0		str	sys	3	
131	site_title	网上订餐系统多店版	网站名称	0		str	sys	2	
132	title	KLKKDJ网上订餐系统多店版	分享标题	0		str	share	0	
133	pic	/webcss/default/images/logo.jpg	图片	0		str	share	0	
134	cont	KLKKDJ网上订餐系统多店版	内容	0		textarea	share	0	
135	max_info_num	5	用户最多可创建收货信息数量	0		int	meal	0	
136	checkout_day	11	每月几号与店铺结算，取值：1-30 之间	0		int	meal	0	
138	one_order_num	10	单份订单最多允许点餐份数	0		int	meal	0	
139	paymethod	a:3:{i:0;s:12:"afterpayment";i:1;s:9:"repayment";i:2;s:13:"onlinepayment";}	支付方式（预付款与在线付款需要安装支付接口才生效）	0	afterpayment=&gt;货到付款
repayment=&gt;预付款支付
onlinepayment=&gt;在线付款	chk	meal	0	
140	pay_timeout	10	订单支付超时时间设置（单位：分钟）,如果支付成功了，但订单超时了，金额将被转到预付款中	0		int	meal	0	
141	config_module	sys=&gt;系统
user=&gt;用户
meal=&gt;订餐
upload=&gt;上传
article=&gt;文章
cache=&gt;缓存
share=&gt;分享
email=&gt;邮件
sms=&gt;短信
view=&gt;显示设置
userapi=&gt;第三方登录
actgift=&gt;积分兑换	模块配置	0		array	sys	1	
148	order_overtime	5	多少分钟商家未处理订单，作超时处理，后台来单显示用到，方便管理员审单	0		int	meal	0	
149	score_mode	2	积分抵扣模式	0	0=&gt;关闭
1=&gt;统一开通
2=&gt;店铺设定
	list	meal	0	
170	reg_switch_info	网站试运营阶段，暂时只接受邀请注册	关闭或邀请注册时，提示用户信息	0		str	user	0	
169	reg_switch	0	注册选项	0	0=&gt;开通注册
1=&gt;关闭注册
2=&gt;邀请注册	list	user	0	
171	reg_invite_code	8888	注册邀请码(在注册选项为：邀请注册时用到)	0		str	user	0	
172	msg_options	a:1:{i:0;s:5:"login";}	顾客留言配置	0	login=&gt;须要登录
name=&gt;名称必填
email=&gt;邮箱必填
tel=&gt;电话必填	chk	sys	6	
173	rule_uname	default	注册账号格式	0	default=&gt;长度在2-16位，不能包函特殊字符
rule1=&gt;英文、数字、下划线，长度在4-16位的字符
email=&gt;邮箱
mobile=&gt;手机号码	list	user	0	
174	reg_verify	1	显示注册验证码	0		bool	user	0	
175	rule_pwd	4-16	用户密码长度：(如：4-16 表示，大于等于4,小于等于16)	0		str	user	0	
186	port	25	设置邮件服务器的端口，默认为25	0		str	email	0	
185	password		邮件服务器登陆密码	0		str	email	0	
184	host	smtp.126.com	设置邮件服务器的地址	0		str	email	0	
183	fromname	KLKKDJ订餐系统	设置发件人的姓名	0		str	email	0	
182	from	klkkdj@126.com	设置发件人的邮箱地址	0		str	email	0	
187	username	klkkdj	邮件服务器登陆账号	0		str	email	0	
188	state	0	开启短信	0		bool	sms	0	
189	count_id		短信账号(需要从官网购买)	0		str	sms	0	
190	count_pwd		短信密码	0		str	sms	0	
191	neworder_shop_tips		收到新订单时发给店有的短信内容（非短信详情店铺)	0		textarea	sms	0	
192	test_tel		短信测试手机号，网站测试环境下仅向测试手机号发送短信	0		array	sms	0	
194	index_group	price	店铺页默认分组	0	price=&gt;价格
group=&gt;菜品分组	list	view	0	
195	cancel_call_user	0	店铺短信取消订单时，是否将取消信息转发给用户	0		bool	sms	0	
196	cancel_user_beta	#shopname#提醒您：由于#cont#，您可以登录网站重新订餐	当店铺取消订单时，提示用户的消息内容 #cont# 为店铺回复的内容,可以调整其位置	0		textarea	sms	0	
197	weibo_key		新浪微博 App Key	0		str	userapi	0	
198	weibo_secret		新浪微博 App Secret	0		str	userapi	0	
199	qq_key		腾讯QQ APP ID	0		str	userapi	0	
200	qq_secret		腾讯QQ Key	0		str	userapi	0	
201	shopshortpath	0	启用店铺简短域名	0		bool	sys	0	
202	pic_watermark_font_path	/upload/font/simkai.ttf	水印字体文件路径，相对路径即可，如没有字体文件可以从官网下载	0		str	upload	0	
203	neworder_user_tips		当用户下单后发给用户的通知短信，为空则不发送，#shopname# 可表示下单的店名，#sitename#可表示站点名称	0		textarea	sms	0	
204	index_pagesize	20	首页店铺分页大小	0		int	view	0	
205	area_shoplist	60	首页进入各区域后店铺列表缓存时间（分钟）	0		int	cache	0	
206	index_sortby	3	首页店铺列表默认排序	0	1=&gt;最新
2=&gt;销量
3=&gt;人气	list	view	0	
207	index_sortval	2	首页店铺列表默认排序方向	0	1=&gt;顺序
2=&gt;倒序	list	view	0	
208	cart_beta	快点送来
别放辣
饭多点
加辣
超时就不要了	用户下单界面，备助关键词	0		array	view	0	
209	gift_group	生活用品
食品
票、优惠券	礼品分组，多个以换行分隔	0		array	actgift	3	
210	exchange_verify	1	兑换礼品是否需要输入验证码	0		bool	actgift	1	
211	gift_state	0	是否开启兑换功能	0		bool	actgift	2	
