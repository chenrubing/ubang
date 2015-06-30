<?php

return array(
    //模块名
    'name' => 'App',
    //别名
    'alias' => 'App服务',
    //版本号
    'version' => '1.0.0',
    //是否商业模块,1是，0，否
    'is_com' => 0,
    //是否显示在导航栏内？  1是，0否
    'show_nav' => 1,
    //模块描述
    'summary' => '狂装网APP模块',
    //开发者
    'developer' => '济南网道网络技术有限公司',
    //开发者网站
    'website' => '',
    //前台入口，可用U函数
    'entry' => 'App/index/index',

    'admin_entry' => 'Admin/Service/index',

    'icon' => 'th',

    'can_uninstall' => 1
);