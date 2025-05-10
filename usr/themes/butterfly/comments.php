<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="comments" class="comments-system">
    <?php $this->comments()->to($comments); ?>

    <div class="comments-header">
        <h3><i class="fas fa-comments"></i><?php _e('评论'); ?></h3>
        <span class="comments-counter"><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></span>
    </div>

    <div class="comments-body">
        <!-- 固定的主评论框 - 始终显示在文章下方 -->
        <div class="fixed-comment-form">
            <h4 class="form-title"><?php _e('发表评论'); ?></h4>
            <?php if($this->allow('comment') && $this->options->CloseComments == 'off'): ?>
                <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
                    <?php if($this->user->hasLogin()): ?>
                        <div class="comment-login-info">
                            <?php _e('登录身份: '); ?>
                            <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?>
                                <?php if($this->user->group == 'administrator'): ?> <span class="vtag vmaster">博主</span>
                                <?php elseif($this->user->group == 'editor'): ?> <span class="vtag vauth">编辑</span>
                                <?php elseif($this->user->group == 'contributor'): ?> <span class="vtag vauth">贡献者</span>
                                <?php elseif($this->user->group == 'subscriber'): ?> <span class="vtag vvisitor">关注者</span>
                                <?php elseif($this->user->group == 'visitor'): ?> <span class="vtag vvisitor">访客</span>
                                <?php endif ?>
                            </a>
                            <a href="<?php $this->options->logoutUrl(); ?>" title="退出" class="logout"><i class="fas fa-sign-out-alt"></i> 退出</a>
                        </div>
                    <?php else: ?>
                        <div class="commcomments-info">
                            <input type="text" name="author" id="author" placeholder="<?php _e('昵称 *'); ?>" value="<?php $this->remember('author'); ?>" required />
                            <input type="email" name="mail" id="mail" placeholder="<?php _e('邮箱 *'); ?>" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> />
                            <input type="url" name="url" id="url" placeholder="<?php _e('网站'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
                        </div>
                    <?php endif; ?>
                    
                    <div class="comments-textarea">
                        <textarea name="text" id="textarea" class="textarea" rows="6" placeholder="<?php _e('说点什么吧...'); ?>" required><?php $this->remember('text'); ?></textarea>
                    </div>
                    
                    <div class="comment-tools">
                        <div class="comment-emoticons">
                            <div class="OwO"></div>
                        </div>
                    </div>
                    
                    <div class="comments-buttons">
                        <button type="submit" class="submit"><i class="fas fa-paper-plane"></i> <?php _e('发表评论'); ?></button>
                    </div>

                    <?php RecapOutPut(false); ?>
                    
                    <!-- 隐藏的父评论ID字段，用于回复功能 -->
                    <input type="hidden" name="parent" id="comment-parent" value="0" />
                </form>
            <?php else: ?>
                <div class="comments-closed">
                    <i class="fas fa-comment-slash"></i>
                    <p><?php _e('评论已关闭'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- 评论列表 -->
        <?php if ($comments->have()): ?>
            <h4 class="comment-list-header"><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></h4>
            
            <ol class="comment-list">
                <?php $comments->listComments(); ?>
            </ol>
            
            <?php $comments->pageNav('&laquo;', '&raquo;'); ?>
        <?php else: ?>
            <div class="no-comments">
                <i class="far fa-comment-dots"></i>
                <p><?php _e('暂无评论，快来抢沙发吧~'); ?></p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- 回复表单模板 - 用于动态创建回复框 -->
    <div id="reply-form-template" class="reply-form-template" style="display: none;">
        <div class="reply-form">
            <div class="reply-avatar">
                <img src="/usr/themes/butterfly/img/default-avatar.png" alt="回复头像" class="reply-user-avatar">
            </div>
            <div class="reply-content">
                <div class="reply-header">
                    <span class="replying-to">回复给 <strong class="reply-to-name">用户</strong>：</span>
                </div>
                <div class="reply-textarea">
                    <textarea class="textarea reply-textarea-content" placeholder="回复内容..."></textarea>
                </div>
                <div class="reply-tools">
                    <div class="reply-emoticons">
                        <!-- 表情选择器将在这里动态创建 -->
                    </div>
                </div>
                <div class="reply-buttons">
                    <button type="button" class="submit-reply"><i class="fas fa-reply"></i> 回复</button>
                </div>
            </div>
            <span class="close-reply-form" title="关闭回复框"><i class="fas fa-times"></i></span>
        </div>
    </div>
    
    <!-- 默认的Typecho回复框容器 - 我们不会实际使用它，但保留它是为了兼容性 -->
    <div id="<?php $this->respondId(); ?>" style="display: none;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 全局变量存储当前回复的评论信息
    const replyData = {
        commentId: null,
        parentId: null,
        replyTo: null
    };
    
    // 初始化表情选择器
    initOwO(document.querySelector('.comment-emoticons .OwO'), document.getElementById('textarea'));
    
    // 给评论添加高亮效果
    highlightTargetComment();
    
    // 完全替换默认的回复链接行为
    replaceDefaultReplyLinks();
    
    // 添加动画效果
    initAnimations();
    
    /**
     * 初始化表情选择器
     */
    function initOwO(container, target) {
        if (typeof OwO !== 'undefined' && container && target) {
            new OwO({
                logo: '<i class="fas fa-smile"></i> 表情',
                container: container,
                target: target,
                api: '/usr/themes/butterfly/OwO.json',
                position: 'down',
            });
        }
    }
    
    /**
     * 高亮目标评论
     */
    function highlightTargetComment() {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#comment-')) {
            const targetComment = document.querySelector(hash);
            if (targetComment) {
                targetComment.classList.add('comment-highlight');
                setTimeout(() => {
                    targetComment.scrollIntoView({behavior: 'smooth', block: 'center'});
                }, 500);
            }
        }
    }
    
    /**
     * 完全替换默认的回复链接行为
     */
    function replaceDefaultReplyLinks() {
        console.log('初始化回复按钮...');
        // 找到所有回复链接
        const replyLinks = document.querySelectorAll('.comment-reply a');
        console.log('找到回复按钮数量:', replyLinks.length);
        
        if (!replyLinks.length) return;
        
        // 为每个回复链接添加自定义事件
        replyLinks.forEach((link, index) => {
            console.log(`处理第${index+1}个回复按钮`);
            
            // 删除原有事件监听器
            const oldLink = link;
            const newLink = oldLink.cloneNode(true);
            oldLink.parentNode.replaceChild(newLink, oldLink);
            
            // 为新链接添加自定义点击事件
            newLink.addEventListener('click', function(e) {
                console.log('回复按钮被点击');
                e.preventDefault();
                e.stopPropagation();
                
                try {
                    // 获取评论ID和回复对象名称
                    const commentItem = this.closest('[id^="comment-"]');
                    console.log('找到评论元素:', commentItem);
                    
                    if (!commentItem) {
                        console.error('未找到包含评论ID的元素');
                        return false;
                    }
                    
                    const commentId = commentItem.id;
                    const parentId = commentId.replace('comment-', '');
                    
                    // 查找评论作者名称
                    const authorElement = commentItem.querySelector('.comment-author .vnick');
                    if (!authorElement) {
                        console.error('未找到评论作者元素');
                        return false;
                    }
                    
                    const replyTo = authorElement.textContent.trim();
                    console.log('回复信息:', {commentId, parentId, replyTo});
                    
                    // 创建回复表单
                    createReplyForm(commentId, parentId, replyTo);
                } catch (error) {
                    console.error('处理回复点击时出错:', error);
                }
                
                return false;
            });
        });
    }
    
    /**
     * 创建回复表单
     */
    function createReplyForm(commentId, parentId, replyTo) {
        console.log('开始创建回复表单:', {commentId, parentId, replyTo});
        
        // 先移除所有已存在的回复表单
        removeAllReplyForms();
        
        // 从模板创建回复表单
        const template = document.getElementById('reply-form-template');
        if (!template) {
            console.error('未找到回复表单模板');
            return;
        }
        
        const newForm = template.cloneNode(true);
        newForm.id = 'reply-form-' + parentId;
        newForm.style.display = 'block';
        console.log('回复表单创建成功:', newForm.id);
        
        // 设置表单数据
        const targetComment = document.getElementById(commentId);
        if (!targetComment) {
            console.error('未找到目标评论元素:', commentId);
            return;
        }
        
        // 设置回复对象名称
        const nameElement = newForm.querySelector('.reply-to-name');
        if (nameElement) {
            nameElement.textContent = replyTo;
        }
        
        // 保存回复数据
        replyData.commentId = commentId;
        replyData.parentId = parentId;
        replyData.replyTo = replyTo;
        
        // 设置用户头像
        let userAvatar = '/usr/themes/butterfly/img/default-avatar.png'; // 默认头像
        const loggedInAvatarImg = document.querySelector('.comment-login-info img');
        if (loggedInAvatarImg) {
            userAvatar = loggedInAvatarImg.getAttribute('src');
        }
        
        const avatarImg = newForm.querySelector('.reply-user-avatar');
        if (avatarImg) {
            avatarImg.src = userAvatar;
        }
        
        // 移除display:none样式，确保表单可见
        newForm.removeAttribute('style');
        newForm.style.display = 'block';
        
        // 插入到目标评论后
        console.log('准备插入回复表单到评论:', commentId);
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
        
        // 初始化回复框中的表情选择器
        const emojiContainer = newForm.querySelector('.reply-emoticons');
        const textarea = newForm.querySelector('.reply-textarea-content');
        
        if (emojiContainer && textarea) {
            // 创建表情选择器
            const OwOContainer = document.createElement('div');
            OwOContainer.className = 'OwO';
            emojiContainer.appendChild(OwOContainer);
            
            // 初始化表情选择器
            initOwO(OwOContainer, textarea);
            
            // 聚焦到回复框
            textarea.focus();
        }
        
        // 滚动到回复框位置
        newForm.scrollIntoView({behavior: 'smooth', block: 'center'});
        console.log('回复表单创建完成');
    }
    
    /**
     * 移除所有回复表单
     */
    function removeAllReplyForms() {
        console.log('正在移除所有回复表单');
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
        const mainForm = document.getElementById('comment-form');
        const replyText = replyForm.querySelector('.reply-textarea-content').value;
        
        if (!replyText.trim()) {
            showMessage('请输入回复内容', 'error');
            return;
        }
        
        // 将回复内容填入主表单
        const mainTextarea = mainForm.querySelector('textarea[name="text"]');
        mainTextarea.value = replyText;
        
        // 设置父评论ID
        const parentInput = document.getElementById('comment-parent');
        parentInput.value = parentId;
        
        // 触发表单提交
        mainForm.submit();
        
        // 显示提交中消息
        showMessage('回复提交中...', 'info');
    }
    
    /**
     * 显示消息提示
     */
    function showMessage(message, type = 'info') {
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
        document.addEventListener('mouseover', function(e) {
            if (e.target.closest('.comment-body')) {
                const hoverComment = e.target.closest('.comment-body');
                hoverComment.classList.add('comment-hover');
            }
        });
        
        document.addEventListener('mouseout', function(e) {
            if (e.target.closest('.comment-body')) {
                const hoverComment = e.target.closest('.comment-body');
                hoverComment.classList.remove('comment-hover');
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
});
</script>