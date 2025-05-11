/**
 * 额外的表情处理脚本
 * 用于确保评论中的表情符号正确显示，去除前缀
 */
(function() {
    // 处理评论表情前缀的函数
    function cleanupEmojiPrefixes() {
        // 找到所有评论内容区域
        const commentContents = document.querySelectorAll('.comment-content');
        
        commentContents.forEach(content => {
            // 获取评论文本内容
            const htmlContent = content.innerHTML;
            
            // 使用更完整的正则表达式匹配前缀+表情的组合
            // 1. 匹配 "word🤕"形式 (无空格)
            // 2. 匹配 "word 🤕"形式 (有空格)
            // 3. 匹配 "word:🤕"形式 (冒号分隔)
            // 4. 匹配 "word-🤕"形式 (连字符分隔)
            // 5. 匹配 "[word]🤕"形式 (方括号)
            const processedHtml = htmlContent
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
            
            // 如果内容有变化，更新评论区内容
            if (processedHtml !== htmlContent) {
                content.innerHTML = processedHtml;
            }
            
            // 处理文本节点
            const walker = document.createTreeWalker(
                content,
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
        });
    }
    
    // 添加CSS样式来隐藏可能的前缀
    function addHidePrefixStyle() {
        const style = document.createElement('style');
        style.textContent = `
            /* 隐藏表情前的文本前缀 */
            .comment-content {
                word-break: break-word;
            }
            
            /* 强制将表情前的单词隐藏 */
            .comment-content span.emoji-prefix,
            .comment-content .emoji-word,
            .comment-content [class*="emoji-name"],
            .comment-content [data-emoji-name],
            .comment-content [data-emoji-prefix] {
                position: absolute !important;
                width: 1px !important;
                height: 1px !important;
                padding: 0 !important;
                margin: -1px !important;
                overflow: hidden !important;
                clip: rect(0, 0, 0, 0) !important;
                white-space: nowrap !important;
                border: 0 !important;
                opacity: 0 !important;
                visibility: hidden !important;
                display: none !important;
            }
            
            /* 使用匹配选择器直接隐藏前缀 */
            .comment-content *:not(img) + emoji,
            .comment-content *:not(img) + .emoji,
            .comment-content span:not(.emoji) + .emoji {
                font-size: 0 !important;
                letter-spacing: -1em !important;
                color: transparent !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    // 应用元素级检查
    function processElementContent() {
        // 检查所有可能包含表情的元素
        document.querySelectorAll('.comment-content *').forEach(el => {
            if (el.childNodes.length > 0) {
                for (let i = 0; i < el.childNodes.length; i++) {
                    const node = el.childNodes[i];
                    if (node.nodeType === Node.TEXT_NODE) {
                        // 使用同一套正则表达式
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
            }
        });
    }
    
    // 在页面加载完成后执行
    function onDOMReady() {
        // 添加隐藏前缀的样式
        addHidePrefixStyle();
        
        // 初始运行清理操作
        cleanupEmojiPrefixes();
        processElementContent();
        
        // 处理输入框实时检查
        const textarea = document.getElementById('textarea');
        if (textarea) {
            // 实时监听输入事件
            textarea.addEventListener('input', function() {
                const text = this.value;
                const newText = text
                    .replace(/(\b[a-z][a-z0-9_]+)(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/(\b[a-z][a-z0-9_]+)\s+(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/(\b[a-z][a-z0-9_]+):(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/(\b[a-z][a-z0-9_]+)-(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                    .replace(/\[([a-z][a-z0-9_]+)\](\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2');
                
                if (newText !== text) {
                    const cursorPos = this.selectionStart;
                    this.value = newText;
                    this.selectionStart = this.selectionEnd = cursorPos;
                }
            });
            
            // 在粘贴时处理
            textarea.addEventListener('paste', function() {
                setTimeout(() => {
                    const text = this.value;
                    const newText = text
                        .replace(/(\b[a-z][a-z0-9_]+)(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                        .replace(/(\b[a-z][a-z0-9_]+)\s+(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                        .replace(/(\b[a-z][a-z0-9_]+):(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                        .replace(/(\b[a-z][a-z0-9_]+)-(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
                        .replace(/\[([a-z][a-z0-9_]+)\](\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2');
                    
                    if (newText !== text) {
                        const cursorPos = this.selectionStart;
                        this.value = newText;
                        this.selectionStart = this.selectionEnd = cursorPos;
                    }
                }, 0);
            });
        }
        
        // 如果有评论列表，设置观察器监听变化
        const commentList = document.querySelector('.comment-list');
        if (commentList && window.MutationObserver) {
            const observer = new MutationObserver(mutations => {
                // 检测变化后处理评论内容
                setTimeout(() => {
                    cleanupEmojiPrefixes();
                    processElementContent();
                }, 100);
            });
            
            // 观察评论列表的变化
            observer.observe(commentList, {
                childList: true,
                subtree: true,
                characterData: true
            });
        }
        
        // 另外，定期检查一次确保一致性
        setInterval(() => {
            cleanupEmojiPrefixes();
            processElementContent();
        }, 2000);
    }
    
    // 当DOM加载完成后执行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', onDOMReady);
    } else {
        onDOMReady();
    }
})(); 