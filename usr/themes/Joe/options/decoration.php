<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$NProgressJS = new \Typecho\Widget\Helper\Form\Element\Select(
	'NProgressJS',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'页面顶部链接加载进度条动画'
);
$NProgressJS->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($NProgressJS->multiMode());

$NewYearLantern = new \Typecho\Widget\Helper\Form\Element\Text(
	'NewYearLantern',
	NULL,
	NULL,
	'新年灯笼挂件文字',
	'介绍：填写后网站顶部将会始终展示新年灯笼挂件，增添网站新年氛围<br>
	示例：新年快乐<br>
	关于：春节，亦称农历新年，是中国最重要的传统节日之一，春节时期往往伴随着阖家团圆和一个新的开始，春节不仅是一个普通的节日，它凝聚了中华民族的历史记忆和文化情感，承载着深厚的文化意义和社会价值'
);
$NewYearLantern->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($NewYearLantern->multiMode());

$JLogo_Light_Effect = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLogo_Light_Effect',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'LOGO扫光效果',
	'介绍：开启后顶栏的LOGO图标将会有扫光效果'
);
$JLogo_Light_Effect->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JLogo_Light_Effect->multiMode());

$loading_list = [];
$loading_scan = scandir(JOE_ROOT . 'module/loading');
if (is_array($loading_scan)) {
	foreach ($loading_scan as $value) {
		$loading_file = JOE_ROOT . 'module/loading/' . $value;
		if (!is_file($loading_file)) continue;
		$loading_name = pathinfo($value, PATHINFO_FILENAME);
		$loading_content = file_get_contents(JOE_ROOT . 'module/loading/' . $value);
		if (preg_match('/\<\!\-\-(.+)\-\-\>/', $loading_content, $loading_value_match)) {
			$loading_value = trim($loading_value_match[1]);
		} else {
			$loading_value = $loading_name;
		}
		$loading_list[$loading_name] = $loading_value;
	}
}
$loading_list['off'] = '关闭';
$default_loading = isset($loading_list['concise']) ? 'concise' : array_key_first($loading_list);
$JLoading = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLoading',
	$loading_list,
	$default_loading,
	'全局加载动画',
	'介绍：网站全局页面加载 Loading 动画，开启后可防止使用谷歌内核的浏览器出现闪动问题'
);
$JLoading->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JLoading->multiMode());

$FirstLoading = new \Typecho\Widget\Helper\Form\Element\Select(
	'FirstLoading',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'仅首次加载动画',
	'介绍：只在用户首次进入网站时展示加载动画，开启后可防止使用谷歌内核的浏览器出现闪动问题'
);
$FirstLoading->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($FirstLoading->multiMode());

$JIndex_Link_Active = new \Typecho\Widget\Helper\Form\Element\Select(
	'JIndex_Link_Active',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'是否开启文章列表选中动画',
	'介绍：开启后首页和搜索页面展示的文章列表中文章被鼠标移入或者选中则会出现浮起动画'
);
$JIndex_Link_Active->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JIndex_Link_Active->multiMode());

$JPendant_SSL = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPendant_SSL',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启SSL安全认证图标',
	'介绍：开启后站点右下角将会显示SSL安全认证图标'
);
$JPendant_SSL->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JPendant_SSL->multiMode());

$JGrey_Model = new \Typecho\Widget\Helper\Form\Element\Select(
	'JGrey_Model',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启哀悼模式',
	'介绍：开启后全站都显示灰色，为逝者默哀！'
);
$JGrey_Model->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JGrey_Model->multiMode());

$JHeader_Counter = new \Typecho\Widget\Helper\Form\Element\Select(
	'JHeader_Counter',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启顶部浏览进度条',
	'介绍：开启后页面顶部位置将会展示屏幕浏览进度条'
);
$JHeader_Counter->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JHeader_Counter->multiMode());

$JFooter_Fish = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFooter_Fish',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启底部鱼群跳跃（消耗性能，可能会卡）',
	'介绍：开启后页面底部位置将会展示灵动的鱼群跳跃，增添网站灵动气氛'
);
$JFooter_Fish->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JFooter_Fish->multiMode());

$JListAnimate = new \Typecho\Widget\Helper\Form\Element\Select(
	'JListAnimate',
	array(
		'off' => '关闭（默认）',
		'bounce' => 'bounce',
		'flash' => 'flash',
		'pulse' => 'pulse',
		'rubberBand' => 'rubberBand',
		'headShake' => 'headShake',
		'swing' => 'swing',
		'tada' => 'tada',
		'wobble' => 'wobble',
		'jello' => 'jello',
		'heartBeat' => 'heartBeat',
		'bounceIn' => 'bounceIn',
		'bounceInDown' => 'bounceInDown',
		'bounceInLeft' => 'bounceInLeft',
		'bounceInRight' => 'bounceInRight',
		'bounceInUp' => 'bounceInUp',
		'bounceOut' => 'bounceOut',
		'bounceOutDown' => 'bounceOutDown',
		'bounceOutLeft' => 'bounceOutLeft',
		'bounceOutRight' => 'bounceOutRight',
		'bounceOutUp' => 'bounceOutUp',
		'fadeIn' => 'fadeIn',
		'fadeInDown' => 'fadeInDown',
		'fadeInDownBig' => 'fadeInDownBig',
		'fadeInLeft' => 'fadeInLeft',
		'fadeInLeftBig' => 'fadeInLeftBig',
		'fadeInRight' => 'fadeInRight',
		'fadeInRightBig' => 'fadeInRightBig',
		'fadeInUp' => 'fadeInUp',
		'fadeInUpBig' => 'fadeInUpBig',
		'fadeOut' => 'fadeOut',
		'fadeOutDown' => 'fadeOutDown',
		'fadeOutDownBig' => 'fadeOutDownBig',
		'fadeOutLeft' => 'fadeOutLeft',
		'fadeOutLeftBig' => 'fadeOutLeftBig',
		'fadeOutRight' => 'fadeOutRight',
		'fadeOutRightBig' => 'fadeOutRightBig',
		'fadeOutUp' => 'fadeOutUp',
		'fadeOutUpBig' => 'fadeOutUpBig',
		'flip' => 'flip',
		'flipInX' => 'flipInX',
		'flipInY' => 'flipInY',
		'flipOutX' => 'flipOutX',
		'flipOutY' => 'flipOutY',
		'rotateIn' => 'rotateIn',
		'rotateInDownLeft' => 'rotateInDownLeft',
		'rotateInDownRight' => 'rotateInDownRight',
		'rotateInUpLeft' => 'rotateInUpLeft',
		'rotateInUpRight' => 'rotateInUpRight',
		'rotateOut' => 'rotateOut',
		'rotateOutDownLeft' => 'rotateOutDownLeft',
		'rotateOutDownRight' => 'rotateOutDownRight',
		'rotateOutUpLeft' => 'rotateOutUpLeft',
		'rotateOutUpRight' => 'rotateOutUpRight',
		'hinge' => 'hinge',
		'jackInTheBox' => 'jackInTheBox',
		'rollIn' => 'rollIn',
		'rollOut' => 'rollOut',
		'zoomIn' => 'zoomIn',
		'zoomInDown' => 'zoomInDown',
		'zoomInLeft' => 'zoomInLeft',
		'zoomInRight' => 'zoomInRight',
		'zoomInUp' => 'zoomInUp',
		'zoomOut' => 'zoomOut',
		'zoomOutDown' => 'zoomOutDown',
		'zoomOutLeft' => 'zoomOutLeft',
		'zoomOutRight' => 'zoomOutRight',
		'zoomOutUp' => 'zoomOutUp',
		'slideInDown' => 'slideInDown',
		'slideInLeft' => 'slideInLeft',
		'slideInRight' => 'slideInRight',
		'slideInUp' => 'slideInUp',
		'slideOutDown' => 'slideOutDown',
		'slideOutLeft' => 'slideOutLeft',
		'slideOutRight' => 'slideOutRight',
		'slideOutUp' => 'slideOutUp',
	),
	'off',
	'文章列表动画',
	'介绍：开启后，文章列表将会显示所选择的炫酷动画'
);
$JListAnimate->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JListAnimate->multiMode());

$JLive2d = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLive2d',
	array(
		'off' => '关闭（默认）',
		'shizuku.model.json' => '志津久(shizuku)',
		'izumi.model.json' => '泉(izumi)',
		'haru01.model.json' => '李夏露01(haru01)',
		'haru02.model.json' => '李夏露02(haru02)',
		'wanko.model.json' => '王淑玲(wanko)',
		'hijiki.model.json' => '羊栖菜(hijiki)',
		'koharu.model.json' => '小春(koharu)',
		'z16.model.json' => 'z16',
		'haruto.model.json' => '哈鲁托(haruto)',
		'tororo.model.json' => '托罗罗(tororo)',
		'chitose.model.json' => '千岁(chitose)',
		'miku.model.json' => '米库(miku)',
		'Epsilon2.1.model.json' => '艾司隆2.1(Epsilon2.1)',
		'unitychan.model.json' => '陈统一(unitychan)',
		'nico.model.json' => '尼科(nico)',
		'rem.model.json' => '雷姆(rem)',
		'nito.model.json' => 'nito',
		'nipsilon.model.json' => '尼普西隆(nipsilon)',
		'ni-j.model.json' => '杰倪(ni-j)',
		'nietzche.model.json' => '采尼(nietzche)',
		'platelet.model.json' => '血小板(platelet)',
		'model.json' => '铃十五(isuzu)',
		'katou_01.model.json' => '卡图01(katou_01)',
		'mikoto.model.json' => '米科托(mikoto)',
		'seifuku.model.json' => '艾福斯(seifuku)',
		'ichigo.model.json' => '圆脸女孩(ichigo)',
		'hk416.model.json' => '女枪手(hk416)'
	),
	'off',
	'选择一款喜爱的Live2D动态人物模型（仅在屏幕分辨率大于1400px下显示）',
	'介绍：开启后会在右下角显示一个小人'
);
$JLive2d->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JLive2d->multiMode());

$JCursorEffects = new \Typecho\Widget\Helper\Form\Element\Select(
	'JCursorEffects',
	[
		'off' => '关闭（默认）',
		'cursor3.js' => '点击文字',
		'firework.js' => '点击烟花',
		'cursor1.js' => '点击爆开-小',
		'cursor2.js' => '点击爆开-大',
		'cursor4.js' => '点击爱心-随机',
		'cursor5.js' => '点击爱心-红色',
		'cursor6.js' => '跟随星星',
		'cursor7.js' => '跟随残影',
		'cursor8.js' => '跟随黄脸-淘气',
		'cursor9.js' => '跟随黄脸-哭笑',
		'cursor10.js' => '跟随水泡泡',
		'cursor11.js' => '跟随雪花',
	],
	'off',
	'选择鼠标特效',
	'介绍：用于开启炫酷的鼠标特效'
);
$JCursorEffects->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JCursorEffects->multiMode());

$JDocumentTitle = new \Typecho\Widget\Helper\Form\Element\Text(
	'JDocumentTitle',
	NULL,
	NULL,
	'网页被隐藏时显示的标题',
	'介绍：在PC端切换网页标签时，网站标题显示的内容。如果不填写，则默认不开启 <br />
		 注意：严禁加单引号或双引号！！！否则会导致网站出错！！'
);
$JDocumentTitle->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JDocumentTitle);
