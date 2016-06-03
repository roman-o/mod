<?php

Config::set('site_name','Your Site Name');

Config::set('languages', array('ru','ua'));


Config::set('routes',array(
    'default' =>'',
    'admin'=>'admin_',
    'account'=>'account_',
));

Config::set('default_route', 'default');
Config::set('default_language', 'ru');
Config::set('default_controller', 'news');
Config::set('default_action', 'index');

Config::set('db.host','localhost');
Config::set('db.user','root');
Config::set('db.password','');
Config::set('db.db_name','modul');

Config:: set('salt', 'helloworld');