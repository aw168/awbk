<?php

if (!defined('__TYPECHO_ROOT_DIR__') || empty($_SERVER['HTTP_HOST'])) {
	http_response_code(404);
	exit;
}

if (PHP_VERSION < base64_decode('Ny40')) {
	throw new Typecho_Exception(base64_decode('5oKo55qEUEhQ54mI5pys6L+H5L2O77yM6K+35L2/55SoVjcuNOWPiuS7peS4iueJiOacrOi/kOihjCA8YSBocmVmPSJodHRwOi8vYmxvZy5icmk2LmNuL2FyY2hpdmVzLzE4Lmh0bWwiIHRhcmdldD0iX2JsYW5rIj5Kb2Xlho3nu63liY3nvJjkuLvpopg8L2E+'));
}

// 记录脚本开始执行的时间戳
define('JOE_START_TIME', microtime(true));

// 记录初始内存使用量
define('JOE_START_MEMORY', memory_get_usage());

// 主题文件夹名称
define('THEME_NAME', basename(JOE_ROOT));

// 通过 siteUrl 获取域名
define('JOE_DOMAIN', parse_url(Helper::options()->siteUrl, PHP_URL_HOST));

header('Generator: YiHang');
header('Author: YiHang');

/* 继承方法函数 */
require_once(JOE_ROOT . 'public/widget.php');

/* Composer自动加载 */
require_once(JOE_ROOT . 'vendor/autoload.php');

/* 公用函数 */
require_once(JOE_ROOT . 'public/function.php');

/* 过滤内容函数 */
require_once(JOE_ROOT . 'public/parse.php');

/* 主题内置开放API */
require_once(JOE_ROOT . 'public/route.php');

/* 插件方法 */
require_once(JOE_ROOT . 'public/factory.php');

/* 主题初始化 */
function themeInit($self)
{
	/** 首次启用安装主题 */
	joe\install();
	/* 强制用户要求填写邮箱 */
	Helper::options()->commentsRequireMail = true;
	/* 强制用户要求无需填写url */
	Helper::options()->commentsRequireURL = false;
	/* 强制用户开启评论回复 */
	Helper::options()->commentsThreaded = true;
	/* 强制回复楼层最高999层 */
	Helper::options()->commentsMaxNestingLevels = 999;

	/** 屏蔽垃圾机器人 */
	if (Helper::options()->JShieldScan != 'off') require_once JOE_ROOT . 'public/tencent_protect.php';

	if (Helper::options()->JPrevent == 'on' && (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false)) {
		// 我就不信这次腾讯会再给封了！！！
		require JOE_ROOT . 'module/jump.php';
		exit;
	}

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	if (!isset($GLOBALS['JOE_USER'])) {
		Typecho_Widget::widget('Widget_User')->to($user);
		$GLOBALS['JOE_USER'] = $user;
		if ($user->hasLogin()) {
			if (!defined('USER_ID')) define('USER_ID', $user->uid);
		} else {
			$cookiesid = isset($_COOKIE['userid']) ? $_COOKIE['userid'] : null;
			if ((!<span class="math-inline">cookiesid\) \|\| \(\!preg\_match\('/^\[0\-9a\-z\]\{32\}</span>/i', $cookiesid))) {
				$cookiesid = md5(uniqid(mt_rand(), 1) . time());
				setcookie('userid', $cookiesid, time() + 94672800, '/'); // 游客用户ID存储三年
			}
			if (!defined('USER_ID')) define('USER_ID', $cookiesid);
		}
	}

	/* 主题开放API 路由规则 */
	if (strpos($self->request->getPathInfo(), "/joe/api") === 0) {
		switch ($self->request->routeType) {
			case 'publish_list':
				_getPost($self);
				break;
			case 'baidu_record':
				_getRecord($self);
				break;
			case 'baidu_push':
				_pushRecord($self);
				break;
			case 'bing_push':
				_pushBing($self);
				break;
			case 'handle_views':
				_handleViews($self);
				break;
			case 'handle_agree':
				_handleAgree($self);
				break;
			case 'wallpaper_type':
				_getWallpaperType($self);
				break;
			case 'wallpaper_list':
				_getWallpaperList($self);
				break;
			case 'maccms_list':
				_getMaccmsList($self);
				break;
			case 'huya_list':
				_getHuyaList($self);
				break;
			case 'server_status':
				_getServerStatus($self);
				break;
			case 'comment_lately':
				_getCommentLately($self);
				break;
			case 'article_filing':
				_getArticleFiling($self);
				break;
				// 提交友链
			case 'friend_submit':
				_friendSubmit($self);
				break;
			case 'statistics':
				_getstatistics($self);
				break;
			case 'meting':
				_Meting($self);
				break;
			case 'pay_cashier_modal':
				_payCashierModal($self);
				break;
			case 'initiate_pay':
				_initiatePay($self);
			case 'user_rewards_modal':
				_userRewardsModal($self);
			case 'check_pay':
				_checkPay($self);
				break;
		};
	}

	if (strpos($self->request->getPathInfo(), '/goto') === 0 && Helper::options()->JPostLinkRedirect == 'on') {
		(function () use ($self) {
			$self->response->setStatus(200);
			$location = Helper::options()->siteUrl;
			$link = base64_decode($self->request->url);
			$cid = $self->request->cid;
			if (is_numeric($cid)) {
				$db = Typecho_Db::get();
				$post = $db->fetchRow($db->select('text')->from('table.contents')->where('cid = ?', $cid));
				if (!empty($post['text']) && strpos($post['text'], $link) !== false) {
					$location = $link;
				}
			}
			require_once JOE_ROOT . '/module/goto.php';
			$self->response->throwContent('');
		})();
	}

	if (Helper::options()->JUser_Switch == 'on') {
		// 增加自定义登录页面
		if (strpos($self->request->getRequestUri(), 'user/login') !== false) {
			$self->response->setStatus(200);
			$self->setThemeFile('user/login.php');
		}
		// 增加自定义注册页面
		if (Helper::options()->allowRegister) {
			if (strpos($self->request->getRequestUri(), 'user/register') !== false) {
				$self->response->setStatus(200);
				$self->setThemeFile('user/register.php');
			}
		}
		// 增加自定义登录注册页面API
		if (strpos($self->request->getRequestUri(), 'user/api') !== false) {
			$self->response->setStatus(200);
			$self->setThemeFile('user/api.php');
		}
		// 增加用户找回密码页面
		if (Helper::options()->JUser_Forget == 'on') {
			if (strpos($self->request->getRequestUri(), 'user/forget') !== false) {
				$self->response->setStatus(200);
				$self->setThemeFile('user/forget.php');
			}
		}
	}

	/* 增加自定义SiteMap功能 */
	if (Helper::options()->JSiteMap && Helper::options()->JSiteMap !== 'off') {
		if (strpos($self->request->getRequestUri(), 'sitemap.xml') !== false) {
			$self->response->setStatus(200);
			$self->setThemeFile("module/sitemap.php");
		}
	}
}

/* 增加自定义字段 */
function themeFields($layout)
{

	$mode = new \Typecho\Widget\Helper\Form\Element\Select(
		'mode',
		array(
			'default' => '默认模式',
			'single' => '大图模式',
			'multiple' => '三图模式',
			'none' => '无图模式'
		),
		'default',
		'文章显示方式',
		'介绍：用于设置当前文章在首页和搜索页的显示方式 <br />
		 注意：独立页面该功能不会生效'
	);
	$layout->addItem($mode);

	$hide = new \Typecho\Widget\Helper\Form\Element\Select(
		'hide',
		['comment' => '评论可见', 'pay' => '付费可见', 'login' => '登录可见'],
		'comment',
		'隐藏内容模式',
		'可将隐藏内容设置为评论可见、付费可见、登录可见'
	);
	$layout->addItem($hide);

	$price = new \Typecho\Widget\Helper\Form\Element\Text(
		'price',
		NULL,
		'0.00',
		'隐藏内容付费金额',
		'设置为 0 则是免费资源，付费可见功能需要在 [主题设置=>付费设置] 处配置好您的支付信息后可用'
	);
	$price->setAttribute('style', 'display:none');
	$layout->addItem($price);

	$pay_box_position = new \Typecho\Widget\Helper\Form\Element\Select(
		'pay_box_position',
		array('top' => '文章内容顶部', 'bottom' => '文章内容底部', 'none' => '不显示'),
		'top',
		'付费阅读模块显示位置',
		'在文章页面中购买模块的显示位置'
	);
	$layout->addItem($pay_box_position);

	$pay_tag_background = new \Typecho\Widget\Helper\Form\Element\Select(
		'pay_tag_background',
		['yellow' => '渐变黄', 'blue' => '渐变蓝', 'cyan' => '渐变青', 'green' => '渐变绿', 'purple' => '渐变紫', 'red' => '渐变红', 'pink' => '渐变粉', 'vip1' => '豪华VIP', 'vip2' => '轻奢VIP', 'none' => '不显示'],
		'yellow',
		'付费阅读标签背景颜色',
		'<script>
			const payPriceInput = document.querySelector(\'input[name="fields[price]"]\').parentElement.parentElement.parentElement;
			const pay_box_position = document.querySelector(\'select[name="fields[pay_box_position]"]\').parentElement.parentElement.parentElement;
			const pay_tag_background = document.querySelector(\'select[name="fields[pay_tag_background]"]\').parentElement.parentElement.parentElement;
			if (document.querySelector(\'select[name="fields[hide]"]\').value === "pay") {
				pay_box_position.style.display = "table-row";
				pay_tag_background.style.display = "table-row";
				payPriceInput.style.display = "table-row";
			} else {
			 	pay_box_position.style.display = "none";
				pay_tag_background.style.display = "none";
				payPriceInput.style.display = "none";
			}
			document.querySelector(\'select[name="fields[hide]"]\').addEventListener("change", () => {
				if (document.querySelector(\'select[name="fields[hide]"]\').value === "pay") {
					pay_box_position.style.display = "table-row";
					pay_tag_background.style.display = "table-row";
					payPriceInput.style.display = "table-row";
				} else {
				 	pay_box_position.style.display = "none";
					pay_tag_background.style.display = "none";
					payPriceInput.style.display = "none";
				}
			});
		</script>'
	);
	$layout->addItem($pay_tag_background);

	$keywords = new \Typecho\Widget\Helper\Form\Element\
