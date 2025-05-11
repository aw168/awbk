<?php
/**
 * 站点地图页面模板
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header_com.php');
?>

<body style="zoom: 1;">
    <div class="page" id="body-wrap">
        <header class="not-top-img" id="page-header">
            <?php $this->need('public/nav.php'); ?>
        </header>
        <main class="layout" id="content-inner">
            <div id="post">
                <div class="post-content">
                    <div class="post-inner">
                        <h1 class="post-title">站点地图</h1>
                        <div class="post-description">
                            <p>这个页面提供了本站的站点地图和其他便于搜索引擎收录的资源。</p>
                        </div>
                        
                        <div class="sitemap-section">
                            <h2>XML 站点地图</h2>
                            <p>适用于搜索引擎的XML格式站点地图:</p>
                            <ul>
                                <li><a href="<?php $this->options->siteUrl(); ?>sitemap.xml" target="_blank">站点地图 (XML格式)</a></li>
                            </ul>
                            
                            <h2>RSS 订阅</h2>
                            <p>通过RSS订阅获取本站更新:</p>
                            <ul>
                                <li><a href="<?php $this->options->feedUrl(); ?>" target="_blank">RSS订阅</a></li>
                            </ul>
                            
                            <h2>Robots.txt</h2>
                            <p>爬虫规则文件:</p>
                            <ul>
                                <li><a href="<?php $this->options->siteUrl(); ?>robots.txt" target="_blank">Robots.txt</a></li>
                            </ul>
                        </div>
                        
                        <div class="sitemap-section">
                            <h2>站点内容导航</h2>
                            
                            <h3>文章分类</h3>
                            <ul class="sitemap-categories">
                                <?php $this->widget('Widget_Metas_Category_List')->to($categories); ?>
                                <?php while ($categories->next()): ?>
                                <li>
                                    <a href="<?php $categories->permalink(); ?>"><?php $categories->name(); ?> (<?php $categories->count(); ?>)</a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                            
                            <h3>页面</h3>
                            <ul class="sitemap-pages">
                                <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                                <?php while($pages->next()): ?>
                                <li>
                                    <a href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                            
                            <h3>最近文章</h3>
                            <ul class="sitemap-recent-posts">
                                <?php $this->widget('Widget_Contents_Post_Recent', 'pageSize=10')->to($recent); ?>
                                <?php while($recent->next()): ?>
                                <li>
                                    <a href="<?php $recent->permalink(); ?>"><?php $recent->title(); ?></a>
                                    <span class="post-date">(<?php $recent->date('Y-m-d'); ?>)</span>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                            
                            <h3>标签云</h3>
                            <div class="sitemap-tag-cloud">
                                <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=count&ignoreZeroCount=1&desc=1&limit=50')->to($tags); ?>
                                <?php if($tags->have()): ?>
                                <?php while ($tags->next()): ?>
                                <a href="<?php $tags->permalink(); ?>" class="tag-item" style="font-size: <?php echo 14 + floor($tags->count / 5) ?>px;">
                                    <?php $tags->name(); ?> (<?php $tags->count(); ?>)
                                </a>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <p>没有标签</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php $this->need('footer.php'); ?>
    </div>
</body>

<style>
.post-inner {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    background: var(--card-bg);
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.post-title {
    font-size: 28px;
    text-align: center;
    margin-bottom: 20px;
    position: relative;
}

.post-description {
    text-align: center;
    margin-bottom: 40px;
    color: var(--font-2);
    font-size: 16px;
}

.sitemap-section {
    margin-bottom: 40px;
}

.sitemap-section h2 {
    border-bottom: 2px solid var(--btn-bg);
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: 22px;
}

.sitemap-section h3 {
    margin: 25px 0 15px;
    font-size: 18px;
    color: var(--font-color);
    position: relative;
    padding-left: 15px;
}

.sitemap-section h3:before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 5px;
    height: 18px;
    background: var(--btn-bg);
    border-radius: 3px;
}

.sitemap-section ul {
    padding-left: 20px;
}

.sitemap-section li {
    margin-bottom: 10px;
    list-style-type: circle;
}

.sitemap-section a {
    color: var(--font-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

.sitemap-section a:hover {
    color: var(--btn-bg);
    text-decoration: underline;
}

.post-date {
    font-size: 14px;
    color: var(--font-2);
    margin-left: 8px;
}

.sitemap-tag-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.tag-item {
    padding: 5px 12px;
    background: rgba(125, 125, 125, 0.1);
    border-radius: 20px;
    transition: all 0.3s ease;
    color: var(--font-color);
    text-decoration: none;
}

.tag-item:hover {
    background: var(--btn-bg);
    color: white;
    transform: translateY(-2px);
    text-decoration: none !important;
}

@media (max-width: 768px) {
    .post-inner {
        padding: 20px 15px;
    }
    
    .post-title {
        font-size: 24px;
    }
    
    .sitemap-section h2 {
        font-size: 20px;
    }
    
    .sitemap-section h3 {
        font-size: 16px;
    }
}
</style>

</html> 