<?php
return array (
  'index' => 
  array (
    'name' => '全局',
    'list' => 
    array (
      'index' => 
      array (
        'act' => 
        array (
          'main' => '桌面',
          'help' => '帮助中心',
          'guide' => '开店向导',
          'msg' => '意见反馈',
          'call' => '来电显示',
        ),
        'name' => '首页',
      ),
    ),
  ),
  'sys' => 
  array (
    'name' => '系统',
    'list' => 
    array (
      'config' => 
      array (
        'act' => 
        array (
          0 => 'add',
          1 => 'delete',
          2 => 'edit',
          3 => 'update',
        ),
        'name' => '模块设置',
      ),
      'common' => 
      array (
        'act' => 
        array (
          0 => 'clear_cache',
          1 => 'update_pwd',
        ),
        'name' => '通用',
      ),
      'log' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '系统日志',
      ),
      'verify' => 
      array (
        'act' => 
        array (
        ),
        'name' => '验证记录',
      ),
      'area' => 
      array (
        'act' => 
        array (
          0 => 'move',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '地区管理',
      ),
      'database' => 
      array (
        'act' => 
        array (
          0 => 'optimize',
          1 => 'repair',
          2 => 'backup',
          3 => 'reback',
          4 => 'del',
        ),
        'name' => '数据库',
      ),
      'user' => 
      array (
        'act' => 
        array (
          0 => 'add',
          1 => 'del',
          2 => 'delete',
          3 => 'edit',
          4 => 'dellist',
          5 => 'state',
          6 => 'clear_config',
        ),
        'name' => '注册用户',
      ),
      'user.group' => 
      array (
        'act' => 
        array (
          0 => 'move',
          1 => 'save',
          2 => 'limit',
        ),
        'name' => '用户组',
      ),
      'user.depart' => 
      array (
        'act' => 
        array (
          0 => 'move',
          1 => 'save',
        ),
        'name' => '组织架构',
      ),
      'user.action' => 
      array (
        'act' => 
        array (
        ),
        'name' => '经验积分',
      ),
      'user.log' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '管理日志',
      ),
    ),
  ),
  'other' => 
  array (
    'name' => '组件',
    'list' => 
    array (
      'email' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
          3 => 'send',
        ),
        'name' => '邮件管理',
      ),
      'sms' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '短信发送记录',
      ),
      'sms.re' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '短信回复记录',
      ),
      'ads' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '广告管理',
      ),
      'msg' => 
      array (
        'act' => 
        array (
          0 => 'save',
          1 => 'delete',
          2 => 'return',
        ),
        'name' => '留言反馈',
      ),
      'pay' => 
      array (
        'act' => 
        array (
          'not' => '未安装页',
          'config' => '配置页',
          'save' => '保存配置',
          'down' => '下载新接口',
          'install' => '安装接口',
          'uninstall' => '卸载',
          'record' => '充值记录',
        ),
        'name' => '支付接口',
      ),
      'uc' => 
      array (
        'act' => 
        array (
          0 => 'save',
        ),
        'name' => 'UCenter',
      ),
    ),
  ),
  'article' => 
  array (
    'name' => '文章',
    'list' => 
    array (
      'article.channel' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'state',
          3 => 'delete',
        ),
        'name' => '频道',
      ),
      'article.topic' => 
      array (
        'act' => 
        array (
          0 => 'showarticle',
          1 => 'edit',
          2 => 'save',
          3 => 'state',
          4 => 'delete',
        ),
        'name' => '专题',
      ),
      'article' => 
      array (
        'act' => 
        array (
          0 => 'selectfolder',
          1 => 'paste_folder',
          2 => 'edit_folder',
          3 => 'save_folder',
          4 => 'del_folder',
          5 => 'delete_folder',
          6 => 'paste_article',
          7 => 'reback_article',
          8 => 'topic',
          9 => 'list',
          10 => 'dellist',
          11 => 'edit_article',
          12 => 'save_article',
          13 => 'state',
          14 => 'del_article',
          15 => 'delete_article',
        ),
        'name' => '文章',
      ),
    ),
  ),
  'meal' => 
  array (
    'name' => '订餐',
    'list' => 
    array (
      'shop' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '店铺管理',
      ),
      'menu' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
          3 => 'dellist',
          4 => 'del',
          5 => 'reback',
          6 => 'state',
          7 => 'group',
          8 => 'mode',
        ),
        'name' => '菜品管理',
      ),
      'menu.today' => 
      array (
        'act' => 
        array (
          0 => 'add',
          1 => 'save',
        ),
        'name' => '当日菜品',
      ),
      'order' => 
      array (
        'act' => 
        array (
          0 => 'confirm',
          1 => 'delete',
        ),
        'name' => '订单管理',
      ),
      'menu.group' => 
      array (
        'act' => 
        array (
          0 => 'save',
        ),
        'name' => '菜品分组',
      ),
      'dispatch' => 
      array (
        'act' => 
        array (
          0 => 'add',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '派送范围',
      ),
      'act' => 
      array (
        'act' => 
        array (
          0 => 'add',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '活动列表',
      ),
    ),
  ),
  'report' => 
  array (
    'name' => '统计报表',
    'list' => 
    array (
      'order' => 
      array (
        'act' => 
        array (
        ),
        'name' => '销售报表',
      ),
      'user' => 
      array (
        'act' => 
        array (
        ),
        'name' => '用户统计',
      ),
      'top' => 
      array (
        'act' => 
        array (
        ),
        'name' => '排行榜',
      ),
    ),
  ),
  'act' => 
  array (
    'name' => '积分活动',
    'list' => 
    array (
      'gift' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '活动礼品',
      ),
      'gift.record' => 
      array (
        'act' => 
        array (
          'send' => '发货',
        ),
        'name' => '兑换记录',
      ),
    ),
  ),
);