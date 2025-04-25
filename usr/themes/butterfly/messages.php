<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php   
/**  
    * 留言板
    *  
    * @package custom  
    * @type page
    */ 
//$this->need('page_header.php');
 ?>

<?php  $this->need('header_com.php'); ?>
<header class="not-top-img" id="page-header">
    <?php  $this->need('public/nav.php'); ?>
</header>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


body {
    min-height: 100vh;
    background-image: url("https://images4.alphacoders.com/966/966314.jpg");
    /* background-image: url("https://images.alphacoders.com/128/1281557.jpg"); */
    background-size: cover; /* 背景图片覆盖整个body */
    background-position: center; /* 背景图片居中显示 */
    background-attachment: fixed; /* 背景图片固定，不随滚动条滚动 */
}

[data-theme="dark"] body{
    background-image: url("https://images6.alphacoders.com/112/1123556.png");
}

#title{
    color: white; /* 设置字体颜色为白色 */
    font-size: 45px; /* 增加字体大小 */
    padding-top: 20vh;
    text-align: center;
    height: 50vh; /* 使主内容区域至少为视窗的高度 */
}

.info{
    margin-top: 50px;
    text-align: center; /* 确保文本居中 */
    margin-bottom: 100px;
    font-size: 30px;
}

</style>

<div id="title">留言板</div>
<main class="layout" id="content-inner">
<div id="page" style="width:100%;min-height: 100vh;">
<div class="info">
    欢迎留下你的足迹。
</div>
    <!-- 评论区 -->
<div id="giscus-container">
    <div class="giscus-loading">评论加载中...</div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 定义loadGiscus函数供全局调用
    window.loadGiscus = function() {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      
      // 显示加载状态
      const giscusLoading = document.querySelector('#giscus-container .giscus-loading');
      if (giscusLoading) giscusLoading.textContent = '评论加载中...';
      
      // 删除现有的giscus iframe和script，以便重新加载
      const existingFrame = document.querySelector('iframe.giscus-frame');
      const existingScript = document.getElementById('giscus-script');
      if (existingFrame) existingFrame.remove();
      if (existingScript) existingScript.remove();
      
      // 创建script元素
      const script = document.createElement('script');
      script.id = 'giscus-script';
      script.src = 'https://giscus.app/client.js';
      script.async = true;

      // 设置Giscus属性
      script.setAttribute('data-repo', 'iawooo/vlty');
      script.setAttribute('data-repo-id', 'R_kgDOOUW3Og');
      script.setAttribute('data-category', 'Announcements');
      script.setAttribute('data-category-id', 'DIC_kwDOOUW3Os4CpQhI');
      script.setAttribute('data-mapping', 'pathname'); // 改用pathname映射方式
      script.setAttribute('data-strict', '0');
      script.setAttribute('data-reactions-enabled', '1');
      script.setAttribute('data-emit-metadata', '0');
      script.setAttribute('data-input-position', 'bottom');
      script.setAttribute('data-lang', 'zh-CN');
      script.setAttribute('data-loading', 'lazy');
      script.crossOrigin = 'anonymous';

      // 设置data-theme属性根据当前网站主题
      script.setAttribute('data-theme', (currentTheme === 'dark') ? 'dark' : 'light');
      
      // 添加事件处理
      script.onload = function() {
        // 创建一个检查器，查看iframe是否创建成功
        let checkCount = 0;
        const checkInterval = setInterval(function() {
          checkCount++;
          const giscusFrame = document.querySelector('iframe.giscus-frame');
          
          if (giscusFrame) {
            // 成功加载，清除检查并移除加载状态
            clearInterval(checkInterval);
            if (giscusLoading) giscusLoading.style.display = 'none';
          } else if (checkCount > 30) {
            // 30次检查后仍未加载（15秒），显示错误
            clearInterval(checkInterval);
            if (giscusLoading) {
              giscusLoading.innerHTML = '评论加载失败，请 <button onclick="window.loadGiscus()" class="retry-button">重试</button> 或刷新页面';
            }
          }
        }, 500); // 每500ms检查一次
      };
      
      script.onerror = function() {
        // 加载脚本失败
        if (giscusLoading) {
          giscusLoading.innerHTML = '评论系统加载失败，请 <button onclick="window.loadGiscus()" class="retry-button">重试</button> 或检查网络连接';
        }
      };

      // 将script元素插入到页面中
      document.getElementById('giscus-container').appendChild(script);
    };
    
    // 初始加载
    loadGiscus();
    
    // 添加样式
    const style = document.createElement('style');
    style.textContent = `
      .retry-button {
        margin-left: 10px;
        padding: 5px 10px;
        background-color: #4a89dc;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }
      .retry-button:hover {
        background-color: #3a70c0;
      }
      .giscus-error {
        padding: 15px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 15px 0;
      }
      .giscus-loading {
        text-align: center;
        padding: 20px;
        color: #666;
      }
      /* 隐藏"Discussion not found"警告 */
      .giscus-frame {
        display: block;
      }
    `;
    document.head.appendChild(style);
    
    // 拦截所有来自giscus.app的404错误
    const originalFetch = window.fetch;
    window.fetch = function(url, options) {
      const promise = originalFetch(url, options);
      
      if (url.toString().includes('giscus.app/api/discussions')) {
        // 拦截这个Promise的错误，不让它输出到控制台
        return promise.catch(error => {
          // 静默处理404错误
          if (error.message.includes('404')) {
            // 返回一个假的response，这样调用代码不会出错
            return new Response(JSON.stringify({
              error: {message: 'Discussion not found (silenced)'}
            }), {
              status: 200,
              headers: {'Content-Type': 'application/json'}
            });
          }
          // 其他错误正常传递
          throw error;
        });
      }
      
      return promise;
    };
    
    // 观察giscus容器变化，处理加载状态
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.addedNodes && mutation.addedNodes.length) {
          for (let i = 0; i < mutation.addedNodes.length; i++) {
            const node = mutation.addedNodes[i];
            // 当giscus框架被添加后，隐藏加载状态
            if (node.nodeName === 'IFRAME' && node.classList.contains('giscus-frame')) {
              const giscusLoading = document.querySelector('#giscus-container .giscus-loading');
              if (giscusLoading) giscusLoading.style.display = 'none';
              break;
            }
          }
        }
      });
    });
    
    observer.observe(document.getElementById('giscus-container'), { 
      childList: true,
      subtree: true 
    });
  });
</script>
</div>
</main>

<?php require_once('public/rightside.php'); ?>
<?php $this -> need('footer.php'); ?>