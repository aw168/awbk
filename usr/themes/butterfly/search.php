<?php
/**
 * 搜索结果模板
 *
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<div id="content-outer">
  <div class="layout" id="content-inner">
    <div id="archive">
      <div class="archive-header">
        <div class="archive-page-counter">
          <?php $this->archiveTitle(array(
            'search' => _t('包含关键字 %s 的文章'),
          ), '', ''); ?>
        </div>
        <div class="search-header">
          <div class="search-page-info">
            共找到 <?php echo $this->getTotal(); ?> 篇文章
          </div>
        </div>
      </div>
      
      <?php if ($this->have()): ?>
      <div class="recent-posts category_ui" id="recent-posts">
        <?php 
        $coverIndex = 1;
        while ($this->next()):
          if($this->options->coverPosition === 'cross'){
            $sideClass = ($coverIndex % 2 == 0) ? 'right' : 'left';
          }else{
            $sideClass = $this->options->coverPosition;
          }
        ?>
        <div class="recent-post-item">
          <?php if (noCover($this)): ?>
          <wehao class="post_cover <?php echo $sideClass; ?>">
            <a href="<?php $this->permalink() ?>">
              <img class="post-bg" data-lazy-src="<?php echo get_ArticleThumbnail($this); ?>" src="<?php echo GetLazyLoad() ?>" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'">
            </a>
          </wehao>
          <?php endif; ?>
          
          <div class="recent-post-info<?php echo noCover($this) ? '' : ' no-cover'; ?>">
            <a class="article-title" href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
            <div class="article-meta-wrap">
              <?php $this->sticky(); ?>
              <span class="post-meta-date">
                <i class="far fa-calendar-alt"></i>
                <?php _e('发表于 '); ?> <?php $this->date(); ?>
              </span>
              
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
              <?php summaryContent($this);
              echo '<br><br><a href="', $this->permalink(), '" title="', $this->title(), '">阅读全文...</a>';
              ?>
            </div>
          </div>
        </div>
        <?php
          if (noCover($this)) {
            $coverIndex++;
          }
        endwhile;
        ?>
        
        <nav id="pagination">
          <?php $this->pageNav('<i class="fas fa-chevron-left fa-fw"></i>', '<i class="fas fa-chevron-right fa-fw"></i>', 1, '...', ['wrapTag' => 'div', 'wrapClass' => 'pagination', 'itemTag' => '', 'prevClass' => 'extend prev', 'nextClass' => 'extend next', 'currentClass' => 'page-number current']); ?>
        </nav>
      </div>
      <?php else: ?>
      <div class="search-no-result">
        <h3><?php _e('没有找到相关内容'); ?></h3>
        <p><?php _e('请尝试使用其他关键词进行搜索'); ?></p>
      </div>
      <?php endif; ?>
    </div>
    
    <?php $this->need('sidebar.php'); ?>
  </div>
</div>

<?php $this->need('footer.php'); ?> 