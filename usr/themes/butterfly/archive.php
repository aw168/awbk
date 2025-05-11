<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
/**  
    * 归档
    *  
    * @package custom  
    * @type page
    */
?>
<style>
/* 统一图标样式 */
.card-info-social-icons img,
.additional-links img {
    width: 36px !important;
    height: 36px !important;
    margin: 0 8px !important;
    transition: all 0.3s ease;
}

.card-info-social-icons a:hover img,
.additional-links a:hover img {
    transform: scale(1.2) !important;
}
</style>
<?php $this->need('page_header.php'); ?>
<main class="layout" id="content-inner">
    <?php if ($this->options->articleListStyle === 'card' || !$this->options->articleListStyle): ?>
    <!-- 卡片式布局 -->
    <div class="post-card-grid" id="recent-posts">
        <?php if ($this->have()): ?>
        <?php while ($this->next()): ?>
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
        <?php else: ?>
        <article class="post">
            <h2 class="post-title"><?php _e('没有找到内容'); ?></h2>
        </article>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <!-- 交互式/靠左式/靠右式布局 -->
    <div class="recent-posts category_ui" id="recent-posts">
        <?php if ($this->have()): ?>
        <?php 
        $coverIndex = 1;
        while ($this->next()):
            if($this->options->articleListStyle === 'interactive'){
                $sideClass = ($coverIndex % 2 == 0) ? 'right' : 'left';
            } else if($this->options->articleListStyle === 'left'){
                $sideClass = 'left';
            } else if($this->options->articleListStyle === 'right'){
                $sideClass = 'right';
            } else {
                $sideClass = 'left'; // 默认靠左
            }
        ?>
           <div class="recent-post-item">
		   <?php if(noCover($this)): ?>  
        		<wehao class="post_cover <?php echo $sideClass; ?>">
             		<a href="<?php $this->permalink() ?>">
                	<img class="post-bg" data-lazy-src="<?php echo get_ArticleThumbnail($this);?>" src="<?php echo GetLazyLoad() ?>" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'"></a>
        		</wehao>
    		<?php endif ?>
              <div class="recent-post-info<?php echo noCover($this) ? '' : ' no-cover'; ?>">
			    <a  class="article-title" href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
			    <div class="article-meta-wrap">
			         <?php $this->sticky(); ?>
			   <span class="post-meta-date" style="display:none;">
			        <i class="far fa-calendar-alt"></i>
				    <?php _e('发表于  '); ?> <?php $this->date(); ?>
				    <time class="post-meta-date-created" datetime="<?php $this->date('c'); ?>">
				    </time>
				</span>
				<i class="fas fa-history"></i>
				<span class="article-meta-label">更新于</span>
				  <?php echo date('Y-m-d', $this->modified); ?>
				<time class="post-meta-date-updated" datetime="<?php echo date('Y-m-d', $this->modified); ?>" title="更新于 ">
				</time>
				<span class="article-meta">
				    <span class="article-meta__separator">|</span>
				    <i class="fas fa-inbox article-meta__icon"></i>
				  <span class="post-meta-date">
				    <?php _e('分类: '); ?>
				    <?php $this->category(' '); ?>
				</span>
				    	<span class="article-meta__separator">|</span>
			     <span class="post-meta-date" itemprop="author">
				    <?php _e('作者: '); ?>
				<a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author">
				    <?php $this->author(); ?>
				    </a>
				</span>
					<span class="article-meta__separator">|</span>
				<i class="fas fa-comments"></i>
			     <span class="post-meta-date" itemprop="interactionCount">
				    <a itemprop="discussionUrl" href="<?php $this->permalink(); ?>#comments">
				        <?php $this->commentsNum('0条评论', '1 条评论', '%d 条评论'); ?>
				    </a>
				</span>
				</span>
			    </div>
			    <div class="content">
			    <?php if ($this->fields->excerpt && $this->fields->excerpt != '') {
    echo $this->fields->excerpt;
} else {
    echo $this->excerpt(130);
}
                   echo '<br><br><a href="',$this->permalink(),'" title="',$this->title(),'">阅读全文...</a>';
                    ?>
                   </div>
            </div>
        </div>
    	<?php 
        if (noCover($this)) {
          $coverIndex++;
        }
        endwhile; ?>
        <?php else: ?>
            <article class="post">
                <h2 class="post-title"><?php _e('没有找到内容'); ?></h2>
            </article>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <nav id="pagination">
        <?php $this->pageNav('<i class="fas fa-chevron-left fa-fw"></i>', '<i class="fas fa-chevron-right fa-fw"></i>', 1, '...', ['wrapTag' => 'div', 'wrapClass' => 'pagination', 'itemTag' => '', 'prevClass' => 'extend prev', 'nextClass' => 'extend next', 'currentClass' => 'page-number current']); ?>
    </nav>
    <?php $this->need('sidebar.php'); ?>
</main>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/wehao.js?v1.4'); ?>"></script>
<script>
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
document.addEventListener('DOMContentLoaded', uniformCards);
window.addEventListener('load', uniformCards);
</script>
<script>
// 修复靠左/靠右式布局问题
function fixArticleLayout() {
    const listStyle = '<?php echo $this->options->articleListStyle; ?>';
    if (listStyle === 'left' || listStyle === 'right') {
        // 强制设置所有post_cover的位置
        const coverClass = listStyle === 'left' ? 'left' : 'right';
        document.querySelectorAll('.post_cover').forEach(cover => {
            // 移除可能存在的left或right类
            cover.classList.remove('left', 'right');
            // 添加指定的类
            cover.classList.add(coverClass);
        });
    }
}

// 页面加载时执行
document.addEventListener('DOMContentLoaded', fixArticleLayout);

// 当页面完全加载后再次执行，确保样式不被其他脚本覆盖
window.addEventListener('load', fixArticleLayout);

// 每隔500毫秒检查一次，确保其他JS不会覆盖我们的样式（执行10次后停止）
let checkCount = 0;
const checkInterval = setInterval(() => {
    fixArticleLayout();
    checkCount++;
    if (checkCount >= 10) clearInterval(checkInterval);
}, 500);
</script>
<?php $this->need('footer.php'); ?>