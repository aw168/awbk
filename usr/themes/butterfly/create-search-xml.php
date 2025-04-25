<?php
/**
 * 生成搜索索引文件
 * 每当有新文章发布或更新时，可以运行此脚本生成最新的搜索索引
 */
if (!defined('__TYPECHO_ROOT_DIR__')) {
    define('__TYPECHO_ROOT_DIR__', dirname(__FILE__) . '/..');
}

// 获取Typecho应用实例
require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';
Typecho_Common::init();

// 获取应用配置
$options = Typecho_Widget::widget('Widget_Options');
$db = Typecho_Db::get();

// 获取所有已发布的文章和页面
$select = $db->select()
    ->from('table.contents')
    ->where('status = ?', 'publish')
    ->where('type = ? OR type = ?', 'post', 'page')
    ->order('created', Typecho_Db::SORT_DESC);

$contents = $db->fetchAll($select);

// 创建XML头部
$xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xmlContent .= '<search>' . PHP_EOL;

// 循环添加每篇文章到XML
foreach ($contents as $content) {
    // 获取文章详情
    $widget = new Typecho_Widget();
    $widget->row = $content;
    $widget->stack = array($content);
    $widget->parameter->type = $content['type'];
    $title = $content['title'];
    
    // 生成链接
    $permalink = $options->permalink;
    $permalink = str_replace(
        array('{cid}', '{slug}', '{category}', '{directory}', '{year}', '{month}', '{day}'),
        array($content['cid'], $content['slug'], '', '', date('Y', $content['created']), date('m', $content['created']), date('d', $content['created'])),
        $permalink
    );
    $permalink = rtrim($options->siteUrl, '/') . $permalink;
    
    // 清理内容
    $text = strip_tags($content['text']);
    $text = preg_replace('/\s+/', ' ', $text); // 去除多余空白字符
    $text = mb_substr($text, 0, 1000, 'UTF-8'); // 限制内容长度
    
    // 添加到XML
    $xmlContent .= '  <entry>' . PHP_EOL;
    $xmlContent .= '    <title>' . htmlspecialchars($title, ENT_XML1) . '</title>' . PHP_EOL;
    $xmlContent .= '    <content>' . htmlspecialchars($text, ENT_XML1) . '</content>' . PHP_EOL;
    $xmlContent .= '    <url>' . htmlspecialchars($permalink, ENT_XML1) . '</url>' . PHP_EOL;
    $xmlContent .= '  </entry>' . PHP_EOL;
}

// 结束XML
$xmlContent .= '</search>';

// 保存到文件
$searchXmlPath = __DIR__ . '/search.xml';
file_put_contents($searchXmlPath, $xmlContent);

// 输出结果
if (php_sapi_name() === 'cli') {
    echo "已生成搜索索引文件: {$searchXmlPath}" . PHP_EOL;
    echo "包含 " . count($contents) . " 篇文章。" . PHP_EOL;
} else {
    header('Content-Type: application/xml');
    echo $xmlContent;
} 