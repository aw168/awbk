<?php $this->need('header_com.php'); ?>
<!--加载进度条插件-->
<?php Typecho_Plugin::factory('Process')->render(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $this->options->title(); ?></title>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css'); ?>">
    <?php if (Helper::options()->commentsStyle !== 'off'): ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/comments.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/comments-form.css'); ?>">
    <?php endif; ?>
</head>

<body style="zoom: 1;">
    <div class="page" id="body-wrap">
        <?php if (is_array($this->options->beautifyBlock) && in_array('ShowTopimg', $this->options->beautifyBlock)) : ?>
        <header class="half_page" id="page-header" style="background-image: url(<?php $this->options->headerimg(); ?>)">
            <div id="site-info">
                <!-- 添加的作者信息模块 -->
                <div class="author-info-box" id="author-info-box">
                    <div class="author-avatar">
                        <img data-lazy-src="https://awtc.pp.ua/1745514532958.png" src="<?php echo GetLazyLoad() ?>" alt="author avatar">
                    </div>
                    <div class="author-name"><?php $this->options->title(); ?></div>
                    <div class="author-description"><?php $this->options->description(); ?></div>
                    <div class="additional-links header-links">
                        <?php if ($this->options->headerSocialLinks): ?>
                            <?php $this->options->headerSocialLinks(); ?>
                        <?php else: ?>
                        <a target="_BLANK" href="https://github.com/iawooo" title="GitHub主页"><img src="/usr/themes/butterfly/img/github.svg" alt="github"></a>
                        <a target="_BLANK" href="https://t.me/AwcttBot" title="telegram"><img src="/usr/themes/butterfly/img/telegram.svg" alt="telegram"></a>
                        <a href="mailto:iawooo@qq.com" title="邮箱"><img src="/usr/themes/butterfly/img/mail.svg" alt="mail" style="width:36px; height:36px;"></a>
                        <?php endif; ?>
                    </div>
                </div>

                <script>
                // 根据当前主题模式设置作者信息框的样式
                document.addEventListener('DOMContentLoaded', function() {
                    var theme = document.documentElement.getAttribute('data-theme');
                    var authorBox = document.getElementById('author-info-box');
                    
                    if (theme === 'dark') {
                        authorBox.classList.add('dark-mode');
                    } else {
                        authorBox.classList.remove('dark-mode');
                    }
                    
                    // 监听主题切换
                    document.addEventListener('themeChange', function(e) {
                        if (e.detail.theme === 'dark') {
                            authorBox.classList.add('dark-mode');
                        } else {
                            authorBox.classList.remove('dark-mode');
                        }
                    });
                });
                </script>
                
                <!-- 雪花特效 -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const header = document.getElementById('page-header');
                    if (!header) return;
                    
                    // 性能检测 - 移动设备降低雪花数量或禁用
                    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    const isLowPerformance = isMobile || window.innerWidth < 768;
                    
                    // 根据设备性能调整雪花数量
                    const snowflakeCount = isLowPerformance ? 5 : 40;
                    
                    // 雪花容器 - 使用单个容器而不是多个DOM元素
                    const snowContainer = document.createElement('div');
                    snowContainer.className = 'snow-container';
                    snowContainer.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;overflow:hidden;';
                    header.appendChild(snowContainer);
                    
                    // 存储所有雪花元素
                    const snowflakes = [];
                    let isSnowEnabled = true;
                    
                    // 创建雪花并添加到容器
                    function createSnowflakes() {
                        // 清空现有雪花
                        snowflakes.forEach(flake => flake.remove());
                        snowflakes.length = 0;
                        
                        // 创建新雪花
                        for (let i = 0; i < snowflakeCount; i++) {
                            createSnowflake(snowContainer);
                        }
                    }
                    
                    // 创建单个雪花
                    function createSnowflake(container) {
                        const snowflake = document.createElement('div');
                        snowflake.className = 'snowflake';
                        
                        // 随机大小 (2-6px)
                        const size = Math.random() * 4 + 2;
                        snowflake.style.width = size + 'px';
                        snowflake.style.height = size + 'px';
                        
                        // 随机透明度 (0.3-0.8)
                        snowflake.style.opacity = Math.random() * 0.5 + 0.3;
                        
                        // 随机位置
                        snowflake.style.left = Math.random() * 100 + '%';
                        snowflake.style.top = -10 + 'px';
                        
                        // 使用CSS动画而不是JavaScript动画
                        const fallDuration = Math.random() * 3 + 3; // 3-6秒
                        const swayAmount = Math.random() * 10 + 5; // 5-15px
                        
                        // 设置CSS动画
                        snowflake.style.animation = `snowfall ${fallDuration}s linear infinite, snowsway ${(Math.random() * 3 + 2)}s ease-in-out infinite alternate`;
                        
                        // 添加随机延迟，防止所有雪花同时动画
                        snowflake.style.animationDelay = `${Math.random() * 5}s`;
                        
                        // 自定义属性记录摆动距离，用于CSS变量
                        snowflake.style.setProperty('--sway-distance', `${swayAmount}px`);
                        
                        // 添加到容器
                        container.appendChild(snowflake);
                        snowflakes.push(snowflake);
                    }
                    
                    // 添加必要的CSS动画
                    const styleTag = document.createElement('style');
                    styleTag.textContent = `
                        @keyframes snowfall {
                            0% { top: -10px; }
                            100% { top: 100%; }
                        }
                        @keyframes snowsway {
                            0% { transform: translateX(0); }
                            100% { transform: translateX(var(--sway-distance, 10px)); }
                        }
                    `;
                    document.head.appendChild(styleTag);
                    
                    // 创建雪花
                    createSnowflakes();
                    
                    // 窗口大小改变时重新创建适量雪花
                    let resizeTimeout;
                    window.addEventListener('resize', function() {
                        if (resizeTimeout) clearTimeout(resizeTimeout);
                        resizeTimeout = setTimeout(function() {
                            // 重新检测设备类型
                            const newIsMobile = window.innerWidth < 768;
                            if (newIsMobile !== isLowPerformance) {
                                // 设备类型变化时重新创建雪花
                                createSnowflakes();
                            }
                        }, 300);
                    });
                });
                </script>
            </div>
            <div id="scroll-down"><i class="fas fa-angle-down scroll-down-effects"></i></div>
            <?php else : ?>
            <header class="not-top-img" id="page-header">
                <?php endif; ?>
                <?php $this->need('public/nav.php'); ?>
            </header>

<style>
/* 搜索框样式 - 透明效果 */
#dSearchIn {
  width: 35px;
  transition: width 0.3s ease-in-out, background 0.3s ease;
  border: none;
  border-radius: 15px;
  outline: none;
  padding: 5px 10px;
  background: transparent; /* 完全透明背景 */
  color: var(--search-text-color, #fff); /* 使用变量便于暗黑模式适配 */
}

#dSearchIn:focus {
  width: 150px !important;
  background: rgba(255, 255, 255, 0.2); /* 稍微半透明的背景，聚焦时 */
  backdrop-filter: blur(5px); /* 模糊背景效果 */
  -webkit-backdrop-filter: blur(5px); /* Safari 兼容 */
}

@media (max-width: 768px) {
  #dSearchIn:focus {
    width: 120px !important;
  }
}

/* 搜索图标与输入框布局 */
#search-button .site-page.search {
  display: flex;
  align-items: center;
}

#search-button .fas.fa-search {
  margin-right: 5px;
}

/* 保持搜索框在有内容时展开状态 */
#dSearchIn:not(:placeholder-shown) {
  width: 150px !important;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
}

/* 设置输入框placeholder颜色 */
#dSearchIn::placeholder {
  color: rgba(255, 255, 255, 0.7);
}

/* 暗黑模式适配 */
[data-theme="dark"] #dSearchIn {
  color: #e5e5e5;
}

[data-theme="dark"] #dSearchIn:focus,
[data-theme="dark"] #dSearchIn:not(:placeholder-shown) {
  background: rgba(50, 50, 50, 0.4);
}

/* 响应式调整 */
@media (max-width: 768px) {
  .additional-links {
    gap: 15px;
  }
}
</style>