<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit; ?>
<?php $this->need('public/noqq.php'); ?>
<?php if (!$this->user->hasLogin()) : ?>
    <?php $this->need('public/defend.php'); ?>
<?php endif; ?>
<!DOCTYPE HTML>
<html data-theme="light" class="">

<head>
    <!-- 预加载字体文件 -->
    <link rel="preload" href="<?php $this->options->themeUrl('css/IBMPlexMono-Regular.ttf'); ?>" as="font" type="font/ttf" crossorigin>

    <!-- 预连接常用CDN，提高资源加载速度 -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://unpkg.com">
    <link rel="preconnect" href="https://lib.baomitu.com">
    <link rel="preconnect" href="//<?php $this->options->jsdelivrLink() ?>">

    <!-- jQuery库 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- chart.js图标库 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <!-- lottie动画库 -->
    <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.7.4/build/player/lottie.min.js"></script>

    <!-- typed.js -->
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12" defer></script>

    <!-- aos动画库 -->
    <!-- <link rel="stylesheet" href="css/aos.css"> -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" media="print" onload="this.media='all'">
    <!-- <script src="js/aos.js"></script> -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>

    <!-- 平滑滚动插件 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.10/SmoothScroll.min.js" defer></script>
    <!-- 使用自定义设置启用平滑滚动 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 全局错误处理
            window.addEventListener('error', function(event) {
                // 忽略404错误
                if (event.filename && (
                    event.filename.includes('rightside.js') || 
                    event.filename.includes('search.php')
                )) {
                    console.warn('忽略资源404错误:', event.filename);
                    event.preventDefault();
                    return false;
                }
            }, true);

            // jQuery备用定义
            if (typeof jQuery === 'undefined') {
                window.$ = window.jQuery = function() {
                    console.warn('jQuery未加载，调用被忽略');
                    return {
                        ready: function(fn) { document.addEventListener('DOMContentLoaded', fn); },
                        on: function() { return this; },
                        click: function() { return this; }
                    };
                };
            }

            // 如果lottie库未加载，提供占位函数
            if (typeof lottie === 'undefined') {
                window.lottie = {
                    loadAnimation: function() {
                        console.warn('Lottie库未加载，动画创建被忽略');
                        return { destroy: function() {}, play: function() {} };
                    }
                };
            }
            
            if (typeof SmoothScroll === 'function') {
                SmoothScroll({
                    animationTime: 400,
                    stepSize: 80,
                    pulseScale: 2,
                    pulseAlgorithm: true,
                    pulseNormalize: 1,
                    accelerationDelta: 20,
                    accelerationMax: 1,
                    keyboardSupport: true,
                    arrowScroll: 50,
                    fixedBackground: true
                });
            }
        });
    </script>

    <!-- AI摘要机器人 -->
    <!-- <link rel="stylesheet" href="https://cdn1.tianli0.top/gh/zhheo/Post-Abstract-AI@0.15.2/tianli_gpt.css"> -->
    <meta content="always" name="referrer">
    <link rel="icon" type="image/png" href="https://awtc.pp.ua/1.png">
    <link rel="shortcut icon" href="https://awtc.pp.ua/1.png">
    <link rel="apple-touch-icon" href="https://awtc.pp.ua/1.png">
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="theme-color" content="<?php $this->options->themeColor() ?>">
    <title>
        <?php $this->archiveTitle(
            array(
                'category' => _t('分类 %s 下的文章'),
                'search' => _t('包含关键字 %s 的文章'),
                'tag' => _t('标签 %s 下的文章'),
                'author' => _t('%s 发布的文章')
            ),
            '',
            ' - '
        ); ?>
        <?php $this->options->title(); ?>
    </title>
    <!-- 使用url函数转换相关路径 -->
    <link rel="preconnect" href="//<?php $this->options->jsdelivrLink() ?>" />
    <!--<link rel="stylesheet" href="https://gcore.jsdelivr.net/npm/justifiedGallery/dist/css/justifiedGallery.min.css">-->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('index.css?v1.7.3'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css?v1.7.8'); ?>">
    <!-- 自定义CSS文件 - 用于头图样式调整 -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('custom.css'); ?>">
    <!--魔改美化-->
    <?php if (!empty($this->options->beautifyBlock) && in_array('ShowBeautifyChange', $this->options->beautifyBlock)) : ?>
        <link rel="stylesheet" href="<?php $this->options->themeUrl('css/custom.css?v1.5.9'); ?>">
    <?php endif; ?>
    <!--百度统计-->
    <?php if ($this->options->baidustatistics != "") : ?>
        <script>
            var _hmt = _hmt || [];
            (function() {
                var hm = document.createElement("script");
                hm.src = "https://hm.baidu.com/hm.js?<?php $this->options->baidustatistics(); ?>";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();
        </script>
    <?php endif; ?>
    <!--谷歌AdSense广告-->
    <?php if ($this->options->googleadsense != "") : ?>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php $this->options->googleadsense(); ?>" crossorigin="anonymous"></script>
    <?php endif; ?>
    <!--图标库-->
    <link href="https://at.alicdn.com/t/font_3159629_5bvsat8p5l.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://lib.baomitu.com/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <!--其余静态文件-->
    <link rel="stylesheet" href="<?php cdnBaseUrl() ?>/css/fancybox.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php cdnBaseUrl() ?>/css/OwO.min.css" media="print" onload="this.media='all'">
    <?php if (!empty($this->options->beautifyBlock) && in_array('showSnackbar', $this->options->beautifyBlock)) : ?>
        <link rel="stylesheet" href="<?php $this->options->themeUrl('/css/snackbar.min.css') ?>" media="print" onload="this.media='all'">
        <script src="<?php $this->options->themeUrl('js/snackbar.min.js') ?>"></script>
    <?php endif; ?>
    <?php if (!empty($this->options->beautifyBlock) && in_array('showLazyloadBlur', $this->options->beautifyBlock)) : ?>
        <style>
            <?php if ($this->options->themeFontSize != "") : ?> :root {
                --global-font-size:
                    <?php $this->options->themeFontSize() ?>;
            }

            <?php endif ?>img[data-lazy-src]:not(.loaded) {
                filter: blur(0px) brightness(1);
            }

            img[data-lazy-src].error {
                filter: none;
            }

            <?php $this->options->CustomCSS() ?>
        </style>
    <?php endif; ?>
    <?php if (!empty($this->options->sidebarBlock) && !in_array('ShowMobileSide', $this->options->sidebarBlock)) : ?>
        <style>
            @media screen and (max-width:900px) {

                #aside-content .card-info,
                #aside-content .card-announcement,
                #aside-content .card-recent-post,
                #aside-content #card-newest-comments,
                #aside-content .card-categories,
                #aside-content .card-tags,
                #aside-content .card-archives,
                #aside-content .card-webinfo {
                    display: none;
                }
            }

            ins.adsbygoogle[data-ad-status="unfilled"] {
                display: none !important;
            }
        </style>
    <?php endif; ?>
    <!--额外的-->
    <script>
        const GLOBAL_CONFIG = {
            root: "/",
            algolia: void 0,
            localSearch: {
                path: "search.php",
                languages: {
                    hits_empty: "找不到相关内容：${query}"
                }
            },
            translate: {
                defaultEncoding: <?php $this->options->DefaultEncoding() ?>,
                translateDelay: 0,
                msgToTraditionalChinese: "繁",
                msgToSimplifiedChinese: "简"
            },
            noticeOutdate: void 0,
            highlight: {
                plugin: "highlighjs",
                highlightCopy: !0,
                highlightLang: !0,
                highlightHeightLimit: 400
            },
            copy: {
                success: "复制成功",
                error: "复制错误",
                noSupport: "浏览器不支持"
            },
            relativeDate: {
                homepage: !0,
                post: !0
            },
            runtime: "天",
            date_suffix: {
                just: "",
                min: "",
                hour: "",
                day: "",
                month: ""
            },
            copyright: undefined,
            lightbox: "fancybox",
            Snackbar: {
                "chs_to_cht": "你已切换为繁体",
                "cht_to_chs": "你已切换为简体",
                "day_to_night": "你已切换为深色模式",
                "night_to_day": "你已切换为浅色模式",
                "bgLight": "#49b1f5",
                "bgDark": "#121212",
                "position": "<?php $this->options->SnackbarPosition() ?>"
            },
            source: {
                justifiedGallery: {
                    js: "https://cdn.bootcdn.net/ajax/libs/flickr-justified-gallery/2.1.2/fjGallery.min.js",
                    css: "https://cdn.bootcdn.net/ajax/libs/flickr-justified-gallery/2.1.2/fjGallery.min.css"
                }
            },
            isPhotoFigcaption: !1,
            islazyload: !0,
            isAnchor: !0,
            percent: {
                toc: !0,
                rightside: !0
            },
            coverPosition: '<?php $this->options->coverPosition() ?>',
            disableSubtitle: true,
        }
        var saveToLocal = {
            set: function setWithExpiry(key, value, ttl) {
                const now = new Date()
                const expiryDay = ttl * 86400000
                const item = {
                    value: value,
                    expiry: now.getTime() + expiryDay,
                }
                localStorage.setItem(key, JSON.stringify(item))
            },
            get: function getWithExpiry(key) {
                const itemStr = localStorage.getItem(key)

                if (!itemStr) {
                    return undefined
                }
                const item = JSON.parse(itemStr)
                const now = new Date()

                if (now.getTime() > item.expiry) {
                    localStorage.removeItem(key)
                    return undefined
                }
                return item.value
            }
        }
        const getScript = url => new Promise((resolve, reject) => {
            const script = document.createElement('script')
            script.src = url
            script.async = true
            script.onerror = reject
            script.onload = script.onreadystatechange = function() {
                const loadState = this.readyState
                if (loadState && loadState !== 'loaded' && loadState !== 'complete') return
                script.onload = script.onreadystatechange = null
                resolve()
            }
            document.head.appendChild(script)
        })
    </script>
    <script id="config-diff">
        var GLOBAL_CONFIG_SITE = {
            isPost: !0,
            isHome: !0,
            isHighlightShrink: !0,
            isToc: !0,
        }
    </script>
    <?php if ($this->is('post')) : ?>
        <script id="config_change">
            var GLOBAL_CONFIG_SITE = {
                isPost: !0,
                isHome: !0,
                isHighlightShrink: !1,
                isToc: !0,
            }
        </script>
    <?php else : ?>
        <script id="config_change">
            var GLOBAL_CONFIG_SITE = {
                isPost: !1,
                isHome: !0,
                isHighlightShrink: !1,
                isToc: !0,
            }
        </script>
    <?php endif; ?>
    <noscript>
        <style type="text/css">
            #nav {
                opacity: 1
            }

            .justified-gallery img {
                opacity: 1
            }

            #recent-posts time,
            #post-meta time {
                display: inline !important
            }
        </style>
    </noscript>
    <script>
        // 主题的暗色/亮色模式切换功能
        (e => {
            e.saveToLocal = {
                    set: (e, t, a) => {
                        if (0 === a) return;
                        const o = {
                            value: t,
                            expiry: Date.now() + 864e5 * a
                        };
                        localStorage.setItem(e, JSON.stringify(o))
                    },
                    get: e => {
                        const t = localStorage.getItem(e);
                        if (!t) return;
                        const a = JSON.parse(t);
                        if (!(Date.now() > a.expiry)) return a.value;
                        localStorage.removeItem(e)
                    }
                },
                e.getScript = (e, t = {}) => new Promise(((a, o) => {
                    const c = document.createElement("script");
                    c.src = e, c.async = !0, c.onerror = o, c.onload = c.onreadystatechange = function() {
                        const e = this.readyState;
                        e && "loaded" !== e && "complete" !== e || (c.onload = c.onreadystatechange = null,
                            a())
                    }, Object.keys(t).forEach((e => {
                        c.setAttribute(e, t[e])
                    })), document.head.appendChild(c)
                })),
                e.getCSS = (e, t = !1) => new Promise(((a, o) => {
                    const c = document.createElement("link");
                    c.rel = "stylesheet", c.href = e, t && (c.id = t), c.onerror = o, c.onload = c
                        .onreadystatechange = function() {
                            const e = this.readyState;
                            e && "loaded" !== e && "complete" !== e || (c.onload = c.onreadystatechange = null,
                                a())
                        }, document.head.appendChild(c)
                })),
                e.activateDarkMode = () => {
                    document.documentElement.setAttribute("data-theme", "dark"), null !== document.querySelector(
                            'meta[name="theme-color"]') && document.querySelector('meta[name="theme-color"]')
                        .setAttribute("content", "#0d0d0d")
                    //切换暗色模式背景图
                    var headerImg = document.querySelector('#page-header.full_page');
                    if (headerImg) {
                        headerImg.style.backgroundImage = "url(<?php $this->options->headerblackimg() ?>)";
                    }
                    
                    //切换暗色评论区
                    document.querySelectorAll("iframe.giscus-frame")?.forEach(frame => {
                        frame.contentWindow.postMessage({
                                giscus: {
                                    setConfig: {
                                        theme: "dark",
                                    },
                                },
                            },
                            "https://giscus.app"
                        );
                    });
                },
                e.activateLightMode = () => {
                    document.documentElement.setAttribute("data-theme", "light"), null !== document.querySelector(
                            'meta[name="theme-color"]') && document.querySelector('meta[name="theme-color"]')
                        .setAttribute("content", "#ffffff")
                    var headerImg = document.querySelector('#page-header.full_page');
                    //切换浅色模式背景图
                    if (headerImg) {
                        headerImg.style.backgroundImage = "url(<?php $this->options->headerimg() ?>)";
                    }
                    
                    //切换浅色评论区
                    document.querySelectorAll("iframe.giscus-frame")?.forEach(frame => {
                        frame.contentWindow.postMessage({
                                giscus: {
                                    setConfig: {
                                        theme: "light",
                                    },
                                },
                            },
                            "https://giscus.app"
                        );
                    });
                };
            const t = saveToLocal.get("theme"),
                a = <?php $this->options->darkModeSelect() ?> === 4,
                o = <?php $this->options->darkModeSelect() ?> === 1,
                c = <?php $this->options->darkModeSelect() ?> === 2,
                n = !a && !o && !c;
            if (void 0 === t) {
                if (o) activateLightMode();
                else if (a) activateDarkMode();
                else if (n) {
                    const e = (new Date).getHours();
                    <?php darkTimeFunc() ?> ? activateDarkMode() : activateLightMode()
                }
                window.matchMedia("(prefers-color-scheme: dark)").addListener((e => {
                    void 0 === saveToLocal.get("theme") && (e.matches ? activateDarkMode() :
                        activateLightMode())
                }))
            } else "light" === t ? activateLightMode() : activateDarkMode();
            const d = saveToLocal.get("aside-status");
            void 0 !== d && ("hide" === d ? document.documentElement.classList.add("hide-aside") : document
                .documentElement.classList.remove("hide-aside"));
            /iPad|iPhone|iPod|Macintosh/.test(navigator.userAgent) && document.documentElement.classList.add("apple")
        })(window)
    </script>

    <!-- 初始化打字机效果 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 页面加载完成后，初始化打字机效果
            var typedElement = document.getElementById('typed');
            if (typedElement && typeof Typed === 'function') {
                try {
                    var postTitle = "<?php echo addslashes($this->title()); ?>";
                    var options = {
                        strings: [postTitle],
                        typeSpeed: 80,
                        backSpeed: 25,
                        startDelay: 500,
                        cursorChar: '😐',
                        onComplete: function(self) {
                            var cursor = document.querySelector('.typed-cursor');
                            if (cursor) {
                                cursor.textContent = '😀';
                            }
                        }
                    };
                    var typed = new Typed('#typed', options);
                } catch(e) {
                    console.error('Typed animation failed to load:', e);
                }
            }
            
            // 如果#subtitle元素存在，也为它添加打字机效果
            var subtitleElement = document.getElementById('subtitle');
            if (subtitleElement && typeof Typed === 'function') {
                try {
                    var subtitle = subtitleElement.getAttribute('data-text') || "欢迎访问本站";
                    var options = {
                        strings: [subtitle],
                        typeSpeed: 80,
                        backSpeed: 25,
                        startDelay: 500,
                        cursorChar: '|',
                        loop: true
                    };
                    var typed = new Typed('#subtitle', options);
                } catch(e) {
                    // 无需输出错误
                }
            }
        });
    </script>

    <!--额外的-->

    <?php if ($this->options->EnableCustomColor === 'true') : ?>
        <style>
            ::-webkit-scrollbar-thumb {
                background-color:
                    <?php $this->options->CustomColorMain() ?> !important;
            }

            :root {
                --btn-hover-color:
                    <?php $this->options->CustomColorButtonHover() ?>;
                --btn-bg:
                    <?php $this->options->CustomColorButtonBG() ?>;
                --text-bg-hover:
                    <?php $this->options->CustomColorButtonBG() ?>;
                --hr-before-color:
                    <?php $this->options->CustomColorButtonBG() ?>;
                --text-bg-hover:
                    <?php $this->options->CustomColorMain() ?>;
                --hr-border:
                    <?php $this->options->CustomColorMain() ?>;
            }

            ::selection,
            #aside-content #card-toc .toc-content .toc-link.active {
                background:
                    <?php $this->options->CustomColorSelection() ?>;
            }

            #page-header.nav-fixed #nav #site-name:hover,
            #page-header.nav-fixed #nav #toggle-menu:hover,
            #page-header.nav-fixed #nav #menus .menus_items .menus_item a:hover,
            #aside-content #card-toc .toc-content .toc-link:hover,
            #recent-posts>.recent-post-item>.recent-post-info>.article-title:hover,
            #aside-content .aside-list>.aside-list-item .content>.comment:hover,
            #aside-content .aside-list>.aside-list-item .content>.title:hover,
            .widget-list a:hover,
            .post-copyright-info a:hover,
            .article-sort-item-title:hover,
            .search-dialog .search-nav,
            #page-header.nav-fixed #nav a:hover,
            .search-dialog .search-nav .search-close-button:hover {
                color:
                    <?php $this->options->CustomColorMain() ?>;
            }

            #nav .site-page:not(.child):after {
                background-color:
                    <?php $this->options->CustomColorMain() ?>
            }

            #local-search .search-dialog .local-search-box input {
                border: 2px solid <?php $this->options->CustomColorMain() ?> !important;
            }

            #aside-content .card-archives ul.card-archive-list>.card-archive-list-item a:hover,
            #aside-content .card-categories ul.card-category-list>.card-category-list-item a:hover {
                background-color: var(--btn-bg);
            }

            #aside-content .card-tag-cloud a:hover {
                color:
                    <?php $this->options->CustomColorMain() ?> !important;
            }
        </style>
    <?php endif ?>
    <?php $this->header('generator=&'); ?>
    <?php $this->options->CustomHead() ?>
    <?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch', $this->options->beautifyBlock)) : ?>
        <style>
            #dSearch {
                display: inline-block;
            }

            #dSearch>input {
                border: none;
                opacity: 1;
                outline: none;
                width: 35px;
                text-indent: 2px;
                transition: all .5s;
                background: transparent;
            }

            #page-header.nav-fixed #nav ::placeholder,
            #page-header.nav-fixed #nav input {
                color: var(--font-color);
            }

            #nav ::placeholder,
            #nav input {
                color: var(--light-grey);
            }

            #page-header.not-top-img #nav ::placeholder,
            #page-header.not-top-img #nav input {
                color: var(--font-color);
                text-shadow: none;
            }

            #page-header.nav-fixed #nav a:hover {
                color: unset;
            }
        </style>
        <script defer>
            document.addEventListener('DOMContentLoaded', () => {
                try {
                    // 桌面版框
                    const searchButton = document.getElementById('search-button');
                    const input = document.getElementById('dSearchIn');
                    if (searchButton && input) {
                        searchButton.addEventListener('click', function() {
                            try { input.style.width = '150px'; input.focus(); } catch (e) { console.warn('Error handling search button click:', e); }
                        });
                        input.addEventListener('blur', function() {
                            try { input.style.width = '35px'; } catch (e) { console.warn('Error handling input blur:', e); }
                        });
                    }
                    
                    // 侧边栏框
                    const sidebarSearchButton = document.getElementById('sidebar-search-button');
                    const sidebarInput = document.getElementById('sidebar-dSearchIn');
                    if (sidebarSearchButton && sidebarInput) {
                        sidebarSearchButton.addEventListener('click', function() {
                            try { sidebarInput.style.width = '150px'; sidebarInput.focus(); } catch (e) { console.warn('Error handling sidebar search button click:', e); }
                        });
                        sidebarInput.addEventListener('blur', function() {
                            try { sidebarInput.style.width = '35px'; } catch (e) { console.warn('Error handling sidebar input blur:', e); }
                        });
                    }
                } catch (error) {
                    console.error('Error in showNoAlertSearch IIFE:', error);
                }
            });
        </script>
    <?php endif ?>
    <!-- 添加手机端框的样式 -->
    <style>
        /* 手机端框样式 */
        #mobile-dSearch {
            display: inline-block;
        }
        
        #mobile-dSearch > input {
            border: none;
            opacity: 1;
            outline: none;
            width: 35px;
            text-indent: 2px;
            transition: all .5s;
            background: transparent;
            color: var(--font-color);
        }
        
        #mobile-search-button .search {
            display: flex;
            align-items: center;
        }
        
        /* 针对暗色模式的适配 */
        [data-theme="dark"] #mobile-dSearchIn {
            color: #eee;
        }
        
        /* 在小屏幕上显示，大屏幕上隐藏 */
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
        }
        
        /* 在小屏幕上增加触摸区域和定位 */
        @media (max-width: 768px) {
            #mobile-search-button.mobile-only {
                display: flex;
                align-items: center;
                margin-right: 8px;
            }
            
            #mobile-search-button .search {
                padding: 8px 4px;
            }
            
            #mobile-dSearchIn {
                font-size: 16px; /* 防止iOS缩放 */
            }
            
            /* 确保按钮在菜单按钮左侧 */
            #menus {
                display: flex;
                align-items: center;
            }
            
            #toggle-menu {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
    <!-- 核心js文件，使用defer延迟加载 -->
    <script src="<?php $this->options->themeUrl('/js/main.js?v1.8.0'); ?>" defer></script>
    <script src="<?php $this->options->themeUrl('/js/utils.js?v1.7.3'); ?>" defer></script>
    <script src="<?php $this->options->themeUrl('/js/tw_cn.js?v1.7.3'); ?>" defer></script>
    <!-- 使用简化版的脚本替代复杂版本 -->
    <!-- <script src="<?php $this->options->themeUrl('/js/simple-search.js?v1.0.0'); ?>"> </script> -->

    <!-- 第三方库，使用defer延迟加载 -->
    <script src="<?php cdnBaseUrl() ?>/js/jquery.min.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/instantpage.min.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/medium-zoom.min.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/dream-msg.min.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/lazyload.iife.min.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/fancybox.umd.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/OwO.min.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/artplayer.js" defer></script>

    <!--[if lt IE 8]>
    <div class="browsehappy" role="dialog"><?php _e('当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="http://browsehappy.com/">升级你的浏览器</a>'); ?>.</div>
<![endif]-->
    <!--移动导航栏-->
    <div id="sidebar">
        <div id="menu-mask" style="display: none;"></div>
        <div id="sidebar-menus" class="">
            <div class="avatar-img is-center">
                <img src="https://awtc.pp.ua/1745514532958.png" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" alt="avatar">
            </div>
            <div class="site-data">
                <div class="card-info-data site-data is-center">
                    <a href="<?php $this->options->archivelink() ?>">
                        <div class="headline">文章</div>
                        <div class="length-num">
                            <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
                            <?php $stat->publishedPostsNum() ?>
                        </div>
                    </a>
                    <a href="<?php $this->options->tagslink() ?>">
                        <div class="headline">标签</div>
                        <div class="length-num">
                            <?php echo tagsNum(); ?>
                        </div>
                    </a>
                    <a href="<?php $this->options->categorylink() ?>">
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
            <hr>
            <div class="menus_items">
                <div class="menus_item">
                    <a class="site-page" title="首页" href="/">
                        <i class="fas fa-home-alt"></i>
                        <span>首页</span>
                    </a>
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
                <?php if ($this->options->EnableAutoHeaderLink === 'on') : ?>
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while ($pages->next()) : ?>
                        <?php 
                            // 跳过归档、标签、分类、页面，以及关于和留言板页面（因为会在自动菜单中重复）
                            if ($pages->title == "归档" || $pages->title == "标签" || $pages->title == "分类" || 
                                $pages->title == "分类列表" || $pages->title == "" || 
                                $pages->slug == "about-me" || $pages->slug == "messages" ||
                                $pages->title == "搜索") {
                                continue; 
                            }
                        ?>
                        <div class="menus_item">
                            <a<?php if ($this->is('page', $pages->slug)) : ?><?php endif; ?> class="site-page" href="<?php $pages->permalink(); ?>">
                                <?php switch ($pages->title) {
                                    case "友链":
                                        echo "<i class='fa-fw fas fa-link'></i>";
                                        break;
                                    case "关于":
                                        echo "<i class='fa-fw fas fa-user'></i>";
                                        break;
                                    case "留言":
                                        echo "<i class='fa-fw fas fa-comment-dots'></i>";
                                        break;
                                    case "留言板":
                                        echo "<i class='fa-fw fa fa-comment-dots'></i>";
                                        break;
                                    default:
                                        echo "<i class='fa-fw fa fa-coffee'></i>";
                                } ?>
                                <span>
                                    <?php $pages->title(); ?>
                                </span>
                                </a>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
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
                <?php $this->options->CustomHeaderLink() ?>
            </div>
        </div>
    </div>
    <!--移动导航栏-->
    <script>
        $(document).ready(function() {
            // 处理按钮点击事件
            $('.search-btn').on('click', function() {
                $('#sidebar-menus').removeClass('open'); // 假设 'open' 类控制着侧栏的显示
                $('#menu-mask').hide(); // 如果有遮罩层，也需要隐藏
            });

            // 为顶部导航栏的手机端框添加输入框展开/收缩效果
            $('#mobile-search-button .search').on('click', function() {
                const input = document.getElementById('mobile-dSearchIn');
                if (input) {
                    input.style.width = '150px';
                    input.focus();
                }
            });
            
            $('#mobile-dSearchIn').on('blur', function() {
                this.style.width = '35px';
            });
        });
    </script>
    <!-- 预加载JS脚本，确保不再引用simple-search.js -->
    <script>
    // 这里我们通过GLOBAL_CONFIG全局配置路径
    window.addEventListener('DOMContentLoaded', function() {
      // 确保全局配置对象存在
      window.GLOBAL_CONFIG = window.GLOBAL_CONFIG || {};
      
      // 设置配置
      window.GLOBAL_CONFIG.localSearch = {
        path: '/search.xml',
        languages: {
          hits_empty: '找不到您查询的内容：${query}'
        }
      };
    });
    </script>
    
    <!-- 添加手机端框的样式 -->
    <style>
        /* 手机端框样式 */
        #mobile-dSearch {
            display: inline-block;
        }
        
        #mobile-dSearch > input {
            border: none;
            opacity: 1;
            outline: none;
            width: 35px;
            text-indent: 2px;
            transition: all .5s;
            background: transparent;
            color: var(--font-color);
        }
        
        #mobile-search-button .search {
            display: flex;
            align-items: center;
        }
        
        /* 针对暗色模式的适配 */
        [data-theme="dark"] #mobile-dSearchIn {
            color: #eee;
        }
        
        /* 在小屏幕上显示，大屏幕上隐藏 */
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
        }
        
        /* 在小屏幕上增加触摸区域和定位 */
        @media (max-width: 768px) {
            #mobile-search-button.mobile-only {
                display: flex;
                align-items: center;
                margin-right: 8px;
            }
            
            #mobile-search-button .search {
                padding: 8px 4px;
            }
            
            #mobile-dSearchIn {
                font-size: 16px; /* 防止iOS缩放 */
            }
            
            /* 确保按钮在菜单按钮左侧 */
            #menus {
                display: flex;
                align-items: center;
            }
            
            #toggle-menu {
                margin-left: auto;
            }
        }
    </style>
    <script>
    // 防止utils.js中的btf重复声明
    window.btfInitialized = false;

    // 页面加载优化：延迟加载非关键资源
    document.addEventListener('DOMContentLoaded', function() {
        // 1秒后加载非关键资源
        setTimeout(function() {
            // 懒加载图片初始化
            if (typeof window.lazyLoadInstance === 'undefined' && typeof LazyLoad === 'function') {
                window.lazyLoadInstance = new LazyLoad({
                    elements_selector: 'img[data-lazy-src],.lazy',
                    threshold: 300,
                    callback_error: function(img) {
                        img.setAttribute('src', img.getAttribute('data-error-src') || '<?php $this->options->themeUrl("img/404.jpg"); ?>');
                        img.classList.add('error');
                    }
                });
            }
            
            // 延迟加载其他非关键脚本
            [
                '<?php $this->options->themeUrl("/js/snackbar.min.js"); ?>',
                '<?php $this->options->themeUrl("/js/rightside.js"); ?>'
            ].forEach(function(src) {
                if (src) {
                    var script = document.createElement('script');
                    script.src = src;
                    script.defer = true;
                    document.body.appendChild(script);
                }
            });
        }, 1000);
    });
    </script>
</body>

</html>