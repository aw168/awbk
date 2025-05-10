<?php
/**
 * robots.txt生成
 * 
 * @package robots
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 设置内容类型为文本
header("Content-Type: text/plain");

// 获取站点URL
$siteUrl = Helper::options()->siteUrl;

// 输出robots.txt内容
echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /admin/\n";
echo "Disallow: /search/\n";
echo "Disallow: /feed/\n";

// 添加sitemap地址
echo "\n";
echo "Sitemap: {$siteUrl}sitemap.xml\n";
echo "Sitemap: {$siteUrl}index.php/sitemap.xml\n"; 