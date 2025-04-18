<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$JPrevent = new Typecho_Widget_Helper_Form_Element_Select(
	'JPrevent',
	array('on' => '开启', 'off' => '关闭（默认）'),
	'off',
	'是否开启QQ、微信防红拦截',
	'介绍：开启后，如果在QQ、微信里打开网站，则会提示跳转浏览器打开'
);
$JPrevent->setAttribute('class', 'joe_content joe_safe');
$form->addInput($JPrevent->multiMode());

$JProtect = new Typecho_Widget_Helper_Form_Element_Select(
	'JProtect',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启反蜘蛛爬虫非法扫描',
	'介绍：开启后，可以一定程度上屏蔽各种自动扫描蜘蛛爬虫机器人非法扫描站点'
);
$JProtect->setAttribute('class', 'joe_content joe_safe');
$form->addInput($JProtect->multiMode());