<?php
/**
 * 搜索数据生成器
 * 生成 search.xml 文件，包含所有文章的标题、URL和内容
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    // 当直接访问此文件时，加载 Typecho 环境
    require_once 'config.inc.php';
    
    // 获取主题目录
    $themeDir = __TYPECHO_ROOT_DIR__ . '/usr/themes/' . Helper::options()->theme;
    
    // 设置输出文件路径
    $outputFile = $themeDir . '/search.xml';
    
    // 获取后台管理地址
    $adminUrl = Helper::options()->adminUrl;
    
    // 生成并保存 search.xml
    if (generateSearchXml($outputFile)) {
        echo "成功生成搜索数据文件：" . $outputFile . "\n";
        echo "请确保该文件有可读权限。\n";
        echo "<p><a href='{$adminUrl}'>返回管理后台</a></p>";
    } else {
        echo "搜索数据文件生成失败，请检查权限设置。\n";
        echo "<p><a href='{$adminUrl}'>返回管理后台</a></p>";
    }
    
    exit;
}

/**
 * 生成搜索XML文件
 * 
 * @param string $outputFile 输出文件路径
 * @return bool 是否成功
 */
function generateSearchXml($outputFile = null) {
    // 如果未指定输出文件，使用默认路径
    if (null === $outputFile) {
        $themeDir = __TYPECHO_ROOT_DIR__ . '/usr/themes/' . Helper::options()->theme;
        $outputFile = $themeDir . '/search.xml';
    }
    
    // 创建XML文档
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    
    // 创建根元素
    $root = $dom->createElement('search');
    $dom->appendChild($root);
    
    // 获取所有公开文章
    $db = Typecho_Db::get();
    $articles = $db->fetchAll($db->select()
        ->from('table.contents')
        ->where('status = ?', 'publish')
        ->where('type = ?', 'post')
        ->order('created', Typecho_Db::SORT_DESC));
    
    // 创建上下文对象，用于构建URL
    $options = Helper::options();
    
    // 添加每篇文章
    foreach ($articles as $article) {
        // 将数据转换为内容对象
        $article = Typecho_Widget::widget('Widget_Abstract_Contents')->push($article);
        
        // 创建条目元素
        $entry = $dom->createElement('entry');
        
        // 添加标题
        $title = $dom->createElement('title');
        $title->appendChild($dom->createTextNode($article['title']));
        $entry->appendChild($title);
        
        // 添加URL
        $url = $dom->createElement('url');
        $url->appendChild($dom->createTextNode($article['permalink']));
        $entry->appendChild($url);
        
        // 获取文章内容并去除HTML标签
        $content = strip_tags($article['text']);
        // 移除多余空格
        $content = preg_replace('/\s+/', ' ', $content);
        
        // 添加内容
        $contentElem = $dom->createElement('content');
        $contentElem->appendChild($dom->createTextNode($content));
        $entry->appendChild($contentElem);
        
        // 将条目添加到根元素
        $root->appendChild($entry);
    }
    
    // 保存XML文件
    $result = $dom->save($outputFile);
    
    return $result !== false;
}

/**
 * 钩子函数：当文章保存时更新搜索数据
 */
function updateSearchXmlOnSave($contents, $edit) {
    // 仅在发布或更新文章时重新生成
    if ($edit->status === 'publish' && $edit->type === 'post') {
        generateSearchXml();
    }
    return $contents;
}

// 添加钩子，在文章保存时更新搜索数据
Typecho_Plugin::factory('Widget_Contents_Post_Edit')->finishPublish = 'updateSearchXmlOnSave';
Typecho_Plugin::factory('Widget_Contents_Post_Edit')->finishDelete = 'updateSearchXmlOnSave'; 