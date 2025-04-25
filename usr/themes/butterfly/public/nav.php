<nav id="nav" class="show">
    <span id="blog-info">
        <a class="site-page dark-light-com" title="深/浅">
            <i class="fas fa-adjust"></i>
            <span style="font-weight:650;"></span>
        </a>
    </span>
    <script>
        // 设置明/暗模式切换按钮的初始状态和点击事件
        const firstMode = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        let spanElement = document.querySelector('.dark-light-com span');
        if (firstMode === 'light') {
            spanElement.textContent = 'Light';
        } else {
            spanElement.textContent = 'Dark';
        }
        document.querySelector('.dark-light-com').addEventListener('click', function() {
            const nowMode = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            let spanElement = this.querySelector('span');
            if (nowMode === 'light') {
                spanElement.textContent = 'Dark';
                activateDarkMode();
                saveToLocal.set('theme', 'dark', 2);
                GLOBAL_CONFIG.Snackbar !== undefined && btf.snackbarShow(GLOBAL_CONFIG.Snackbar.day_to_night);
            } else {
                spanElement.textContent = 'Light';
                activateLightMode();
                saveToLocal.set('theme', 'light', 2);
                GLOBAL_CONFIG.Snackbar !== undefined && btf.snackbarShow(GLOBAL_CONFIG.Snackbar.night_to_day);
            }
            // handle some cases
            typeof utterancesTheme === 'function' && utterancesTheme();
            typeof changeGiscusTheme === 'function' && changeGiscusTheme();
            typeof FB === 'object' && window.loadFBComment();
            window.DISQUS && document.getElementById('disqus_thread').children.length && setTimeout(() => window.disqusReset(), 200);
            typeof runMermaid === 'function' && window.runMermaid();
        });
    </script>
    <div id="menus">
        <!-- 手机端按钮 -->
        <div id="mobile-search-button" class="mobile-only">
            <a class="site-page social-icon search">
                <i class="fas fa-search fa-fw"></i>
                <?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch',$this->options->beautifyBlock)): ?>
                <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search" id="mobile-dSearch">
                    <input type="text" placeholder="" id="mobile-dSearchIn" name="s" required="required">
                </form>
                <?php else: ?>
                <span></span>
                <?php endif ?>
            </a> 
        </div>
        <div id="toggle-menu"><a class="site-page"><i class="fas fa-bars fa-fw"></i></a></div>
        <div class="menus_items">
            <!-- 这里的一切改动记得带上header_com.php -->
            <style>
            #nav .menus_items .menus_item .menus_item_child li:hover {
                background-color: lightskyblue !important;
            }

            #nav .menus_items .menus_item .menus_item_child li:first-child {
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
            }

            #nav .menus_items .menus_item .menus_item_child li:last-child {
                border-bottom-right-radius: 5px;
                border-bottom-left-radius: 5px;
            }
            </style>
            <div class="menus_items">
                <div class="menus_item">
                <a class="site-page" title="首页" href="/">
                    <i class="fas fa-home-alt"></i>
                    <span>首页</span>
                </a>
            </div>
            <div class="menus_item">
                <div id="search-button">
                    <a class="site-page social-icon search">
                    <i class="fas fa-search fa-fw"></i>
                    <?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch',$this->options->beautifyBlock)): ?>
                    <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search" id="dSearch">
                        <input type="text" placeholder="" id="dSearchIn" name="s" required="required">
                    </form>
                    <?php else: ?>
                    <span></span>
                    <?php endif ?>
                    </a> 
                </div>
            </div>
            <div class="menus_item">
                <a class="site-page group" href="javascript:void(0);" rel="external nofollow noreferrer">
                    <i class="fas fa-blog"></i>
                    <span>文章</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="menus_item_child">

                    <li>
                        <a class="site-page child" href="/index.php/archive.html">
                            <i class="fa-fw fas fa-archive"></i>
                            <span>归档</span></a>
                    </li>
                    <li>
                        <a class="site-page child" href="/index.php/tags.html">
                            <i class="fa-fw fas fa-tags"></i>
                            <span>标签</span></a>
                    </li>
                    <li>
                        <a class="site-page child" href="/index.php/category-list.html">
                            <i class="fa-fw fas fa-folder-open"></i>
                            <span>分类</span></a>
                    </li>
                </ul>
            </div>
            <div class="menus_item">
                <a class="site-page" title="留言板" href="/index.php/messages.html">
                    <i class="fas fa-sticky-note"></i>
                    <span>留言板</span>
                </a>
            </div>
            <div class="menus_item">
                <a class="site-page" title="关于" href="/index.php/about-me.html">
                    <i class="fas fa-address-card"></i>
                    <span>关于这一切</span>
                </a>
            </div>
        </div>
    </div>
</nav>