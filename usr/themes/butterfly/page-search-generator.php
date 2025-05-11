<?php
/**
 * 搜索索引生成器页面
 * 
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 获取当前主题URL
$themeUrl = Helper::options()->themeUrl;
$siteUrl = Helper::options()->siteUrl;

// 判断是否为管理员访问
$user = Typecho_Widget::widget('Widget_User');
if (!$user->hasLogin()) {
    // 重定向到登录页
    header('Location: ' . $siteUrl . 'admin/login.php');
    exit;
}

// 检测数据库类型
$db = Typecho_Db::get();
$adapter = $db->getAdapterName();
$isPostgreSQL = (strpos($adapter, 'Pgsql') !== false);

// 处理表单提交
$message = '';
$error = '';
if (isset($_POST['generate']) && $_POST['generate'] == 'true') {
    try {
        // 调用搜索索引生成器
        $searchXmlContent = file_get_contents($siteUrl . 'search.php');
        
        if (empty($searchXmlContent)) {
            throw new Exception('无法获取搜索索引内容，请确认search.php文件存在且可访问');
        }
        
        // 检查内容是否为有效的XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($searchXmlContent);
        if ($xml === false) {
            $xmlErrors = libxml_get_errors();
            libxml_clear_errors();
            throw new Exception('生成的内容不是有效的XML: ' . $xmlErrors[0]->message);
        }
        
        // 保存为search.xml文件
        $searchXmlPath = __TYPECHO_ROOT_DIR__ . '/search.xml';
        if (file_put_contents($searchXmlPath, $searchXmlContent)) {
            $message = '搜索索引已成功生成！文件保存在根目录的 search.xml';
        } else {
            throw new Exception('无法写入search.xml文件，请检查目录权限');
        }
    } catch (Exception $e) {
        $error = '生成索引时出错: ' . $e->getMessage();
    }
}

$this->need('header_com.php');
?>

<div id="body-wrap">
    <main class="layout" id="content-inner">
        <div id="page">
            <div class="article-container">
                <h1 class="article-title">搜索索引生成器</h1>
                
                <?php if ($message): ?>
                <div class="notification is-success" style="background-color: #48c774; color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="notification is-danger" style="background-color: #f14668; color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <div class="article-content">
                    <div style="background-color: #f5f5f5; padding: 20px; border-radius: 4px; margin-bottom: 20px;">
                        <h2>当前数据库信息</h2>
                        <p><strong>数据库类型:</strong> <?php echo $adapter; ?></p>
                        <p><strong>PostgreSQL兼容模式:</strong> <?php echo $isPostgreSQL ? '已启用' : '未启用'; ?></p>
                    </div>
                    
                    <p>这个页面用于生成本地搜索功能所需的索引文件。点击下方按钮生成搜索索引。</p>
                    <p>注意：生成过程可能需要一些时间，尤其是在文章数量较多的情况下。</p>
                    
                    <form method="post" action="">
                        <input type="hidden" name="generate" value="true">
                        <button type="submit" class="button" style="background-color: #3273dc; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">生成搜索索引</button>
                    </form>
                    
                    <h2>使用说明</h2>
                    <p>1. 搜索索引文件将保存为站点根目录下的 search.xml 文件</p>
                    <p>2. 每当添加新文章或修改现有文章后，应该重新生成索引</p>
                    <p>3. 生成的索引支持PostgreSQL和MySQL数据库</p>
                    
                    <h2>自动更新</h2>
                    <p>你也可以设置一个定时任务定期访问 <?php echo $siteUrl; ?>search.php 来自动更新索引</p>
                    
                    <h2>故障排除</h2>
                    <p>如果搜索功能无法正常工作，请尝试以下步骤：</p>
                    <ol>
                        <li>确保 search.xml 文件已生成并可以通过浏览器访问</li>
                        <li>检查浏览器控制台是否有 JavaScript 错误</li>
                        <li>对于PostgreSQL用户，确保数据库查询能正确处理字段名称</li>
                        <li>如果使用了CDN，确保 search.php 和 search.xml 文件未被缓存</li>
                    </ol>
                    
                    <p>如果问题仍然存在，您可以：</p>
                    <ul>
                        <li>检查网站错误日志</li>
                        <li>直接访问 <a href="<?php echo $siteUrl; ?>search.php" target="_blank">search.php</a> 查看输出是否为有效的XML</li>
                        <li>更新主题到最新版本</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<?php $this->need('footer.php'); ?> 