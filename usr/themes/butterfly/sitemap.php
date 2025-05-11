<?php
/**
 * 站点地图生成
 * 
 * @package sitemap
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    define('__TYPECHO_ROOT_DIR__', dirname(dirname(__FILE__)));
    require_once __TYPECHO_ROOT_DIR__ . '/config.inc.php';
    $db = Typecho_Db::get();
    Typecho_Widget::widget('Widget_Options')->to($options);
}

// 设置内容类型为XML
header("Content-Type: application/xml; charset=utf-8");

// 获取站点URL和数据库对象
$siteUrl = isset($options) ? $options->siteUrl : Helper::options()->siteUrl;
$db = Typecho_Db::get();

// 构建XML头部
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// 添加首页
echo "\t<url>\n";
echo "\t\t<loc>{$siteUrl}</loc>\n";
echo "\t\t<priority>1.0</priority>\n";
echo "\t\t<changefreq>daily</changefreq>\n";
echo "\t\t<lastmod>" . date('c', time()) . "</lastmod>\n";
echo "\t</url>\n";

// 获取所有文章
$articles = $db->fetchAll($db->select()->from('table.contents')
    ->where('table.contents.status = ?', 'publish')
    ->where('table.contents.type = ?', 'post')
    ->where('table.contents.created < ?', time())
    ->order('table.contents.created', Typecho_Db::SORT_DESC));

// 添加文章链接
foreach ($articles as $article) {
    $widget = Widget_Abstract_Contents::alloc();
    $article = $widget->push($article);
    $permalink = $article['permalink'];
    $created = date('c', $article['created']);
    $modified = $article['modified'] ? date('c', $article['modified']) : $created;
    
    echo "\t<url>\n";
    echo "\t\t<loc>{$permalink}</loc>\n";
    echo "\t\t<priority>0.8</priority>\n";
    echo "\t\t<changefreq>weekly</changefreq>\n";
    echo "\t\t<lastmod>{$modified}</lastmod>\n";
    echo "\t</url>\n";
}

// 获取所有独立页面
$pages = $db->fetchAll($db->select()->from('table.contents')
    ->where('table.contents.status = ?', 'publish')
    ->where('table.contents.type = ?', 'page')
    ->where('table.contents.created < ?', time())
    ->order('table.contents.created', Typecho_Db::SORT_DESC));

// 添加独立页面链接
foreach ($pages as $page) {
    $widget = Widget_Abstract_Contents::alloc();
    $page = $widget->push($page);
    $permalink = $page['permalink'];
    $created = date('c', $page['created']);
    $modified = $page['modified'] ? date('c', $page['modified']) : $created;
    
    echo "\t<url>\n";
    echo "\t\t<loc>{$permalink}</loc>\n";
    echo "\t\t<priority>0.7</priority>\n";
    echo "\t\t<changefreq>monthly</changefreq>\n";
    echo "\t\t<lastmod>{$modified}</lastmod>\n";
    echo "\t</url>\n";
}

// 获取所有分类
$categories = NULL;
$widget = Widget_Metas_Category_List::alloc();

// 添加分类链接
while ($widget->next()) {
    $permalink = $widget->permalink;
    $updated = date('c', time()); // 分类可能经常更新
    
    echo "\t<url>\n";
    echo "\t\t<loc>{$permalink}</loc>\n";
    echo "\t\t<priority>0.6</priority>\n";
    echo "\t\t<changefreq>weekly</changefreq>\n";
    echo "\t\t<lastmod>{$updated}</lastmod>\n";
    echo "\t</url>\n";
}

// 获取所有标签
$tags = NULL;
$widget = Widget_Metas_Tag_Cloud::alloc('sort=count&ignoreZeroCount=1&desc=1');

// 添加标签链接
while ($widget->next()) {
    $permalink = $widget->permalink;
    $updated = date('c', time()); // 标签可能经常更新
    
    echo "\t<url>\n";
    echo "\t\t<loc>{$permalink}</loc>\n";
    echo "\t\t<priority>0.5</priority>\n";
    echo "\t\t<changefreq>monthly</changefreq>\n";
    echo "\t\t<lastmod>{$updated}</lastmod>\n";
    echo "\t</url>\n";
}

// 结束XML
echo "</urlset>"; 