<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
/**  
 * 关于
 *  
 * @package custom  
 * @type page
 */
?>

<?php $this->need('header_com.php'); ?>
<header class="not-top-img" id="page-header">
    <?php $this->need('public/nav.php'); ?>
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
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

[data-theme="dark"] body {
    background-image: url("https://images6.alphacoders.com/112/1123556.png");
}

#content-inner.layout {
    padding-top: 20px !important;
}

#page {
    backdrop-filter: blur(5px);
    background-color: rgba(255, 255, 255, 0.5);
    padding: 40px;
    border-radius: 15px;
    max-width: 900px;
    margin: 0 auto;
}

[data-theme="dark"] #page {
    background-color: rgba(0, 0, 0, 0.5);
}

.about-container {
    text-align: center;
    padding: 20px;
}

.about-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 0 auto 20px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    opacity: 0;
    animation: zoomIn 1s ease forwards, floatImage 4s ease-in-out infinite;
    animation-delay: 0.2s, 1.2s;
}

@keyframes zoomIn {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes floatImage {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-24px);
    }
    100% {
        transform: translateY(0);
    }
}

.about-title {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: var(--font-color);
}

.about-subtitle {
    font-size: 1.5rem;
    margin-bottom: 20px;
    color: var(--font-color);
    opacity: 0.8;
}

.about-description {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 30px;
    color: var(--font-color);
}

.projects-section {
    margin: 30px 0;
    text-align: left;
}

.projects-title {
    font-size: 1.8rem;
    margin-bottom: 20px;
    color: var(--font-color);
    border-bottom: 2px solid var(--hr-border);
    padding-bottom: 10px;
}

.project-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
}

.project-card:hover {
    transform: translateY(-5px);
}

.project-title {
    font-size: 1.4rem;
    margin-bottom: 10px;
    color: var(--font-color);
}

.project-description {
    font-size: 1rem;
    color: var(--font-color);
    opacity: 0.9;
}

.emoji {
    font-size: 1.5em;
    margin-right: 5px;
    display: inline-block;
}

/* 移动端适配 */
@media (max-width: 600px) {
    #page {
        padding: 20px;
    }
    
    .about-title {
        font-size: 2rem;
    }
    
    .about-subtitle {
        font-size: 1.2rem;
    }
}
</style>

<main class="layout" id="content-inner">
    <div id="page">
        <div class="about-container">
            <img src="https://awtc.pp.ua/1.png" alt="阿伟头像" class="about-avatar">
            <h1 class="about-title">👋 嗨，我是阿伟!</h1>
            <p class="about-description">一位充满激情的Web爱好者，热爱构建 Web 应用程序和探索新技术. 🚀</p>
            
            <div class="projects-section">
                <h2 class="projects-title">🚀 特色项目</h2>
                
                <div class="project-card">
                    <h3 class="project-title">📚 书签导航</h3>
                    <p class="project-description">轻量级的书签管理工具，具有拖放排序、备份/恢复和美丽的樱花效果. 🌸</p>
                </div>
                
                <div class="project-card">
                    <h3 class="project-title">💬 CTT</h3>
                    <p class="project-description">基于Cloudflare实现的Telegram消息转发机器人，后台群组每个用户独立群组中的分话题，直接话题中发送信息而无需再艾特回复，适用于客服、社区管理等场景. ✨</p>
                </div>
                
                <div class="project-card">
                    <h3 class="project-title">🖼️ cftc</h3>
                    <p class="project-description">基于cloudflare图床,支持tg机器人面板和网页管理,上传,自定义后缀. ✨</p>
                </div>
            </div>
        </div>
    </div>

    <?php $this->need('sidebar.php'); ?>
</main>

<?php $this->need('footer.php'); ?>