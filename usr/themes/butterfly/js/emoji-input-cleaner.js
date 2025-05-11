/**
 * 专门处理评论输入框中的表情前缀
 * 确保输入框中只显示表情符号，不显示前缀
 */
(function() {
    // 处理表情前缀的函数
    function cleanupInputEmoji() {
        const textarea = document.getElementById('textarea');
        if (!textarea) return;
        
        // 原始值
        const originalValue = textarea.value;
        // 表情匹配正则
        const emojiRegex = /(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu;
        
        // 清理各种格式的前缀+表情组合
        const cleanedValue = originalValue
            // word + emoji (无空格)
            .replace(/(\b[a-z][a-z0-9_]+)(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
            // word + emoji (有空格)
            .replace(/(\b[a-z][a-z0-9_]+)\s+(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
            // word:emoji (冒号)
            .replace(/(\b[a-z][a-z0-9_]+):(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
            // word-emoji (连字符)
            .replace(/(\b[a-z][a-z0-9_]+)-(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
            // [word]emoji (方括号)
            .replace(/\[([a-z][a-z0-9_]+)\](\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
            // "word"emoji (引号)
            .replace(/"([a-z][a-z0-9_]+)"(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
            // 'word'emoji (单引号)
            .replace(/'([a-z][a-z0-9_]+)'(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
            // (word)emoji (圆括号)
            .replace(/\(([a-z][a-z0-9_]+)\)(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2');
        
        // 仅当值变化时更新
        if (cleanedValue !== originalValue) {
            // 保存光标位置
            const cursorPosition = textarea.selectionStart;
            
            // 更新文本并尝试保持光标位置
            textarea.value = cleanedValue;
            try {
                // 重设光标位置
                textarea.selectionStart = textarea.selectionEnd = 
                    Math.min(cursorPosition, cleanedValue.length);
            } catch (e) {
                // 忽略可能的错误
            }
        }
    }
    
    // 设置监听器
    function setupInputListeners() {
        const textarea = document.getElementById('textarea');
        if (!textarea) return;
        
        // 监听各种可能的输入事件
        const events = ['input', 'change', 'keyup', 'paste', 'focus', 'blur'];
        
        events.forEach(eventType => {
            textarea.addEventListener(eventType, function(e) {
                // 延迟执行以确保获取最新内容
                setTimeout(cleanupInputEmoji, 0);
            });
        });
        
        // 监听表情选择器事件
        document.addEventListener('click', function(e) {
            if (e.target.closest('.OwO-item')) {
                // 表情被点击，延迟处理
                setTimeout(cleanupInputEmoji, 10);
            }
        }, true);
        
        // 监听整个表单提交
        const form = textarea.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // 提交前再次确保清理
                cleanupInputEmoji();
            });
        }
        
        // 定期检查
        setInterval(cleanupInputEmoji, 1000);
    }
    
    // 检查修改OwO插入方法
    function patchOwoInsertMethod() {
        if (typeof OwO !== 'function' || !OwO.prototype) return;
        
        // 保存原始方法
        const originalInsert = OwO.prototype.insertToTextarea;
        if (!originalInsert) return;
        
        // 覆盖插入方法
        OwO.prototype.insertToTextarea = function(text) {
            // 提取纯表情部分
            const emojiMatch = text.match(/(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu);
            
            if (emojiMatch && emojiMatch.length > 0) {
                // 只使用表情部分
                originalInsert.call(this, emojiMatch.join(''));
            } else {
                // 没有找到表情，使用原始文本
                originalInsert.call(this, text);
            }
            
            // 插入后再次清理
            setTimeout(cleanupInputEmoji, 0);
        };
    }
    
    // 在DOM加载完成后执行
    function init() {
        // 尝试修改OwO插入方法
        patchOwoInsertMethod();
        
        // 设置输入监听器
        setupInputListeners();
        
        // 初始清理
        cleanupInputEmoji();
        
        // 监听DOM变化，处理可能的动态加载表情选择器
        if (window.MutationObserver) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length) {
                        // 检查是否有表情选择器被添加
                        for (let i = 0; i < mutation.addedNodes.length; i++) {
                            const node = mutation.addedNodes[i];
                            if (node.nodeType === 1 && (
                                node.classList.contains('OwO') || 
                                node.querySelector('.OwO')
                            )) {
                                // 表情选择器被添加，尝试再次修补OwO方法
                                patchOwoInsertMethod();
                                break;
                            }
                        }
                    }
                });
            });
            
            // 监听整个文档
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    // 根据文档状态执行初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(); 