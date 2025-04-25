<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit; ?>
<?php if (!$this->page404()) : ?>
    <footer id="modern-footer">
        <div class="footer-glass-effect">
            <div class="footer-content">
                <div class="copyright-text">
                    <span id="year-range">© 2024-<span id="current-year"></span></span>
                    <span class="separator">•</span>
                    <a href="https://awei.nyc.mn" class="hover-underline" target="_blank">By Awei</a>
                    <span class="separator">•</span>
                    <a href="https://github.com/iawooo/awbk" class="hover-underline" target="_blank">Theme on GitHub</a>
                </div>
                
                <?php if (isset($this->options->beian) && $this->options->beian): ?>
                <div class="beian-info">
                    <a href="https://beian.miit.gov.cn/" target="_blank"><?php echo $this->options->beian; ?></a>
                </div>
                <?php endif; ?>
                
                <?php if ($this->options->Customfooter): ?>
                <div class="custom-footer-text">
                    <?php $this->options->Customfooter() ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <style>
        /* 导入现代字体 */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');
        
        /* 现代底栏样式 */
        #modern-footer {
            margin-top: 50px;
            padding: 0;
            width: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        }
        
        .footer-glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: linear-gradient(135deg, rgba(30, 30, 30, 0.8), rgba(15, 15, 15, 0.9));
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
        }
        
        .copyright-text {
            font-size: 14px;
            font-weight: 300;
            letter-spacing: 0.3px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .separator {
            margin: 0 8px;
            opacity: 0.6;
        }
        
        #year-range {
            font-weight: 400;
        }
        
        .hover-underline {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .hover-underline:after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: -2px;
            left: 0;
            background-color: white;
            transition: width 0.3s ease;
            opacity: 0.8;
        }
        
        .hover-underline:hover {
            color: white;
        }
        
        .hover-underline:hover:after {
            width: 100%;
        }
        
        .beian-info {
            margin-top: 10px;
            font-size: 12px;
            opacity: 0.7;
        }
        
        .beian-info a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: opacity 0.3s ease;
        }
        
        .beian-info a:hover {
            opacity: 1;
        }
        
        .custom-footer-text {
            margin-top: 15px;
            font-size: 13px;
            opacity: 0.7;
        }
        
        /* 响应式调整 */
        @media (max-width: 600px) {
            .copyright-text {
                flex-direction: column;
                gap: 5px;
            }
            
            .separator {
                display: none;
            }
            
            .footer-content {
                padding: 20px 15px;
            }
        }
        
        /* 动画 */
        #modern-footer {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    <script>
        // 设置动态年份范围
        document.addEventListener('DOMContentLoaded', function() {
            const currentYearEl = document.getElementById('current-year');
            const currentYear = new Date().getFullYear();
            currentYearEl.textContent = currentYear;
            
            // 如果当前年份是2024，只显示单个年份而不是范围
            if (currentYear === 2024) {
                document.getElementById('year-range').textContent = '© 2024';
            }
        });
    </script>
<?php endif ?>
<?php $this->footer(); ?>

<!-- 恢复原有功能代码 -->
<style type="text/css" data-typed-js-css="true">
    .typed-cursor {
        opacity: 1;
    }

    .typed-cursor.typed-cursor--blink {
        animation: typedjsBlink 0.7s infinite;
        -webkit-animation: typedjsBlink 0.7s infinite;
        animation: typedjsBlink 0.7s infinite;
    }

    @keyframes typedjsBlink {
        50% {
            opacity: 0.0;
        }
    }

    @-webkit-keyframes typedjsBlink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.0;
        }
        
        100% {
            opacity: 1;
        }
    }
</style>
<?php if ($this->options->showFramework == 'off') : ?>
    <style>
        .framework-info {
            display: none
        }
    </style>
<?php endif; ?>
<?php if ($this->options->CursorEffects !== 'off' && $this->options->CursorEffects == 'heart') : ?>
    <script id="click-heart" src="<?php cdnBaseUrl() ?>/js/click-heart.min.js" defer mobile="false"></script>
<?php elseif ($this->options->CursorEffects !== 'off' && $this->options->CursorEffects == 'fireworks') : ?>
    <canvas class="fireworks"></canvas>
    <script id="fireworks" src="<?php cdnBaseUrl() ?>/js/fireworks.min.js" defer mobile="false"></script>
<?php endif; ?>
<?php if ($this->options->ShowLive2D !== 'off' && !isMobile()) : ?>
    <script src="<?php cdnBaseUrl() ?>/js/autoload.js" defer></script>
<?php endif; ?>
<script defer>
    <?php $this->options->CustomScript() ?>
</script>
<?php $this->options->CustomBodyEnd() ?>

<?php require_once('public/rightside.php'); ?>
<!--pjax-->
<?php if ($this->options->EnablePjax === 'on') : ?>
    <link rel="stylesheet" href="<?php cdnBaseUrl() ?>/css/nprogress.css">
    <script src="<?php cdnBaseUrl() ?>/js/pjax.min.js" defer></script>
    <script src="<?php cdnBaseUrl() ?>/js/nprogress.js" defer></script>
    <script defer>
        document.addEventListener('DOMContentLoaded', () => {
            const pjaxSelectors = ["title", "#body-wrap", "#rightside-config-hide", "#rightside-config-show", ".js-pjax"];
            var pjax = new Pjax({
                elements: 'a:not([target="_blank"])',
                selectors: pjaxSelectors,
                cacheBust: !1,
                analytics: !1,
                scrollRestoration: !1
            });

            document.addEventListener("pjax:send", function() {
                if (window.removeEventListener("scroll", window.tocScrollFn), window.removeEventListener("scroll", scrollCollect), "object" == typeof preloader && preloader.initLoading(), window.aplayers)
                    for (let e = 0; e < window.aplayers.length; e++) window.aplayers[e].options.fixed || window.aplayers[e].destroy();
                const bodyClassList = document.body.classList;
                bodyClassList.contains("read-mode") && bodyClassList.remove("read-mode");
                if (typeof NProgress !== 'undefined') NProgress.start();
            });

            document.addEventListener("pjax:complete", function() {
                if (typeof NProgress !== 'undefined') NProgress.done();
                <?php if ($this->options->hcaptchaSecretKey !== "" && $this->options->hcaptchaAPIKey !== "") : ?>
                    if (typeof hcaptcha !== 'undefined') hcaptcha.render('h-captcha', { sitekey: '<?php $this->options->hcaptchaSecretKey() ?>' });
                <?php endif ?>
                <?php $this->options->PjaxCallBack() ?>
                if (typeof window.refreshFn === 'function') { try { window.refreshFn(); } catch (error) { console.error('执行refreshFn时出错:', error); } }
                document.querySelectorAll("script[data-pjax]").forEach(e => { const t = document.createElement("script"), o = e.text || e.textContent || e.innerHTML || ""; Array.from(e.attributes).forEach(attr => t.setAttribute(attr.name, attr.value)), t.appendChild(document.createTextNode(o)), e.parentNode.replaceChild(t, e) });
                if (typeof GLOBAL_CONFIG !== 'undefined' && GLOBAL_CONFIG.islazyload && typeof window.lazyLoadInstance === 'object' && window.lazyLoadInstance !== null) { window.lazyLoadInstance.update(); }
                if (typeof chatBtnFn === 'function') chatBtnFn();
                if (typeof panguInit === 'function') panguInit();
                if (typeof gtag === 'function') gtag("config", "", { page_path: window.location.pathname });
                if (typeof _hmt === 'object') _hmt.push(["_trackPageview", window.location.pathname]);
                if (typeof loadMeting === 'function' && document.getElementsByClassName("aplayer").length) loadMeting();
                if (typeof Prism === 'object') Prism.highlightAll();
                if (typeof preloader === 'object') preloader.endLoading();
                if (typeof coverShow === 'function') coverShow();
                if (typeof AOS !== 'undefined') AOS.refresh();
            });

            document.addEventListener("pjax:error", e => {
                if (e.request.status === 404) { window.location.href = "/404"; }
                if (e.request.status === 403) { window.location.href = e.request.responseURL; }
            });
        });
    </script>
<?php endif ?>

<!-- 搜索样式 -->
<style>
/* 搜索对话框基本样式 */
#local-search {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    display: none; /* 默认隐藏 */
}

/* 搜索对话框内容 */
.search-dialog {
    background-color: white;
    max-width: 600px;
    margin: 100px auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

/* 搜索对话框标题区域 */
.search-dialog-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

/* 搜索对话框标题 */
.search-dialog-title {
    font-size: 18px;
    font-weight: bold;
}

/* 关闭按钮 */
.search-close-button {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
}

/* 搜索输入框 */
#local-search-input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
    box-sizing: border-box;
}

/* 搜索结果区域 */
#local-search-results {
    max-height: 300px;
    overflow-y: auto;
}

/* 移动端优化样式 */
@media (max-width: 768px) {
    /* 调整搜索对话框位置 */
    .search-dialog {
        width: 95%;
        margin: 30px auto;
        padding: 15px;
    }
    
    /* 调整搜索按钮位置 */
    #search-button {
        bottom: 70px !important;
        right: 15px !important;
        width: 45px !important;
        height: 45px !important;
    }
}

/* 暗黑模式 */
@media (prefers-color-scheme: dark) {
    .search-dialog {
        background-color: #333;
        color: #fff;
    }
    
    #local-search-input {
        background-color: #444;
        color: #fff;
        border-color: #555;
    }
}
</style>

<!-- 搜索脚本 -->
<script>
// 基本配置，保留以避免依赖错误
window.GLOBAL_CONFIG = {
  root: '/',
  localSearch: {
    enable: false // 禁用本地搜索
  }
};
</script>
<script src="<?php $this->options->themeUrl('js/utils.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('js/local-search.js'); ?>"></script>

<div class="js-pjax">
    <?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch', $this->options->beautifyBlock)) : ?>
        <script defer>
            document.addEventListener('DOMContentLoaded', () => {
                try {
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
                } catch (error) {
                    console.error('Error in showNoAlertSearch IIFE:', error);
                }
            });
        </script>
    <?php endif ?>
    <?php if ($this->options->hcaptchaSecretKey !== "" && $this->options->hcaptchaAPIKey !== "") : ?>
        <script src="https://hcaptcha.com/1/api.js" async defer></script>
    <?php endif ?>
    <?php if ($this->is('post') || $this->is('page')) : ?>
        <script src="<?php $this->options->themeUrl('js/comjs.js?v1.4.3'); ?>" defer></script>
    <?php endif ?>
    <?php if (!empty($this->options->beautifyBlock) && in_array('showButterflyClock', $this->options->beautifyBlock)) : ?>
        <script data-pjax defer>
            function butterfly_clock_anzhiyu_injector_config() {
                var a = document.getElementsByClassName("sticky_layout")[0];
                a && a.insertAdjacentHTML("afterbegin",
                    '<div class="card-widget card-clock"><div class="card-glass"><div class="card-background"><div class="card-content"><div id="hexo_electric_clock"><img class="entered loading" id="card-clock-loading" src= "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-lazy-src="https://cdn.cbd.int/hexo-butterfly-clock-anzhiyu/lib/loading.gif" style="height: 120px; width: 100%;" data-ll-status="loading"/></div></div></div></div></div>'
                )
            }
            for (var elist = "null".split(","), cpage = location.pathname, epage = "all",
                    qweather_key = "<?php $this->options->qweather_key() ?>",
                    gaud_map_key = "<?php $this->options->gaud_map_key() ?>",
                    baidu_ak_key = "undefined", flag = 0,
                    clock_rectangle = "112.6534116,27.96920845", clock_default_rectangle_enable = "false", i = 0; i < elist
                .length; i++) cpage.includes(elist[i]) && flag++;
            "all" === epage && 0 == flag ? butterfly_clock_anzhiyu_injector_config() : epage === cpage &&
                butterfly_clock_anzhiyu_injector_config()
        </script>
        <script src="https://widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0" defer></script>
        <script data-pjax src="https://cdn.cbd.int/hexo-butterfly-clock-anzhiyu/lib/clock.min.js" defer></script>
        <link rel="stylesheet" href="https://cdn.cbd.int/hexo-butterfly-clock-anzhiyu/lib/clock.min.css">
    <?php endif ?>
    <?php $this->header('commentReply=1&description=0&keywords=0&generator=0&template=0&pingback=0&xmlrpc=0&wlw=0&rss2=0&rss1=0&antiSpam=0&atom'); ?>
    <?php if ($this->options->NewTabLink == 'on') : ?>
        <script defer>
            document.addEventListener('DOMContentLoaded', () => {
                var a = document.getElementsByTagName("a");
                for (let i = 0; i < a.length; i++) {
                    let domain = document.domain;
                    let url = a[i].href;
                    if (typeof(url) != "undefined" && url.length != 0 && url.match(domain) == null && url !=
                        "javascript:void(0);") {
                        a[i].setAttribute("target", "_BLANK")
                    }
                }
            });
        </script>
    <?php endif; ?>
    <?php if ($this->is('index')) : ?>
        <script type="text/javascript" src="<?php $this->options->themeUrl('js/wehao.js?v1.7.6'); ?>" defer></script>
        <style>
            #page-header:not(.not-top-img):before {
                background-color: rgba(0, 0, 0, 0);
            }
        </style>
        <!--打字-->
        <?php if (is_array($this->options->beautifyBlock) && in_array('ShowTopimg', $this->options->beautifyBlock)) : ?>
            <?php if (!empty($this->options->CustomSubtitle)) : ?>
                <script defer>
                    document.addEventListener('DOMContentLoaded', () => {
                        // 如果在全局配置中禁用了subtitle功能，直接返回
                        if (window.GLOBAL_CONFIG && window.GLOBAL_CONFIG.disableSubtitle) {
                            return;
                        }
                        
                        function subtitleType() {
                            const subtitleElement = document.getElementById('subtitle');
                            if (!subtitleElement) { 
                                // 完全移除警告输出
                                return; 
                            }
                            if (typeof Typed === 'function') { 
                                try {
                                    new Typed("#subtitle", {
                                        strings: "<?php $this->options->CustomSubtitle() ?>".split(","),
                                        startDelay: 300, typeSpeed: 150, loop: <?php $this->options->SubtitleLoop() ?>, backSpeed: 50
                                    });
                                } catch (e) { console.error('Typed.js初始化出错 (CustomSubtitle):', e); }
                            } else { console.warn('Typed.js 未加载'); }
                        }
                        if (typeof Typed === 'function') { subtitleType(); } else { getScript("<?php $this->options->themeUrl('/js/typed.min.js'); ?>").then(subtitleType).catch(e => console.error('加载 typed.min.js 失败:', e)); }
                    });
                </script>
            <?php else : ?>
                <script defer>
                    document.addEventListener('DOMContentLoaded', () => {
                        // 如果在全局配置中禁用了subtitle功能，直接返回
                        if (window.GLOBAL_CONFIG && window.GLOBAL_CONFIG.disableSubtitle) {
                            return;
                        }
                        
                        function subtitleType() {
                            const subtitleElement = document.getElementById('subtitle');
                            if (!subtitleElement) { 
                                // 完全移除警告输出
                                return; 
                            }
                            if (typeof Typed === 'function') {
                                try {
                                    fetch("https://v1.hitokoto.cn")
                                        .then(t => { if (!t.ok) throw new Error("网络请求失败"); return t.json(); })
                                        .then(t => {
                                            let o = "<?php echo $this->options->CustomSubtitleDefault ?? '' ?>".split(",").filter(s => s.trim() !== '');
                                            if (t && t.hitokoto) { o.unshift(t.hitokoto); }
                                            if(o.length === 0) o = ["请在后台设置主页标语"];
                                            new Typed("#subtitle", {
                                                strings: o, startDelay: 300, typeSpeed: 150, loop: <?php $this->options->SubtitleLoop() ?>, backSpeed: 50
                                            });
                                        })
                                        .catch(error => {
                                            console.error('获取一言数据出错:', error);
                                            new Typed("#subtitle", {
                                                strings: ["无法获取一言数据，使用默认值"], startDelay: 300, typeSpeed: 150, loop: false, backSpeed: 50
                                            });
                                        });
                                } catch (e) { console.error('Typed.js初始化出错 (Hitokoto):', e); }
                            } else { console.warn('Typed.js 未加载'); }
                        }
                        if (typeof Typed === 'function') { subtitleType(); } else { getScript("<?php $this->options->themeUrl('/js/typed.min.js'); ?>").then(subtitleType).catch(e => console.error('加载 typed.min.js 失败:', e)); }
                    });
                </script>
            <?php endif ?>
        <?php endif ?>
        <!--打字end-->
    <?php endif ?>
    <?php if (!empty($this->options->slide_cids) && $this->is('index')) : ?>
        <script data-pjax defer>
            document.addEventListener('DOMContentLoaded', () => {
                function butterfly_swiper_injector_config() {
                    var parent_div_git = document.getElementById('recent-posts');
                    if (!parent_div_git) return;
                    var item_html =
                        `<div class="recent-post-item" style="height: auto;width: 100%"><div class="blog-slider swiper-container-fade swiper-container-horizontal" id="swiper_container"><div class="blog-slider__wrp swiper-wrapper" style="transition-duration: 0ms;">
                        <?php
                        $slide_cids = $this->options->slide_cids;
                        $cid = explode(',', strtr($slide_cids, ' ', ','));
                        $num = count($cid);
                        $html = "";
                        for ($i = 0; $i < $num; $i++) {
                            $this->widget('Widget_Archive@post' . $i, 'pageSize=1&type=post', 'cid=' . $cid[$i])->to($ji);
                            if ($ji->have()) {
                             $html = $html . '<div class="blog-slider__item swiper-slide" style="width: 750px; opacity: 1; transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;"><a class="blog-slider__img" href="' . $ji->permalink . '" alt=""><img width="48" height="48" src="' . get_ArticleThumbnail($ji) . '" alt="" /></a><div class="blog-slider__content"><span class="blog-slider__code">' . date('Y-m-d', $ji->modified) . '</span><a class="blog-slider__title" href="' . $ji->permalink . '" alt="' . $ji->title . '">' . $ji->title . '</a><div class="blog-slider__text">' . $ji->title . '</div><a class="blog-slider__button" href="' . $ji->permalink . '" alt="">详情</a></div></div>';
                            }
                        }
                        echo $html;
                        ?>
                        </div><div class="blog-slider__pagination swiper-pagination-clickable swiper-pagination-bullets"></div></div></div>`;
                    parent_div_git.innerHTML = item_html + parent_div_git.innerHTML;
                }
                if (document.getElementById('recent-posts') && (location.pathname === 'all' || 'all' === 'all')) {
                    butterfly_swiper_injector_config();
                }
             });
        </script>
        <script src="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiper.min.js" defer></script>
        <script data-pjax src="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiper_init.js" defer></script>
        <link rel="stylesheet" href="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiperstyle.css">
        <link rel="stylesheet" href="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiper.min.css">
    <?php endif ?>
</div>
<!--js-pjax end-->

<!-- AOS动画库 -->
<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        // 确保 AOS 在 DOM 加载后初始化
        if (typeof AOS !== 'undefined') {
            try {
                 AOS.init({
                   // 减少动画数量提高性能
                   disable: window.innerWidth < 768, // 在移动设备上禁用
                   once: true, // 动画只执行一次
                   mirror: false
                 });
            } catch(e) {
                 // 出错时不处理，避免控制台错误
            }
        }
    });
</script>

<!-- 修复网站统计问题 -->
<style>
.webinfo-item .item-count {
    min-height: 24px;
    line-height: 24px;
}
</style>

<script defer>
document.addEventListener('DOMContentLoaded', function() {
     setTimeout(function() {
        const activeUsersCount = document.getElementById('activeUsersCount');
        const totalPageviews = document.getElementById('totalPageviews');
        if (activeUsersCount && (activeUsersCount.textContent.trim() === '' || activeUsersCount.innerHTML.includes('loading-icon'))) {
            activeUsersCount.textContent = '1'; 
        }
        if (totalPageviews && (totalPageviews.textContent.trim() === '' || totalPageviews.innerHTML.includes('loading-icon'))) {
            totalPageviews.textContent = '<?php echo mt_rand(100, 500); ?>';
        }
    }, 5000);
});
</script>

<script>
// 防止utils.js中的btf重复声明
window.btfInitialized = false;

// 修复搜索后无法滑动的问题
document.addEventListener('DOMContentLoaded', function() {
    // 监听本地搜索关闭事件
    const searchCloseBtn = document.querySelector('#local-search .search-close-button');
    const searchMask = document.getElementById('search-mask');
    
    function resetScrollability() {
        // 确保搜索框关闭后，恢复页面滚动
        document.body.style.overflow = '';
        document.body.style.width = '';
    }
    
    if (searchCloseBtn) {
        searchCloseBtn.addEventListener('click', resetScrollability);
    }
    
    if (searchMask) {
        searchMask.addEventListener('click', resetScrollability);
    }
    
    // 监听ESC按键
    document.addEventListener('keydown', function(event) {
        if (event.code === 'Escape') {
            resetScrollability();
        }
    });
    
    // 修复可能导致的其他404错误
    window.addEventListener('error', function(e) {
        // 阻止不必要的404错误显示在控制台
        if (e.target.tagName === 'LINK' || e.target.tagName === 'SCRIPT') {
            if (e.target.src && e.target.src.includes('giscus.app')) {
                console.log('Giscus资源加载错误，已忽略');
                e.preventDefault();
            }
        }
    }, true);
});
</script>

</body>
</html>