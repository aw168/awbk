/**
 * OwO表情兼容脚本
 * 用于确保OwO表情选择器在评论系统中正常工作
 */

(function() {
  // 确保全局OwO对象存在
  window.OwO = window.OwO || function(option) {
    // 如果主要的OwO.min.js未加载，提供基本功能
    if (typeof OwO.prototype.init !== 'function') {
      console.warn('OwO主要脚本未加载，使用兼容模式');
      
      return {
        container: option.container || null,
        target: option.target || null,
        position: option.position || 'down',
        width: option.width || '100%',
        maxHeight: option.maxHeight || '250px',
        api: option.api || null,
        
        // 占位方法
        init: function() {
          console.log('OwO表情选择器已初始化(兼容模式)');
          return this;
        },
        toggle: function() {
          console.log('OwO表情选择器切换显示/隐藏(兼容模式)');
          return this;
        }
      };
    }
    
    // 否则返回正常的OwO实例
    return new OwO(option);
  };

  // 封装安全调用函数
  function safeCall(fn) {
    try {
      return fn();
    } catch (e) {
      console.warn('OwO兼容模式错误:', e);
      return null;
    }
  }

  // 处理评论区域中的表情前缀
  function cleanupEmojiPrefixes() {
    // 处理评论内容
    const commentContents = document.querySelectorAll('.comment-content');
    commentContents.forEach(content => {
      // 使用正则表达式处理表情前缀
      const htmlContent = content.innerHTML;
      
      // 使用更完整的正则表达式匹配前缀+表情的组合
      // 1. 匹配 "word🤕"形式 (无空格)
      // 2. 匹配 "word 🤕"形式 (有空格)
      // 3. 匹配 "word:🤕"形式 (冒号分隔)
      // 4. 匹配 "word-🤕"形式 (连字符分隔)
      // 5. 匹配 "[word]🤕"形式 (方括号)
      const cleanedHtml = htmlContent
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
      
      // 只有在内容实际改变时才更新
      if (cleanedHtml !== htmlContent) {
        content.innerHTML = cleanedHtml;
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
    
    // 监听评论区变化
    const commentList = document.querySelector('.comment-list');
    if (commentList && window.MutationObserver) {
      const observer = new MutationObserver(function(mutations) {
        let shouldProcess = false;
        
        // 检查是否有评论相关的内容变化
        for (let mutation of mutations) {
          if (mutation.type === 'childList' && 
              (mutation.target.classList.contains('comment-list') || 
               mutation.target.closest('.comment-list') ||
               mutation.target.classList.contains('comment-content'))) {
            shouldProcess = true;
            break;
          }
        }
        
        if (shouldProcess) {
          setTimeout(cleanupEmojiPrefixes, 100);
        }
      });
      
      // 开始监听评论列表变化
      observer.observe(commentList, {
        childList: true,
        subtree: true,
        characterData: true
      });
    }
  }

  // 当文档加载完成后检查评论区
  document.addEventListener('DOMContentLoaded', function() {
    // 延迟加载，确保所有元素都已渲染
    setTimeout(function() {
      safeCall(function() {
        // 检查页面是否有评论区
        const commentTextarea = document.getElementById('textarea');
        
        // 如果找到了评论区但未找到OwO按钮，初始化OwO
        if (commentTextarea && !document.querySelector('.OwO-logo')) {
          console.log('找到评论区，但未找到OwO按钮，尝试初始化');
          
          // 检查OwO.min.js是否已加载
          if (typeof OwO !== 'function' || typeof OwO.prototype.init !== 'function') {
            console.log('OwO库未加载，尝试加载...');
            
            // 先尝试使用相对路径
            let scriptSrc = 'js/OwO.min.js';
            
            // 检查是否可以从当前页面的theme URL构建路径
            const themeUrlElements = document.querySelectorAll('link[href*="/themes/butterfly/"]');
            if (themeUrlElements.length > 0) {
              const hrefAttr = themeUrlElements[0].getAttribute('href');
              const themeUrlMatch = hrefAttr.match(/(.*\/themes\/butterfly\/)/);
              if (themeUrlMatch && themeUrlMatch[1]) {
                scriptSrc = themeUrlMatch[1] + 'js/OwO.min.js';
              }
            }
            
            // 创建并加载脚本
            const script = document.createElement('script');
            script.src = scriptSrc;
            script.onload = function() {
              console.log('OwO库加载成功，初始化表情选择器');
              initOwO();
            };
            script.onerror = function() {
              console.warn('无法加载OwO库，尝试使用兼容模式');
              // 仍然尝试初始化，使用兼容模式
              initOwO();
            };
            document.body.appendChild(script);
          } else {
            // OwO已加载，直接初始化
            initOwO();
          }
        }
        
        // 处理评论区表情前缀
        cleanupEmojiPrefixes();
      });
    }, 1000);
  });

  // 初始化OwO的函数
  function initOwO() {
    safeCall(function() {
      // 获取评论文本区域
      const commentTextarea = document.getElementById('textarea');
      if (!commentTextarea) return;
      
      // 如果已存在OwO按钮，不重复创建
      if (document.querySelector('.OwO-logo')) return;
      
      // 查找评论表单
      const commentForm = commentTextarea.closest('form');
      if (!commentForm) return;
      
      // 查找表单中是否已有OwO容器
      let owoContainer = commentForm.querySelector('.OwO');
      
      // 如果没有容器，创建一个
      if (!owoContainer) {
        owoContainer = document.createElement('div');
        owoContainer.className = 'OwO';
        
        // 尝试找到合适的位置插入
        const buttonsContainer = commentForm.querySelector('.comments-buttons');
        if (buttonsContainer) {
          buttonsContainer.parentNode.insertBefore(owoContainer, buttonsContainer);
        } else {
          // 如果找不到特定位置，尝试在文本区域后插入
          commentTextarea.parentNode.parentNode.appendChild(owoContainer);
        }
      }
      
      // 处理评论框和表情选择
      commentTextarea.addEventListener('input', function(e) {
        // 在用户输入时检查并清理表情前缀
        const text = this.value;
        const newText = text
          .replace(/(\b[a-z][a-z0-9_]+)(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
          .replace(/(\b[a-z][a-z0-9_]+)\s+(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
          .replace(/(\b[a-z][a-z0-9_]+):(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
          .replace(/(\b[a-z][a-z0-9_]+)-(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2')
          .replace(/\[([a-z][a-z0-9_]+)\](\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu, '$2');
        
        if (newText !== text) {
          // 只有当内容变化时更新，避免光标位置重置
          const cursorPos = this.selectionStart;
          this.value = newText;
          this.selectionStart = this.selectionEnd = cursorPos;
        }
      });
      
      // 尝试初始化OwO
      try {
        if (typeof OwO === 'function') {
          // 保存原始的OwO.prototype.insertToTextarea方法
          const originalInsertMethod = OwO.prototype.insertToTextarea;
          
          // 修改OwO.prototype.insertToTextarea方法，确保只插入表情
          if (originalInsertMethod) {
            OwO.prototype.insertToTextarea = function(text) {
              // 检查并提取纯表情部分
              const emojiMatch = text.match(/(\p{Emoji_Presentation}|\p{Emoji}\uFE0F)/gu);
              if (emojiMatch && emojiMatch.length > 0) {
                // 只使用提取的表情
                originalInsertMethod.call(this, emojiMatch.join(''));
              } else {
                // 如果未找到表情，则使用原文本
                originalInsertMethod.call(this, text);
              }
            };
          }
          
          new OwO({
            logo: '<i class="fas fa-smile"></i> 表情',
            container: owoContainer,
            target: commentTextarea,
            api: '/usr/themes/butterfly/OwO.json',
            position: 'down',
            width: '100%',
            maxHeight: '250px'
          });
          console.log('OwO表情选择器初始化成功');
        } else {
          console.warn('OwO对象不可用，仅添加了占位按钮');
          
          // 添加一个简单的占位按钮
          owoContainer.innerHTML = '<div class="OwO-logo"><i class="fas fa-smile"></i> <span>表情</span></div>';
          
          // 添加点击事件显示提示
          owoContainer.querySelector('.OwO-logo').addEventListener('click', function() {
            alert('表情功能暂时不可用，请刷新页面重试');
          });
        }
      } catch (e) {
        console.warn('初始化OwO时出错:', e);
        
        // 添加一个简单的占位按钮
        owoContainer.innerHTML = '<div class="OwO-logo"><i class="fas fa-smile"></i> <span>表情</span></div>';
      }
    });
  }
})(); 