<?php


return array(
    //模块名
    'name' => 'Cms',
    //别名
    'alias' => '资讯',
    //版本号
    'version' => '1.0.0',
    //是否商业模块,1是，0，否
    'is_com' => 0,
    //是否显示在导航栏内？  1是，0否
    'show_nav' => 1,
    //模块描述
    'summary' => '原OneThink的内容模块，传统的CMS模块',
    //开发者
    'developer' => '上海顶想信息科技有限公司',
    //开发者网站
    'website' => 'http://www.topthink.net/',
	
	 'icon'=>'book',
    //前台入口，可用U函数
    'entry' => 'Cms/index/index',
	
	'admin_entry' => 'Admin/article/index',
	
	'icon'=>'home',

    'can_uninstall' => 0

);