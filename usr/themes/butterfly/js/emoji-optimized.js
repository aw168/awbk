/**
 * OwO表情选择器优化脚本
 * 确保点击表情选择器时插入的是表情符号而不是表情名称
 */
(function() {
    // 存储表情数据
    let emojiData = null;
    // 全局数据共享，防止重复加载
    window.OwOEmojiData = window.OwOEmojiData || null;
    
    // 加载OwO.json数据，使用缓存优化
    function loadEmojiData() {
        // 如果全局已有数据，直接使用
        if (window.OwOEmojiData) {
            emojiData = window.OwOEmojiData;
            enhanceEmojiItems();
            return;
        }
        
        // 尝试从sessionStorage获取缓存数据
        const cachedData = sessionStorage.getItem('owoCachedEmoji');
        if (cachedData) {
            try {
                emojiData = JSON.parse(cachedData);
                window.OwOEmojiData = emojiData;
                enhanceEmojiItems();
                return;
            } catch (e) {
                // 缓存数据解析错误，继续从服务器加载
            }
        }
        
        // 从服务器加载
        const emojiPath = window.location.origin + '/OwO.json?v=' + Date.now();
        
        fetch(emojiPath)
            .then(response => response.json())
            .then(data => {
                emojiData = data;
                window.OwOEmojiData = data;
                // 缓存到sessionStorage
                try {
                    sessionStorage.setItem('owoCachedEmoji', JSON.stringify(data));
                } catch (e) {
                    // 存储失败时忽略错误
                }
                enhanceEmojiItems();
                // 处理评论区表情
                cleanupCommentEmojis();
            })
            .catch(() => {});
    }
    
    // 处理表情点击
    function handleEmojiClick(event) {
        // 检查是否点击的是表情元素
        const target = event.target.closest('.OwO-item');
        if (!target) return;
        
        // 阻止事件冒泡和默认行为
        event.stopPropagation();
        event.preventDefault();
        
        // 获取表情数据
        let emojiText = '';
        const title = target.getAttribute('title');
        const categoryElem = target.closest('.OwO-items');
        
        if (categoryElem && title && emojiData) {
            const category = categoryElem.getAttribute('data-name');
            if (category && emojiData[category] && 
                emojiData[category].container && 
                emojiData[category].container[title]) {
                // 获取纯表情符号 - 从表情数据中提取
                emojiText = emojiData[category].container[title];
                
                // 检查是否包含前缀（如 "grin😁" 格式）
                if (emojiText) {
                    // 使用正则表达式提取仅表情部分
                    const emojiMatch = emojiText.match(/(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu);
                    if (emojiMatch && emojiMatch.length > 0) {
                        // 只使用表情符号
                        emojiText = emojiMatch.join('');
                    }
                }
            }
        }
        
        // 如果无法从数据中获取，则尝试从元素中获取
        if (!emojiText) {
            const rawValue = target.getAttribute('data-value') || 
                           target.getAttribute('data-text') || 
                           target.textContent;
            
            // 同样提取纯表情部分
            if (rawValue) {
                const emojiMatch = rawValue.match(/(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu);
                if (emojiMatch && emojiMatch.length > 0) {
                    emojiText = emojiMatch.join('');
                } else {
                    emojiText = rawValue;
                }
            }
        }
        
        if (!emojiText) return;
        
        // 获取文本框
        const textarea = document.getElementById('textarea');
        if (!textarea) return;
        
        // 插入表情
        const cursorPos = textarea.selectionEnd;
        const value = textarea.value;
        textarea.value = value.substring(0, cursorPos) + emojiText + value.substring(cursorPos);
        
        // 重新设置光标位置
        textarea.selectionStart = textarea.selectionEnd = cursorPos + emojiText.length;
        textarea.focus();
        
        // 关闭表情面板
        const owoContainer = document.querySelector('.OwO');
        if (owoContainer && owoContainer.classList.contains('OwO-open')) {
            owoContainer.classList.remove('OwO-open');
        }
        
        // 显示通知
        showNotification(emojiText);
        
        // 如果设备支持触觉反馈
        if ('vibrate' in navigator) {
            try {
                navigator.vibrate(30);
            } catch (e) {}
        }
    }
    
    // 显示表情插入通知
    function showNotification(emojiText) {
        // 查找已有通知，如果存在则移除
        const existingNotification = document.querySelector('.emoji-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = 'emoji-notification';
        // 仅展示表情，不包含前缀文本
        notification.textContent = `已插入表情: ${emojiText}`;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 1000);
        }, 10);
    }
    
    // 增强表情元素
    function enhanceEmojiItems() {
        if (!emojiData) return;
        
        // 查找所有表情元素
        document.querySelectorAll('.OwO-item').forEach(item => {
            // 如果元素已处理过，跳过
            if (item.hasAttribute('data-processed')) return;
            
            const emojiName = item.getAttribute('title');
            const categoryElement = item.closest('.OwO-items');
            
            if (!emojiName || !categoryElement) return;
            
            const categoryName = categoryElement.getAttribute('data-name');
            
            if (categoryName && emojiData[categoryName] && 
                emojiData[categoryName].container && 
                emojiData[categoryName].container[emojiName]) {
                
                // 获取表情符号
                let emojiValue = emojiData[categoryName].container[emojiName];
                
                // 提取纯表情符号部分
                if (emojiValue) {
                    const emojiMatch = emojiValue.match(/(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu);
                    if (emojiMatch && emojiMatch.length > 0) {
                        emojiValue = emojiMatch.join('');
                    }
                }
                
                // 设置data-value属性
                item.setAttribute('data-value', emojiValue);
                item.setAttribute('data-processed', 'true');
                
                // 清空内容，只显示表情符号
                if (item.childNodes.length > 0) {
                    item.innerHTML = '';
                    item.textContent = emojiValue;
                }
                
                // 添加表情预览功能
                item.addEventListener('mouseenter', function() {
                    // 创建预览元素
                    let preview = this.querySelector('.OwO-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'OwO-preview';
                        preview.textContent = emojiValue;
                        preview.style.fontSize = categoryName === '文字' ? '14px' : '24px';
                        this.appendChild(preview);
                    }
                });
            }
        });
        
        // 添加加载指示器
        const owoBody = document.querySelector('.OwO-body');
        if (owoBody && !owoBody.querySelector('.OwO-loading')) {
            const loading = document.createElement('div');
            loading.className = 'OwO-loading';
            owoBody.appendChild(loading);
            
            // 一旦表情加载完成，隐藏加载指示器
            setTimeout(() => {
                loading.style.display = 'none';
            }, 800);
        }
        
        // 更新表情选择器选项卡名称
        updateTabNames();
    }
    
    // 更新表情选择器选项卡名称
    function updateTabNames() {
        const tabItems = document.querySelectorAll('.OwO-items-name-item');
        if (!tabItems.length || !emojiData) return;
        
        // 获取表情分类名称
        const categories = Object.keys(emojiData);
        
        // 更新选项卡文本
        tabItems.forEach((tab, index) => {
            if (categories[index]) {
                tab.textContent = categories[index];
            }
        });
    }
    
    // 清理评论区的表情前缀
    function cleanupCommentEmojis() {
        // 创建反向映射：从表情符号映射到表情名称
        const emojiToName = {};
        if (emojiData) {
            Object.keys(emojiData).forEach(category => {
                const container = emojiData[category].container;
                if (container) {
                    Object.keys(container).forEach(name => {
                        emojiToName[container[name]] = name;
                    });
                }
            });
        }
        
        // 处理函数 - 移除英文前缀，保留表情符号
        function processCommentHTML(commentElement) {
            if (!commentElement) return;
            
            // 获取当前HTML内容
            const htmlContent = commentElement.innerHTML;
            
            // 使用更完整的正则表达式匹配前缀+表情的组合
            // 1. 匹配 "word🤕"形式 (无空格)
            // 2. 匹配 "word 🤕"形式 (有空格)
            // 3. 匹配 "word:🤕"形式 (冒号分隔)
            // 4. 匹配 "word-🤕"形式 (连字符分隔)
            // 5. 匹配 "[word]🤕"形式 (方括号)
            const modifiedHtml = htmlContent
                // 匹配没有空格的情况：word+emoji
                .replace(/(\b[a-z][a-z0-9_]+)(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                // 匹配有空格的情况：word + emoji
                .replace(/(\b[a-z][a-z0-9_]+)\s+(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                // 匹配冒号情况：word:emoji
                .replace(/(\b[a-z][a-z0-9_]+):(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                // 匹配连字符情况：word-emoji
                .replace(/(\b[a-z][a-z0-9_]+)-(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                // 匹配方括号情况：[word]emoji
                .replace(/\[([a-z][a-z0-9_]+)\](\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2');
            
            // 只有在内容变化时才更新
            if (modifiedHtml !== htmlContent) {
                commentElement.innerHTML = modifiedHtml;
            }
            
            // 处理文本节点
            const textNodes = [];
            const walker = document.createTreeWalker(
                commentElement,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );
            
            let node;
            while (node = walker.nextNode()) {
                // 使用相同的正则表达式处理文本节点
                const text = node.nodeValue;
                const newText = text
                    .replace(/(\b[a-z][a-z0-9_]+)(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/(\b[a-z][a-z0-9_]+)\s+(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/(\b[a-z][a-z0-9_]+):(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/(\b[a-z][a-z0-9_]+)-(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/\[([a-z][a-z0-9_]+)\](\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2');
                
                if (newText !== text) {
                    node.nodeValue = newText;
                }
            }
        }
        
        // 处理评论内容区域
        function processCommentContent() {
            // 查找所有评论内容元素
            const commentContents = document.querySelectorAll('.comment-content');
            commentContents.forEach(content => {
                processCommentHTML(content);
            });
        }
        
        // 初始处理
        processCommentContent();
        
        // 设置MutationObserver以监视DOM变化
        const observer = new MutationObserver((mutations) => {
            let shouldProcess = false;
            
            // 检查是否有评论相关的变化
            mutations.forEach(mutation => {
                if (mutation.type === 'childList' && 
                    (mutation.target.classList.contains('comment-list') || 
                     mutation.target.closest('.comment-list') ||
                     mutation.target.classList.contains('comment-content'))) {
                    shouldProcess = true;
                }
            });
            
            if (shouldProcess) {
                processCommentContent();
            }
        });
        
        // 监视整个评论列表区域
        const commentList = document.querySelector('.comment-list');
        if (commentList) {
            observer.observe(commentList, { 
                childList: true, 
                subtree: true,
                characterData: true
            });
        }
    }
    
    // 加载表情数据
    loadEmojiData();
    
    // 在DOM加载完成后设置事件监听
    document.addEventListener('DOMContentLoaded', function() {
        // 添加全局事件委托处理表情点击
        document.body.addEventListener('click', handleEmojiClick, true);
        
        // 初始化OwO表情
        initOwO();
        
        // 处理评论区表情
        cleanupCommentEmojis();
        
        // 监听表情面板打开事件
        document.addEventListener('click', function(e) {
            if (e.target.closest('.OwO-logo')) {
                setTimeout(enhanceEmojiItems, 100);
            }
        });
        
        // 监听OwO初始化完成事件
        document.addEventListener('owo-initialized', function() {
            setTimeout(enhanceEmojiItems, 100);
        });
    });
    
    // 初始化表情选择器
    function initOwO() {
        if (typeof OwO === 'undefined') return;
        
        setTimeout(() => {
            try {
                new OwO({
                    logo: '<i class="fas fa-smile"></i> 表情',
                    container: document.querySelector('.main-comment-form .OwO'),
                    target: document.getElementById('textarea'),
                    api: window.location.origin + '/OwO.json',
                    position: 'down',
                    width: '100%',
                    maxHeight: '250px'
                });
                
                setTimeout(enhanceEmojiItems, 100);
            } catch (e) {}
        }, 0);
    }
})(); 