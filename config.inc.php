<?php
// site root path
define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));

// plugin directory (relative path)
define('__TYPECHO_PLUGIN_DIR__', '/usr/plugins');

// theme directory (relative path)
define('__TYPECHO_THEME_DIR__', '/usr/themes');

// admin directory (relative path)
define('__TYPECHO_ADMIN_DIR__', '/admin/');

// register autoload
require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';

// init
\Typecho\Common::init();

// 配置数据库连接信息
$dbConfig = array (
    'host' => 'gateway01.us-west-2.prod.aws.tidbcloud.com',
    'port' => 4000,
    'user' => '2ActdKRgos3DVzV.root',
    'password' => 'DAACmx7H3a03R3ve',
    'charset' => 'utf8mb4',
    'database' => 'test',
    'engine' => 'InnoDB',
    'sslCa' => __TYPECHO_ROOT_DIR__ . '/isrgrootx1.pem', // 请将 isrgrootx1.pem 文件放在 index.php 所在的目录，并使用此路径
    'sslVerify' => true,
);

// 检查 SSL CA 文件是否存在
if (!file_exists($dbConfig['sslCa'])) {
    die('错误：SSL CA 证书文件 ' . $dbConfig['sslCa'] . ' 不存在！请上传该文件到服务器。');
}

// config db
$db = new \Typecho\Db('Pdo_Mysql', 'typecho_');
$db->addServer($dbConfig, \Typecho\Db::READ | \Typecho\Db::WRITE);

try {
    \Typecho\Db::set($db);
    // 可选：测试数据库连接是否成功
    $db->query('SELECT 1');
    // echo '数据库连接成功！';
} catch (\Typecho\Db\Exception $e) {
    die('Error establishing a database connection: ' . $e->getMessage());
}

// 可以在这里添加 Typecho 的后续初始化代码，例如处理请求等
