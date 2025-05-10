/* 现代化评论系统 JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    console.log('评论系统初始化开始...');
    
    // 全局变量存储当前回复的评论信息
    const replyData = {
        commentId: null,
        parentId: null,
        replyTo: null
    };
    
    // 初始评论表单的表情选择器
    const mainEmojiContainer = document.querySelector('.comment-emoticons .OwO');
    const mainTextarea = document.getElementById('textarea');
    if (mainEmojiContainer && mainTextarea && typeof OwO !== 'undefined') {
        console.log('初始化主评论表单表情选择器');
        new OwO({
            logo: '<i class="fas fa-smile"></i> 表情',
            container: mainEmojiContainer,
            target: mainTextarea,
            api: '/usr/themes/butterfly/OwO.json',
            position: 'down',
        });
    }
    
    // 给评论添加高亮效果
    highlightTargetComment();
    
    // 处理评论回复按钮
    initReplyButtons();
    
    // 添加动画效果
    initAnimations();
    
    /**
     * 高亮目标评论
     */
    function highlightTargetComment() {
        console.log('检查是否需要高亮目标评论');
        const hash = window.location.hash;
        if (hash && hash.startsWith('#comment-')) {
            const targetComment = document.querySelector(hash);
            if (targetComment) {
                console.log('高亮目标评论:', hash);
                targetComment.classList.add('comment-highlight');
                setTimeout(() => {
                    targetComment.scrollIntoView({behavior: 'smooth', block: 'center'});
                }, 500);
            }
        }
    }
    
    /**
     * 初始化回复按钮
     */
    function initReplyButtons() {
        console.log('初始化回复按钮');
        const replyLinks = document.querySelectorAll('.comment-reply a');
        console.log('找到回复按钮数量:', replyLinks.length);
        
        if (!replyLinks.length) return;
        
        // 为每个回复链接添加自定义事件
        replyLinks.forEach((link, index) => {
            // 移除链接上的所有现有事件
            const oldLink = link;
            const newLink = oldLink.cloneNode(true);
            oldLink.parentNode.replaceChild(newLink, oldLink);
            
            // 为新链接添加点击事件
            newLink.addEventListener('click', function(e) {
                console.log(`点击了第${index+1}个回复按钮`);
                e.preventDefault();
                e.stopPropagation();
                
                try {
                    // 获取评论元素和ID
                    const commentItem = this.closest('[id^="comment-"]');
                    if (!commentItem) {
                        console.error('未找到评论元素');
                        return;
                    }
                    
                    const commentId = commentItem.id;
                    const parentId = commentId.replace('comment-', '');
                    
                    // 获取评论作者名称
                    const authorElement = commentItem.querySelector('.vnick');
                    if (!authorElement) {
                        console.error('未找到评论作者元素');
                        return;
                    }
                    
                    const replyTo = authorElement.textContent.trim();
                    console.log('回复信息:', {commentId, parentId, replyTo});
                    
                    // 创建回复表单
                    createReplyForm(commentId, parentId, replyTo);
                } catch (error) {
                    console.error('回复处理错误:', error);
                }
            });
        });
        
        // 隐藏所有取消回复按钮
        document.querySelectorAll('.cancel-comment-reply').forEach(btn => {
            btn.style.display = 'none';
        });
    }
    
    /**
     * 创建回复表单
     */
    function createReplyForm(commentId, parentId, replyTo) {
        console.log('创建回复表单:', {commentId, parentId, replyTo});
        
        // 移除所有现有回复表单
        removeAllReplyForms();
        
        // 获取表单模板
        const template = document.getElementById('reply-form-template');
        if (!template) {
            console.error('找不到回复表单模板');
            return;
        }
        
        // 克隆模板创建新表单
        const newForm = template.cloneNode(true);
        newForm.id = 'reply-form-' + parentId;
        newForm.style.display = 'block';
        console.log('创建了回复表单:', newForm.id);
        
        // 设置回复对象信息
        const targetComment = document.getElementById(commentId);
        if (!targetComment) {
            console.error('找不到目标评论:', commentId);
            return;
        }
        
        const nameElement = newForm.querySelector('.reply-to-name');
        if (nameElement) {
            nameElement.textContent = replyTo;
        }
        
        // 保存回复数据
        replyData.commentId = commentId;
        replyData.parentId = parentId;
        replyData.replyTo = replyTo;
        
        // 设置用户头像
        let userAvatar = '/usr/themes/butterfly/img/default-avatar.png';
        const avatarImg = document.querySelector('.comment-login-info img');
        if (avatarImg) {
            userAvatar = avatarImg.getAttribute('src');
        }
        
        const replyAvatar = newForm.querySelector('.reply-user-avatar');
        if (replyAvatar) {
            replyAvatar.src = userAvatar;
        }
        
        // 插入回复表单到评论元素后
        targetComment.appendChild(newForm);
        
        // 绑定关闭按钮事件
        const closeBtn = newForm.querySelector('.close-reply-form');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                console.log('关闭回复表单');
                removeAllReplyForms();
            });
        }
        
        // 绑定提交按钮事件
        const submitBtn = newForm.querySelector('.submit-reply');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                console.log('提交回复');
                submitReply(newForm, parentId);
            });
        }
        
        // 初始化表情选择器
        const emojiContainer = newForm.querySelector('.reply-emoticons');
        const textarea = newForm.querySelector('.reply-textarea-content');
        
        if (emojiContainer && textarea && typeof OwO !== 'undefined') {
            console.log('初始化回复表单表情选择器');
            const OwOContainer = document.createElement('div');
            OwOContainer.className = 'OwO';
            emojiContainer.appendChild(OwOContainer);
            
            new OwO({
                logo: '<i class="fas fa-smile"></i> 表情',
                container: OwOContainer,
                target: textarea,
                api: '/usr/themes/butterfly/OwO.json',
                position: 'down',
            });
        }
        
        // 聚焦到回复文本框
        if (textarea) {
            textarea.focus();
        }
        
        // 滚动到回复表单位置
        newForm.scrollIntoView({behavior: 'smooth', block: 'center'});
    }
    
    /**
     * 移除所有回复表单
     */
    function removeAllReplyForms() {
        console.log('移除所有回复表单');
        const replyForms = document.querySelectorAll('[id^="reply-form-"]');
        console.log('找到', replyForms.length, '个回复表单');
        
        replyForms.forEach(form => {
            if (form.parentNode) {
                console.log('移除回复表单:', form.id);
                form.parentNode.removeChild(form);
            }
        });
    }
    
    /**
     * 提交回复
     */
    function submitReply(replyForm, parentId) {
        console.log('提交回复:', parentId);
        const mainForm = document.getElementById('comment-form');
        const replyText = replyForm.querySelector('.reply-textarea-content').value;
        
        if (!replyText.trim()) {
            showMessage('请输入回复内容', 'error');
            return;
        }
        
        // 将回复内容填入主表单
        const mainTextarea = mainForm.querySelector('textarea[name="text"]');
        if (mainTextarea) {
            mainTextarea.value = replyText;
        }
        
        // 设置父评论ID
        const parentInput = document.getElementById('comment-parent');
        if (parentInput) {
            parentInput.value = parentId;
        }
        
        // 提交表单
        console.log('提交主表单');
        mainForm.submit();
        
        // 显示提交中消息
        showMessage('回复提交中...', 'info');
    }
    
    /**
     * 显示消息提示
     */
    function showMessage(message, type = 'info') {
        console.log('显示消息:', message, type);
        let msgDiv = document.getElementById('comment-message');
        if (!msgDiv) {
            msgDiv = document.createElement('div');
            msgDiv.id = 'comment-message';
            document.querySelector('.comments-body').appendChild(msgDiv);
        }
        
        msgDiv.className = 'comment-message ' + type;
        msgDiv.textContent = message;
        msgDiv.style.display = 'block';
        
        setTimeout(() => {
            msgDiv.style.display = 'none';
        }, 3000);
    }
    
    /**
     * 初始化动画效果
     */
    function initAnimations() {
        // 鼠标悬停效果
        document.addEventListener('mouseover', function(e) {
            const commentBody = e.target.closest('.comment-body');
            if (commentBody) {
                commentBody.classList.add('comment-hover');
            }
        });
        
        document.addEventListener('mouseout', function(e) {
            const commentBody = e.target.closest('.comment-body');
            if (commentBody) {
                commentBody.classList.remove('comment-hover');
            }
        });
        
        // 监听滚动为评论添加动画
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('comment-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        document.querySelectorAll('.comment-body').forEach(comment => {
            observer.observe(comment);
            comment.classList.add('comment-animate');
        });
    }
    
    console.log('评论系统初始化完成');
}); 