<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit; ?>
<div class="aside-content" id="aside-content">
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowAuthorInfo', $this->options->sidebarBlock)) : ?>
    <div class="card-widget card-info" data-aos="fade-left" data-aos-easing="ease-out" data-aos-duration="4000"
        data-aos-delay="500" data-aos-offset="200" data-aos-mirror="true">
        <div class="card-info-avatar is-center">
            <div class="avatar-img">
                <?php if ($this->options->cardImage): ?>
                    <img id="img_hover" data-lazy-src="<?php $this->options->cardImage() ?>" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" alt="card-img">
                <?php else: ?>
                    <img id="img_hover" data-lazy-src="https://awtc.pp.ua/1745514532958.png"
                    onerror="this.onerror=null;this.src='https://awtc.pp.ua/1745514532958.png'"
                    alt="card-img">
                <?php endif; ?>
                <script>
                const glitchText = () => {
                    const elem = document.querySelector('.cyberpunk');
                    if (!elem) return;
                    let originalText = elem.getAttribute('data-text');
                    let glitchedText = '';
                    for (let i = 0; i < originalText.length; i++) {
                        if (Math.random() > 0.8) {
                            const char = originalText[i];
                            glitchedText += char === char.toUpperCase() ? char.toLowerCase() : char.toUpperCase();
                        } else {
                            glitchedText += originalText[i];
                        }
                    }
                    elem.setAttribute('data-text', glitchedText);
                }

                setInterval(glitchText, 200);
                </script>
            </div>
            <div class="author-info__name cyberpunk glitched" data-text="<?php $this->author(); ?>">
                <?php $this->author(); ?>
            </div>
            <div class="author-info__description">
                <?php $this->options->author_description() ?>
            </div>
            <div class="additional-links sidebar-links">
                <a target="_BLANK" href="https://github.com/iawooo" title="GitHub主页"><img class="entered loading" src="/usr/themes/butterfly/img/github.svg" data-ll-status="loading"></a>
                <a target="_BLANK" href="https://t.me/AwcttBot" title="telegram"><img class="entered loading" src="/usr/themes/butterfly/img/telegram.svg" data-ll-status="loading"></a>
                <a href="mailto:iawooo@qq.com" title="邮箱"><img class="entered loading" src="/usr/themes/butterfly/img/mail.svg" data-ll-status="loading"></a>
            </div>
        </div>
        <div class="card-info-data">
            <div class="card-info-data site-data is-center">
                <a href="/index.php/archive.html">
                    <div class="headline">文章</div>
                    <div class="length-num">
                        <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
                        <?php $stat->publishedPostsNum() ?>
                    </div>
                </a>
                <a href="/index.php/tags.html">
                    <div class="headline">标签</div>
                    <div class="length-num">
                        <?php echo tagsNum(); ?>
                    </div>
                </a>
                <a href="/index.php/category-list.html">
                    <div class="headline">
                        分类
                    </div>
                    <div class="length-num">
                        <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
                        <?php $stat->categoriesNum() ?>
                    </div>
                </a>
            </div>
        </div>
        <!-- <a class="button--animated" id="card-info-btn" target="_blank" rel="noopener"
                href="<?php $this->options->author_site() ?>">
                <i class="fas fa-link">
                </i>
                <span>
                    <?php $this->options->author_site_description() ?>
                </span>
            </a> -->

        <?php if ($this->options->author_bottom != null) : ?>
        <div class="card-info-social-icons is-center">
            <?php $this->options->author_bottom() ?>
        </div>
        <?php elseif (!$this->options->author_bottom) : ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowAnnounce', $this->options->sidebarBlock)) : ?>
    <!-- <div class="card-widget card-announcement">
            <div class="item-headline">
                <i class="fas fa-bullhorn card-announcement-animation"></i><span>公告</span>
            </div>
            <div class="announcement_content">
                <?php $this->options->announcement() ?>
            </div>
        </div> -->
    <?php endif; ?>
    <?php if (!empty($this->options->AD)) : ?>
    <div class="card-widget">
        <div class="item-headline"><i class="fa-solid fa-rectangle-ad"></i><span>广告</span></div>
        <div>
            <?php $this->options->AD() ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sticky_layout">
        <!--微博热搜-->
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowWeiboHot', $this->options->sidebarBlock)) : ?>
        <div class="card-widget card-weibo wow animate__zoomIn" data-wow-duration="2s" data-wow-delay="200ms"
            data-wow-offset="30" data-wow-iteration="1"
            style="visibility: visible; animation-duration: 2s; animation-delay: 200ms; animation-iteration-count: 1; animation-name: zoomIn;">
            <div class="card-content">
                <div class="item-headline">
                    <i class="fab fa-weibo"></i>
                    <span>微博热搜</span>
                </div>
                <div id="weibo-container" style="width:100%;height:150px;font-size:95%">
                    <style>
                    .weibo-new {
                        background: #ff3852
                    }

                    .weibo-hot {
                        background: #ff9406
                    }

                    .weibo-jyzy {
                        background: #ffc000
                    }

                    .weibo-recommend {
                        background: #00b7ee
                    }

                    .weibo-adrecommend {
                        background: #febd22
                    }

                    .weibo-friend {
                        background: #8fc21e
                    }

                    .weibo-boom {
                        background: #bd0000
                    }

                    .weibo-topic {
                        background: #ff6f49
                    }

                    .weibo-topic-ad {
                        background: #4dadff
                    }

                    .weibo-boil {
                        background: #f86400
                    }

                    #weibo-container {
                        overflow-y: auto;
                        -ms-overflow-style: none;
                        scrollbar-width: none
                    }

                    #weibo-container::-webkit-scrollbar {
                        display: none
                    }

                    .weibo-list-item {
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                        flex-wrap: nowrap
                    }

                    .weibo-title {
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        margin-right: auto
                    }

                    .weibo-num {
                        float: right
                    }

                    .weibo-hotness {
                        display: inline-block;
                        padding: 0 6px;
                        transform: scale(.8) translateX(-3px);
                        color: #fff;
                        border-radius: 8px
                    }
                    </style>
                    <div class="weibo-list">
                        <?php echo weibohot() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
        <!--微博热搜end-->
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentPosts', $this->options->sidebarBlock)) : ?>
        <div class="card-widget card-recent-post">
            <div class="item-headline">
                <i class="fas fa-history"></i><span>
                    <?php _e('最新文章'); ?>
                </span>
            </div>
            <div class="aside-list">
                <?php $this->widget('Widget_Contents_Post_Recent')->to($contents); ?>
                <?php while ($contents->next()) : ?>
                <div class="aside-list-item">
                    <a class="thumbnail" href="<?php $contents->permalink() ?>" title="<?php $contents->title() ?>">
                        <img onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'"
                            data-lazy-src="<?php GetRandomThumbnail($contents); ?> " src="<?php echo GetLazyLoad() ?>"
                            alt="<?php $contents->title() ?>">
                    </a>
                    <div class="content">
                        <a class="title" href="<?php $contents->permalink() ?>">
                            <?php $contents->title() ?>
                        </a>
                        <time datetime="" title="发表于 ">
                            <?php $contents->date() ?>
                        </time>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentComments', $this->options->sidebarBlock)) : ?>
        <div class="card-widget" id="card-newest-comments">
            <div class="item-headline">
                <i class="fas fa-bolt"></i>
                <span><?php _e('最新评论'); ?></span>
            </div>
            <div class="aside-list" id="giscus-recent-comments">
                <div class="aside-list-item">
                    <div class="content" style="text-align: center; width: 100%;">
                        <span class="comment">正在加载最新评论...</span>
                    </div>
                </div>
            </div>
            <div class="comment-refresh" style="text-align: center; margin-top: 10px; display: none;">
                <button onclick="loadRecentComments()" style="background: #4a7dff; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">
                    <i class="fas fa-sync-alt"></i> 重新加载
                </button>
            </div>
        </div>
        
        <!-- 引入评论获取脚本 -->
        <script src="<?php $this->options->themeUrl('js/comments-cache.js'); ?>?v=<?php echo time(); ?>"></script>
        <?php endif; ?>
        <?php /* 开始注释掉 分类 卡片 */ ?>
        <?php /* if (!empty($this->options->sidebarBlock) && in_array('ShowCategory', $this->options->sidebarBlock)) : ?>
        <div class="card-widget card-categories" data-aos="fade-left" data-aos-easing="ease-out"
            data-aos-duration="4000" data-aos-delay="500" data-aos-offset="200" data-aos-mirror="true">
            <div class="item-headline"><i class="fas fa-folder-open"></i><span>
                    <?php _e('分类'); ?>
                </span>
            </div>
            <ul class="card-category-list" id="aside-cat-list">
                <?php $this->widget('Widget_Metas_Category_List')->parse('
         <li class="card-category-list-item">
         <a href="{permalink}" class="card-category-list-link" title="{description}"> 
         <span class="card-category-list-name">{name}</span>
          <span class="card-category-list-count"> {count} </span>
          </a>
          </li> '); ?>
            </ul>
        </div>
        <?php endif; */ ?>
        <?php /* 结束注释掉 分类 卡片 */ ?>

        <?php /* 开始注释掉 标签 卡片 */ ?>
        <?php /* if (!empty($this->options->sidebarBlock) && in_array('ShowTag', $this->options->sidebarBlock)) : ?>
        <div class="card-widget card-tags" data-aos="fade-left" data-aos-easing="ease-out" data-aos-duration="4000"
            data-aos-delay="500" data-aos-offset="200" data-aos-mirror="true">
            <div class="item-headline"><i class="fas fa-tags"></i><span>
                    <?php _e('标签'); ?>
                </span></div>

            <div class="card-tag-cloud">
                <?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 20))->to($tags); ?>
                <?php while ($tags->next()) : ?>
                <a <?php if (
                                !empty($this->options->beautifyBlock) && in_array(
                                    'ShowColorTags',
                                    $this->options->beautifyBlock
                                )
                            ) : ?>
                    style="color: rgb(<?php echo (rand(0, 255)); ?>, <?php echo (rand(0, 255)); ?>, <?php echo (rand(0, 255)); ?>)"
                    <?php endif; ?> rel="tag" href="<?php $tags->permalink(); ?>" title="<?php $tags->name(); ?>"
                    style='display: inline-block; margin: 0 5px 5px 0;'>
                    <?php $tags->name(); ?>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; */ ?>
        <?php /* 结束注释掉 标签 卡片 */ ?>

        <?php /* 开始注释掉 归档 卡片 */ ?>
        <?php /* if (!empty($this->options->sidebarBlock) && in_array('ShowArchive', $this->options->sidebarBlock)) : ?>
        <div class="card-widget card-archives" data-aos="fade-left" data-aos-easing="ease-out" data-aos-duration="4000"
            data-aos-delay="500" data-aos-offset="200" data-aos-mirror="true">
            <div class="item-headline">
                <i class="fas fa-archive"></i><span>
                    <?php _e('归档'); ?>
                </span>
            </div>
            <ul class="card-archive-list">
                <?php
                    $this->widget('Widget_Contents_Post_Date', 'type=month&format=n月 Y')->to($recent);
                    $x = 0;
                    $num = 5;
                    if (!empty($this->options->sidderArchiveNum)) {
                        $num = $this->options->sidderArchiveNum;
                    }
                    while ($recent->next() && $x < $num) :
                        echo '<li class="card-archive-list-item">
            <a class="card-archive-list-link" href="' . $recent->permalink . '">
            <span class="card-archive-list-date">' . $recent->date . '</span>
            <span class="card-archive-list-count">' . $recent->count . '</span>
            </a></li>';
                        $x++;
                    endwhile;
                    ?>
            </ul>
        </div>
        <?php endif; */ ?>
        <?php /* 结束注释掉 归档 卡片 */ ?>

        <!-- 开始添加视频播放模块 -->
        <div class="card-widget" data-aos="fade-left" data-aos-easing="ease-out" data-aos-duration="4000"
            data-aos-delay="500" data-aos-offset="200" data-aos-mirror="true">
            <div id="app">
                <div class="video-sidebar-module">
                  <div class="module-header">
                    <h3>轻松一刻</h3>
                    <button id="refresh-video" class="refresh-button" title="加载新视频并自动播放">
                      <svg viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 10h7V3l-2.35 3.35z"></path>
                      </svg>
                    </button>
                  </div>
                  <div class="video-container">
                    <div id="loading-spinner" class="loading-spinner">
                      <div class="spinner-container">
                        <div class="spinner"></div>
                      </div>
                    </div>
                    <div id="error-message" class="error-message">
                      <p>视频加载失败，请刷新重试</p>
                      <button id="retry-button">重试</button>
                    </div>
                    <video id="sidebar-video" controls playsinline muted>
                      您的浏览器不支持 HTML5 视频
                    </video>
                  </div>
                </div>
              </div>
        </div>
        <!-- 结束添加视频播放模块 -->

        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowWebinfo', $this->options->sidebarBlock)) : ?>
        <div class="card-widget card-webinfo" data-aos="fade-left" data-aos-easing="ease-out" data-aos-duration="4000"
            data-aos-delay="500" data-aos-offset="200" data-aos-mirror="true">
            <div class="item-headline">
                <i class="fas fa-chart-line"></i>
                <span>网站统计</span>
            </div>
            <div class="webinfo">
                <style>
                .loading-icon {
                    width: 25px;
                    /* 调整宽度 */
                    height: 25px;
                    /* 调整高度 */
                </style>
                <div class="webinfo-item">
                    <div class="item-name">本站总访问量 :</div>
                    <div class="item-count" id="totalPageviews">
                        <div id="loadingIconTotalPageviews" class="loading-icon"></div>
                    </div>
                </div>

                <div class="webinfo-item">
                    <div class="item-name">当前在线人数 :</div>
                    <div class="item-count" id="activeUsersCount">
                        <div id="loadingIconActiveUsers" class="loading-icon"></div>
                    </div>
                </div>

                <div class="webinfo-item">
                    <div class="item-name">网站运行时长 :</div>
                    <div class="item-count" id="runTimeSidebar">
                        <?php 
                            $buildTime = strtotime(Helper::options()->buildtime); 
                            $runDays = floor((time() - $buildTime) / 86400);
                            echo $runDays . '天';
                        ?>
                    </div>
                </div>

                <script>
                function loadAnimation(containerId, animationPath) {
                    try {
                    if (!document.getElementById(containerId)) return null;
                        if (typeof lottie === 'undefined') {
                            console.error('Lottie库未加载，无法创建动画');
                            return null;
                        }
                    return lottie.loadAnimation({
                        container: document.getElementById(containerId),
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        path: animationPath
                    });
                    } catch (error) {
                        console.error('加载动画时出错:', error.message);
                        return null;
                    }
                }

                <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
                // 网站统计数据
                const siteStats = {
                    articleCount: <?php echo $stat->publishedPostsNum() ?>,
                    totalCharacters: '<?php echo allOfCharacters(true); ?>',
                    runningDays: <?php echo floor((time() - strtotime('2024-02-20')) / 86400); ?>,
                    lastUpdate: '<?php echo get_last_update(true); ?>'
                };

                // 初始化加载动画
                let animationTotalPageviews = loadAnimation('loadingIconTotalPageviews',
                    '<?php $this->options->themeUrl('img/loading.json'); ?>');
                let animationActiveUsers = loadAnimation('loadingIconActiveUsers',
                    '<?php $this->options->themeUrl('img/loading.json'); ?>');

                // 更新网站运行时间
                function updateRunningSidebarTime() {
                    const buildTime = <?php echo strtotime(Helper::options()->buildtime) * 1000; ?>;
                    const now = new Date().getTime();
                    const diff = now - buildTime;
                    
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    
                    document.getElementById('runTimeSidebar').textContent = 
                        days + '天' + hours + '小时' + minutes + '分钟';
                }
                
                // 每分钟更新一次时间
                setInterval(updateRunningSidebarTime, 60000);
                updateRunningSidebarTime(); // 立即执行一次

                // 获取访问统计数据
                function updateVisitorStats() {
                    // 显示loading动画
                    if (document.getElementById('loadingIconTotalPageviews')) {
                        document.getElementById('loadingIconTotalPageviews').style.display = 'block';
                    }
                    if (document.getElementById('loadingIconActiveUsers')) {
                        document.getElementById('loadingIconActiveUsers').style.display = 'block';
                    }

                    // 设置统计数据获取超时
                    const timeout = setTimeout(() => {
                        setDefaultVisitorStats();
                    }, 5000); // 5秒超时

                    fetch('<?php $this->options->themeUrl('public/visitorStats.php'); ?>?t=' + new Date().getTime())
                        .then(response => {
                            clearTimeout(timeout); // 清除超时
                            if (!response.ok) {
                                throw new Error('网络请求失败: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // 销毁对应的动画实例
                            if (animationTotalPageviews) {
                                animationTotalPageviews.destroy();
                                animationTotalPageviews = null;
                            }
                            if (animationActiveUsers) {
                                animationActiveUsers.destroy();
                                animationActiveUsers = null;
                            }

                            // 更新页面内容
                            const totalPageviewsElement = document.getElementById('totalPageviews');
                            const activeUsersElement = document.getElementById('activeUsersCount');
                            
                            if (totalPageviewsElement) {
                                totalPageviewsElement.textContent = data.totalVisits || '<?php echo $stat->publishedPostsNum() * 10 ?>';
                            }
                            
                            if (activeUsersElement) {
                                activeUsersElement.textContent = data.activeUsers || '1';
                            }
                        })
                        .catch(error => {
                            console.error('获取统计数据失败:', error);
                            setDefaultVisitorStats();
                        });
                }

                // 设置默认访客统计数据
                function setDefaultVisitorStats() {
                    // 销毁动画并显示默认值
                    if (animationTotalPageviews) {
                        animationTotalPageviews.destroy();
                        animationTotalPageviews = null;
                    }
                    if (animationActiveUsers) {
                        animationActiveUsers.destroy();
                        animationActiveUsers = null;
                    }
                    
                    const totalPageviewsElement = document.getElementById('totalPageviews');
                    const activeUsersElement = document.getElementById('activeUsersCount');
                    
                    if (totalPageviewsElement) {
                        // 如果获取失败，显示文章数量*10作为备用统计值
                        totalPageviewsElement.textContent = '<?php echo $stat->publishedPostsNum() * 10 ?>';
                    }
                    
                    if (activeUsersElement) {
                        activeUsersElement.textContent = '1';
                    }
                }

                // 页面加载后立即获取数据
                document.addEventListener('DOMContentLoaded', updateVisitorStats);
                
                // 设置定期更新，每3分钟获取一次新数据
                setInterval(updateVisitorStats, 180000); // 180000毫秒 = 3分钟
                </script>

                <div class="webinfo-item">
                    <div class="item-name">文章数目 :</div>
                    <div class="item-count">
                        <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
                        <?php $stat->publishedPostsNum() ?>
                    </div>
                </div>
                <div class="webinfo-item">
                    <div class="item-name">本站总字数 :</div>
                    <div class="item-count">
                        <?php allOfCharacters(); ?>
                    </div>
                </div>

                <div class="webinfo-item">
                    <div class="item-name">最后更新时间 :</div>
                    <div class="item-count">
                        <?php get_last_update(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($this->options->sidebarBlock) && in_array('ShowOther', $this->options->sidebarBlock)) : ?>
        <div class="card-widget card-ty-user">
            <div class="item-headline">
                <i class="fas fa-user"></i><span>
                    <?php _e('用户'); ?>
                </span>
            </div>
            <div class="widget-list">
                <?php if ($this->user->hasLogin()) : ?>
                <div class="last"><a href="<?php $this->options->adminUrl(); ?>">
                        <?php _e('进入后台'); ?> (
                        <?php $this->user->screenName(); ?>)
                    </a></div>
                <div class="last"><a href="<?php $this->options->logoutUrl(); ?>">
                        <?php _e('退出'); ?>
                    </a></div>
                <?php else : ?>
                <div class="last"><a href="<?php $this->options->adminUrl('login.php'); ?>">
                        <?php _e('登录'); ?>
                    </a></div>
                <?php endif; ?>
                <div class="last"><a href="<?php $this->options->feedUrl(); ?>">
                        <?php _e('文章 RSS'); ?>
                    </a></div>
                <div class="last"><a href="<?php $this->options->commentsFeedUrl(); ?>">
                        <?php _e('评论 RSS'); ?>
                    </a></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- end #sidebar -->