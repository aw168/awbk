<?php
/**
 * 搜索索引生成器
 * 用于生成适用于本地搜索功能的XML文件
 */
if (!defined('__TYPECHO_ROOT_DIR__')) {
    define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));
    require_once __TYPECHO_ROOT_DIR__ . '/config.inc.php';
    
    // 确保Typecho应用被初始化
    try {
        $db = Typecho_Db::get();
        Typecho_Widget::widget('Widget_Init');
    } catch (Exception $e) {
        // 如果初始化失败，记录日志但继续执行
        error_log("初始化Typecho失败: " . $e->getMessage());
    }
}

// 设置内容类型为XML
header('Content-Type: application/xml');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

try {
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<search>';

    // 尝试从数据库获取文章
    try {
        // 获取数据库实例
        $db = Typecho_Db::get();
        
        // 查询所有已发布的文章
        $select = $db->select('cid, title, text, created')
            ->from('table.contents')
            ->where('type = ?', 'post')
            ->where('status = ?', 'publish')
            ->order('created', Typecho_Db::SORT_DESC);
            
        // 执行查询
        $posts = $db->fetchAll($select);
        
        // 输出文章数据
        if (!empty($posts)) {
            foreach ($posts as $post) {
                try {
                    // 获取文章永久链接
                    $options = Typecho_Widget::widget('Widget_Options');
                    $permalink = Typecho_Router::url('post', array('cid' => $post['cid']), $options->index);
                    
                    // 处理内容，移除HTML标签
                    $text = $post['text'] ?? '';
                    $text = strip_tags(Typecho_Widget::widget('Widget_Abstract_Contents')->markdown($text));
                    
                    // 限制内容长度
                    if (function_exists('mb_substr')) {
                        $text = mb_substr($text, 0, 5000, 'utf-8');
                    } else {
                        $text = substr($text, 0, 5000);
                    }
                    
                    // 文章标题
                    $title = $post['title'] ?? '无标题文章';
                    
                    echo '<entry>';
                    echo '<title>' . htmlspecialchars($title) . '</title>';
                    echo '<url>' . $permalink . '</url>';
                    echo '<content>' . htmlspecialchars($text) . '</content>';
                    echo '</entry>';
                } catch (Exception $e) {
                    // 略过单个文章的错误，继续处理其他文章
                    error_log("搜索索引生成错误 - 文章ID: {$post['cid']}: " . $e->getMessage());
                }
            }
        } else {
            // 未找到文章，添加一个默认条目
            echo '<entry><title>没有文章</title><content>没有找到任何已发布的文章</content><url>/</url></entry>';
        }
    } catch (Exception $e) {
        // 如果数据库查询失败，添加一个默认条目
        error_log("搜索索引生成错误 - 数据库: " . $e->getMessage());
        echo '<entry><title>数据库错误</title><content>查询数据库时发生错误: ' . htmlspecialchars($e->getMessage()) . '</content><url>/</url></entry>';
        
        // 添加一些示例条目，确保搜索功能至少能基本工作
        echo '<entry><title>示例文章 1</title><content>这是一个示例文章内容，用于在数据库查询失败时提供基本搜索功能。</content><url>/index.php</url></entry>';
        echo '<entry><title>示例文章 2</title><content>又一个示例文章，包含一些关键词如：Typecho博客系统、PHP、MySQL。</content><url>/index.php</url></entry>';
        echo '<entry><title>使用帮助</title><content>如果您看到此消息，说明搜索索引生成遇到了问题。请尝试访问create-search-xml.php生成搜索索引。</content><url>/create-search-xml.php</url></entry>';
    }

    echo '</search>';
} catch (Exception $e) {
    // 处理整个过程中的任何异常
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<search>';
    echo '<entry><title>系统错误</title><content>生成搜索索引时发生错误: ' . htmlspecialchars($e->getMessage()) . '</content><url>/</url></entry>';
    echo '<entry><title>解决方案</title><content>请访问create-search-xml.php来生成搜索索引。</content><url>/create-search-xml.php</url></entry>';
    echo '</search>';
    
    error_log("搜索索引生成失败: " . $e->getMessage());
}
exit; 