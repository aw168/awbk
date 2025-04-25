<?php
/**
 * 自动检查并生成搜索索引文件
 */
$searchXmlPath = __DIR__ . '/search.xml';
$searchXmlAgeThreshold = 24 * 60 * 60; // 24小时的秒数

// 检查search.xml文件是否存在或超过更新阈值
if (!file_exists($searchXmlPath) || (time() - filemtime($searchXmlPath)) > $searchXmlAgeThreshold) {
    try {
        $searchPhpPath = __DIR__ . '/search.php';
        if (file_exists($searchPhpPath)) {
            // 在后台运行生成搜索索引
            if (function_exists('exec')) {
                exec("php {$searchPhpPath} > {$searchXmlPath} 2>/dev/null &");
            } else {
                // 如果exec函数不可用，直接通过HTTP请求生成
                $searchUrl = rtrim(Helper::options()->siteUrl, '/') . '/search.php';
                $ch = curl_init($searchUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5秒超时
                $result = curl_exec($ch);
                curl_close($ch);
                
                if ($result) {
                    file_put_contents($searchXmlPath, $result);
                }
            }
        }
    } catch (Exception $e) {
        // 仅记录错误，不中断页面加载
        error_log("自动生成搜索索引失败: " . $e->getMessage());
    }
}

/**
 * <span>主题最新版本：<span id="latest">获取中...</span><script>
fetch('https://ty.wehao.org')
  .then(res => {
    if (!res.ok) throw new Error('Network response was not ok');
    return res.json();
  })
  .then(data => {
    if (data && data.ver) {
      document.getElementById("latest").textContent = data.ver;
    } else {
      document.getElementById("latest").textContent = "无法获取";
    }
  })
  .catch(error => {
    console.error('获取版本信息失败:', error);
    document.getElementById("latest").textContent = "获取失败";
  });
</script></span>
 * 这是 Typecho 版本的 butterfly 主题
 * 主题为移植至Typecho，你可以替换原butterfly主题的index.css文件
 * 当前适配 hexo-butterfly 4.6.0
 * <a href="https://www.wehaox.com">个人网站</a> | <a href="https://blog.wehaox.com/archives/typecho-butterfly.html">主题使用文档</a>
 * @package Typecho-Butterfly
 * @author b站:wehao-
 * @version 1.7.9
 * @link https://space.bilibili.com/34174433
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/** 文章置顶 */
$sticky = $this->options->sticky_cids;
if ($sticky && $this->is('index') || $this->is('front')) {
    $sticky_cids = explode(',', strtr($sticky, ' ', ',')); //分割文本 
    $sticky_html = "<span class='article-meta'><i class='fas fa-thumbtack article-meta__icon sticky'></i><span class='sticky'>置顶 </span><span class='article-meta__separator'>|</span></span>";
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    $isPostgreSQL = ($dbType == 'Pgsql' || $dbType == 'Postgresql' || $dbType == 'Pdo' && strpos($db->getAdapterName(), 'Pgsql') !== false);
    
    try {
        $select1 = $this->select()->where('type = ?', 'post');
        $select2 = $this->select()->where('type = ? AND status = ? AND created < ?', 'post', 'publish', time());
        $this->row = [];
        $this->stack = [];
        $this->length = 0;
        $order = '';
        
        // 针对不同数据库使用不同的查询方式
        if ($isPostgreSQL) {
            // PostgreSQL的case语句语法
            $case_stmt = 'CASE "cid" ';
            foreach ($sticky_cids as $i => $cid) {
                if ($i == 0) $select1->where('cid = ?', $cid);
                else $select1->orWhere('cid = ?', $cid);
                $case_stmt .= "WHEN $cid THEN $i ";
                $select2->where('cid != ?', $cid);
            }
            $case_stmt .= 'ELSE 999 END';
            
            if ($sticky_cids) $select1->order('', $case_stmt);
        } else {
            // MySQL的case语句语法
            foreach ($sticky_cids as $i => $cid) {
                if ($i == 0) $select1->where('cid = ?', $cid);
                else $select1->orWhere('cid = ?', $cid);
                $order .= " when $cid then $i";
                $select2->where('table.contents.cid != ?', $cid);
            }
            if ($order) $select1->order('', "(case cid$order else 999 end)");
        }
        
        if ($this->_currentPage == 1) {
            foreach ($db->fetchAll($select1) as $sticky_post) {
                $sticky_post['sticky'] = $sticky_html;
                $this->push($sticky_post);
            }
        }
        
        $uid = $this->user->uid; //登录时，显示用户各自的私密文章
        if ($uid) {
            if ($isPostgreSQL) {
                $select2->orWhere('author_id = ? AND status = ?', $uid, 'private');
            } else {
                $select2->orWhere('authorId = ? AND status = ?', $uid, 'private');
            }
        }
        
        if ($isPostgreSQL) {
            // 在PostgreSQL中确保列名使用双引号
            $sticky_posts = $db->fetchAll($select2->order('"created"', Typecho_Db::SORT_DESC)->page($this->_currentPage, $this->parameter->pageSize));
        } else {
            $sticky_posts = $db->fetchAll($select2->order('table.contents.created', Typecho_Db::SORT_DESC)->page($this->_currentPage, $this->parameter->pageSize));
        }
        
        foreach ($sticky_posts as $sticky_post) $this->push($sticky_post); //压入列队
        $this->setTotal($this->getTotal() - count($sticky_cids)); //置顶文章不计算在所有文章内
    } catch (Exception $e) {
        // 当查询出错时记录错误信息
        error_log("置顶文章查询错误: " . $e->getMessage());
    }
}
?>
<?php $this->need('header.php'); ?>
<main class="layout" id="content-inner">
    <div class="post-card-grid" id="recent-posts">
        <?php
        if ($this->options->googleadsense != "") :
            $i = 1;
            if ($this->options->pageSize <= 5) {
                $k = $m = $g = 3;
            } else if ($this->options->pageSize == 10) {
                $k = rand(3, 4);
                $m = rand(6, 8);
                $g = rand(10, 12);
            } else if ($this->options->pageSize > 5 && $this->options->pageSize < 10) {
                $k = $m = $g = 4;
            }
        endif;
        while ($this->next()) :
            if ($this->options->googleadsense != "") :
                if ($i == $k || $i == $m || $i == $g) {
        ?>
                    <div class="post-card-item ads-wrap">
                        <ins class="adsbygoogle" style="display:block;height:200px;width:100%;" data-ad-format="fluid" data-ad-client="<?php $this->options->googleadsense(); ?>"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
            <?php
                    $i++;
                }
                $i++;
            endif;
            ?>
            <div class="post-card-item" data-aos="zoom-in-up" data-aos-easing="ease-out" data-aos-duration="800">
                 <div class="post-card-image-wrap">
                     <a href="<?php $this->permalink() ?>" class="post-card-image-link">
                         <?php if (noCover($this)) : ?>
                            <img class="post-card-image" loading="lazy" data-lazy-src="<?php echo get_ArticleThumbnail($this); ?>" src="<?php echo GetLazyLoad() ?>" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" alt="<?php $this->title() ?>">
                         <?php else: ?>
                            <div class="post-card-image placeholder"></div> 
                         <?php endif; ?>
                         <?php if($this->sticky): ?>
                             <div class="post-card-sticky-tag">置顶</div>
                         <?php endif; ?>
                     </a>
                 </div>
                 <div class="post-card-content">
                     <div class="post-card-meta">
                         <span class="post-card-category">
                            <i class="fas fa-inbox"></i>
                            <?php $this->category(', '); ?>
                         </span>
                         <span class="post-card-date">
                            <i class="far fa-calendar-alt"></i>
                            <?php $this->date('Y-m-d'); ?>
                         </span>
                     </div>
                     <a class="post-card-title" href="<?php $this->permalink() ?>" title="<?php $this->title() ?>"><?php $this->title() ?></a>
                     <div class="post-card-excerpt">
                         <?php $this->excerpt(70, '...'); ?>
                     </div>
                 </div>
            </div>
        <?php endwhile; ?>
    </div>
    <nav id="pagination">
        <?php $this->pageNav('<i class="fas fa-chevron-left fa-fw"></i>', '<i class="fas fa-chevron-right fa-fw"></i>', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'pagination', 'itemTag' => '', 'prevClass' => 'extend prev', 'nextClass' => 'extend next', 'currentClass' => 'page-number current')); ?>
    </nav>
    <?php $this->need('sidebar.php'); ?>
</main>
<?php $this->need('footer.php'); ?>
<script>
// 站点信息和版本显示
function ver() {
    // console.log('fugu基于typecho-butterfly7.9修改');
}

// 全局错误处理
window.addEventListener('error', function(event) {
    // 检查是否为JSON解析错误
    if (event.message && event.message.includes('Unexpected token')) {
        // console.error('JSON解析错误已捕获，可能是API返回了非JSON格式的数据');
        event.preventDefault(); // 防止错误继续冒泡
    }
});

// 防止空对象引用错误
window.addEventListener('DOMContentLoaded', function() {
    // 修复常见的空对象引用错误
    const commonSelectors = {
        'subtitle': '#subtitle',
        'activity': '#activity',
        'search-button': '#search-button',
        'footer-wrap': '#footer-wrap'
    };
    
    // 检查并处理可能的空对象
    for (const [name, selector] of Object.entries(commonSelectors)) {
        const element = document.querySelector(selector);
        if (!element) {
            // console.log(`元素未找到: ${name} (${selector})`);
            
            // 为了防止其他脚本尝试在这个空元素上添加事件监听器，我们可以创建一个空元素
            if (name === 'search-button' || name === 'subtitle') {
                // 只为关键元素创建替代品
                const dummy = document.createElement('div');
                dummy.id = selector.substring(1); // 移除前导#
                dummy.style.display = 'none';
                dummy.setAttribute('data-dummy', 'true');
                
                // 添加一个空的addEventListener方法，防止错误
                dummy.addEventListener = function(event, handler) {
                    // console.log(`添加事件监听器失败，目标元素不存在: ${selector}, 事件: ${event}`);
                    // 不做任何事情，只是防止错误
                    return dummy;
                };
                
                // 添加到body中
                document.body.appendChild(dummy);
                // console.log(`为 ${selector} 创建了替代元素防止错误`);
            }
        }
    }
    
    // 保护所有可能的addEventListener调用
    const originalAddEventListener = Element.prototype.addEventListener;
    Element.prototype.addEventListener = function(type, listener, options) {
        try {
            return originalAddEventListener.call(this, type, listener, options);
        } catch (e) {
            // console.error(`addEventListener调用失败: ${e.message}`);
            // 返回this以支持链式调用
            return this;
        }
    };
    
    // 强制统一卡片大小和图片
    function uniformCards() {
        const cards = document.querySelectorAll('.post-card-item:not(.ads-wrap)');
        const cardHeight = 400; // 卡片固定高度
        const imageHeight = 200; // 图片固定高度
        
        cards.forEach(card => {
            // 强制卡片高度
            card.style.height = cardHeight + 'px';
            card.style.maxHeight = cardHeight + 'px';
            card.style.minHeight = cardHeight + 'px';
            
            // 强制图片区域高度
            const imageWrap = card.querySelector('.post-card-image-wrap');
            if (imageWrap) {
                imageWrap.style.height = imageHeight + 'px';
                imageWrap.style.maxHeight = imageHeight + 'px';
                imageWrap.style.minHeight = imageHeight + 'px';
                imageWrap.style.overflow = 'hidden';
                
                // 确保图片正确裁剪
                const image = imageWrap.querySelector('.post-card-image');
                if (image) {
                    image.style.width = '100%';
                    image.style.height = '100%';
                    image.style.objectFit = 'cover';
                    image.style.objectPosition = 'center center';
                }
            }
            
            // 强制内容区域高度
            const content = card.querySelector('.post-card-content');
            if (content) {
                content.style.height = (cardHeight - imageHeight) + 'px';
                content.style.maxHeight = (cardHeight - imageHeight) + 'px';
                content.style.minHeight = (cardHeight - imageHeight) + 'px';
                content.style.overflow = 'hidden';
            }
        });
    }
    
    // 页面加载后执行
    uniformCards();
    
    // 在图片加载完成后再次执行，确保所有卡片都有正确大小
    window.addEventListener('load', uniformCards);
    
    // 如果有懒加载图片，监听它们的加载完成事件
    document.querySelectorAll('.post-card-image[data-lazy-src]').forEach(img => {
        img.addEventListener('load', uniformCards);
    });
});
</script>