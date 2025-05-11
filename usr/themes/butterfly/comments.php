<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('css/comments.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('css/OwO.min.css'); ?>">
<script src="<?php $this->options->themeUrl('js/OwO.min.js'); ?>" defer></script>
<script src="<?php $this->options->themeUrl('js/emoji-optimized.js'); ?>" defer></script>
<script src="<?php $this->options->themeUrl('js/emoji-additional.js'); ?>" defer></script>
<script src="<?php $this->options->themeUrl('js/owo-compat.js'); ?>" defer></script>
<script src="<?php $this->options->themeUrl('js/emoji-input-cleaner.js'); ?>" defer></script>

<!-- 表情选择器基础样式重置 -->
<style type="text/css">
/* 完全重置表情选择器样式 */
.OwO {
  position: relative;
  margin-top: 10px;
}

.OwO .OwO-logo {
  display: inline-block;
  padding: 8px 15px;
  border-radius: 4px;
  background: #f2f2f2;
  color: #666;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.OwO .OwO-logo:hover {
  background: #e6e6e6;
}

.OwO .OwO-body {
  display: none;
  position: absolute;
  width: 400px;
  max-width: 90vw;
  left: 0;
  top: 100%;
  margin-top: 5px;
  padding: 0;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: #fff;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  z-index: 100;
}

.OwO.OwO-open .OwO-body {
  display: block;
}

.OwO .OwO-body .OwO-items {
  display: none;
  max-height: 250px;
  overflow-y: auto;
  margin: 0;
  padding: 10px;
}

.OwO .OwO-body .OwO-items-show {
  display: block;
}

/* 颜文字部分 */
.OwO .OwO-body .OwO-items[data-name="文字"] {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-gap: 10px;
}

.OwO .OwO-body .OwO-items[data-name="文字"] .OwO-item {
  text-align: center;
  padding: 8px 0;
  background: #f7f7f7;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 15px;
}

/* Emoji部分 */
.OwO .OwO-body .OwO-items[data-name="表情"] {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  grid-gap: 10px;
}

.OwO .OwO-body .OwO-items[data-name="表情"] .OwO-item {
  text-align: center;
  padding: 8px 0;
  background: #f7f7f7;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 22px;
}

/* 动物部分 */
.OwO .OwO-body .OwO-items[data-name="动物"] {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  grid-gap: 10px;
}

.OwO .OwO-body .OwO-items[data-name="动物"] .OwO-item {
  text-align: center;
  padding: 8px 0;
  background: #f7f7f7;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 22px;
}

/* 符号部分 */
.OwO .OwO-body .OwO-items[data-name="符号"] {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  grid-gap: 10px;
}

.OwO .OwO-body .OwO-items[data-name="符号"] .OwO-item {
  text-align: center;
  padding: 8px 0;
  background: #f7f7f7;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 22px;
}

.OwO .OwO-body .OwO-items .OwO-item:hover {
  background: #e6e6e6;
  transform: scale(1.1);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.OwO .OwO-body .OwO-bar {
  display: flex;
  border-top: 1px solid #eee;
  background: #f8f8f8;
  border-radius: 0 0 6px 6px;
}

.OwO .OwO-body .OwO-bar .OwO-packages {
  display: flex;
  margin: 0;
  padding: 0;
  list-style: none;
}

.OwO .OwO-body .OwO-bar .OwO-packages li {
  padding: 10px 15px;
  cursor: pointer;
  color: #666;
  transition: all 0.2s;
}

.OwO .OwO-body .OwO-bar .OwO-packages li:hover {
  background: #eee;
  color: #333;
}

.OwO .OwO-body .OwO-bar .OwO-packages .OwO-package-active {
  background: #eee;
  color: #333;
  font-weight: bold;
}

/* 提示文字 */
.OwO-tips {
  position: absolute;
  color: #888;
  font-size: 12px;
  right: 10px;
  top: -20px;
}

@media (max-width: 768px) {
  .OwO .OwO-body {
    width: 320px;
  }
  
  .OwO .OwO-body .OwO-items-emoticon {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .OwO .OwO-body .OwO-items[data-name="Emoji"],
  .OwO .OwO-body .OwO-items[data-name="小动物"] {
    grid-template-columns: repeat(5, 1fr);
  }
}
</style>

<div id="comments" class="comments-system">
    <?php $this->comments()->to($comments); ?>

    <div class="comments-header">
        <h3><i class="fas fa-comments"></i><?php _e('评论'); ?></h3>
        <span class="comments-counter"><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></span>
    </div>

    <div class="comments-body">
    <?php if($this->allow('comment') && $this->options->CloseComments == 'off'): ?>
            <!-- 评论列表部分 -->
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
        <?php else: ?>
            <div class="comments-closed">
                <i class="fas fa-comment-slash"></i>
                <p><?php _e('评论已关闭'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- 主评论框 - 独立放置在页面上的固定位置 -->
<?php if($this->allow('comment') && $this->options->CloseComments == 'off'): ?>
<div id="comment-place" class="main-comment-container">
    <div id="<?php $this->respondId(); ?>" class="comments-form main-comment-form">
        <h4 class="comment-reply-title" id="comment-reply-title"><?php _e('发表评论'); ?></h4>
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
                    <div class="input-wrapper">
                        <input type="text" name="author" id="author" placeholder="<?php _e('昵称 *'); ?>" value="<?php $this->remember('author'); ?>" required />
                        <div class="comment-focus-effect"></div>
                    </div>
                    <div class="input-wrapper">
                        <input type="email" name="mail" id="mail" placeholder="<?php _e('邮箱 *'); ?>" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> />
                        <div class="comment-focus-effect"></div>
                    </div>
                    <div class="input-wrapper">
                        <input type="url" name="url" id="url" placeholder="<?php _e('网站'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
                        <div class="comment-focus-effect"></div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="comments-textarea">
                <div class="input-wrapper">
                    <textarea name="text" id="textarea" class="textarea" rows="8" placeholder="<?php _e('说点什么吧...'); ?>" required><?php $this->remember('text'); ?></textarea>
                    <div class="comment-focus-effect"></div>
                </div>
                <!-- 添加表情选择器容器 -->
                <div class="OwO"></div>
                <!-- 添加表情提示文字 -->
                <div class="OwO-tips">点击表情可直接插入</div>
            </div>
            
            <div class="comments-buttons">
                <button type="submit" class="submit"><span><?php _e('发表评论'); ?></span></button>
                  </div>
            
            <div class="cancel-comment-reply-container">
                <div class="cancel-comment-reply">
                    <?php $comments->cancelReply('<span class="cancel-reply-btn-enhanced"><i class="fas fa-comment"></i> 评论文章</span>'); ?>
                </div>
            </div>

            <?php RecapOutPut(false); ?>
                </form>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 删除页面中可能存在的"去评论"链接
    const commentLinks = document.querySelectorAll('.go-to-comment');
    commentLinks.forEach(link => {
        link.remove();
    });
    
    // 给评论添加高亮效果
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
    
    // 尝试将评论框移动到指定位置
    const moveCommentBox = function() {
        // 找到主评论框容器
        const commentContainer = document.getElementById('comment-place');
        // 找到目标位置
        const targetLocation = document.querySelector('.tag_share');
        
        // 如果都存在，将评论框移动到目标位置之后
        if (commentContainer && targetLocation) {
            targetLocation.after(commentContainer);
        }
    };
    
    // 执行移动
    moveCommentBox();
    
    // 获取关键元素
    const commentForm = document.getElementById('comment-form');
    const mainCommentForm = document.querySelector('.main-comment-form');
    const commentTitle = document.getElementById('comment-reply-title');
    const originalTitle = commentTitle ? commentTitle.textContent : '发表评论';
    
    // 显示主表单
    if(mainCommentForm) {
        mainCommentForm.style.display = 'block';
    }
    
    // 修复回复按钮，改为纯文字按钮
    const fixReplyLinks = function() {
        const replyLinks = document.querySelectorAll('.comment-reply a');
        replyLinks.forEach(link => {
            // 清空链接内容，只添加文字
            link.innerHTML = '回复';
            
            // 调整样式
            link.className = 'text-reply-link';
            link.title = '回复该评论';
        });
    };
    
    // 执行修复
    fixReplyLinks();
    
    // 替换回复链接的默认行为
    const replyLinks = document.querySelectorAll('.comment-reply a');
    replyLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 显示主评论表单
            if(mainCommentForm) {
                mainCommentForm.style.display = 'block';
            }
            
            // 获取回复目标信息
            const href = this.getAttribute('href');
            const replyToId = href.split('#')[1];
            const replyTo = document.getElementById(replyToId);
            
            // 找到被回复人姓名
            let replyToName = '匿名';
            const nickElement = replyTo.querySelector('.vnick');
            if (nickElement) {
                replyToName = nickElement.textContent.trim();
            } else {
                // 尝试其他可能包含用户名的元素
                const authorElement = replyTo.querySelector('.comment-author');
                if (authorElement) {
                    const authorNameElement = authorElement.querySelector('a, cite');
                    if (authorNameElement) {
                        replyToName = authorNameElement.textContent.trim();
                    }
                }
                
                // 尝试获取评论者的名称 - 扩展选择器
                const possibleNameElements = [
                    replyTo.querySelector('.fn'), 
                    replyTo.querySelector('.comment-author-name'),
                    replyTo.querySelector('.author-name'),
                    replyTo.querySelector('.name'),
                    replyTo.querySelector('.user-name'),
                    replyTo.querySelector('cite'),
                    replyTo.querySelector('.comment-author cite'),
                    replyTo.querySelector('.comment-author a')
                ];
                
                for (const element of possibleNameElements) {
                    if (element && element.textContent.trim()) {
                        replyToName = element.textContent.trim();
                        break;
         }
                }
                
                // 直接从data属性中获取
                const dataAuthor = replyTo.getAttribute('data-author');
                if (dataAuthor) {
                    replyToName = dataAuthor;
                }
                
                // 检查是否有class包含author的元素
                const authorClassElements = replyTo.querySelectorAll('[class*="author"]');
                for (const element of authorClassElements) {
                    if (element.textContent.trim() && !element.querySelector('*')) {
                        // 如果元素有文本内容且不包含子元素
                        replyToName = element.textContent.trim();
                        break;
                    }
                }
                
                // 如果还是匿名，使用更模糊的方法获取
                if (replyToName === '匿名') {
                    const allText = replyTo.textContent;
                    const authorMatch = allText.match(/作者[：:]\s*([^\s,，:：]+)/);
                    if (authorMatch && authorMatch[1]) {
                        replyToName = authorMatch[1];
                    }
                }
            }
            
            // 更新评论框标题为"回复给XXX"
            if(commentTitle) {
                commentTitle.innerHTML = `<i class="fas fa-reply"></i> 回复给 <span>${replyToName}</span>`;
            }
            
            // 更新评论框提示文本
            const textarea = document.getElementById('textarea');
            if(textarea) {
                textarea.placeholder = `回复 ${replyToName}...`;
                textarea.focus();
            }
            
            // 使用原生评论系统的回复功能触发
            let originalOnclick = this.getAttribute('onclick');
            if(originalOnclick) {
                // 提取并执行函数
                let funcMatch = originalOnclick.match(/return (.*?)\((.*?)\)/);
                if(funcMatch && window[funcMatch[1]]) {
                    window[funcMatch[1]].apply(window, eval('[' + funcMatch[2] + ']'));
                }
            }
            
            // 显示取消回复按钮
            const cancelReply = document.querySelector('.cancel-comment-reply');
            if(cancelReply) {
                cancelReply.style.display = 'block';
            }
            
            // 滚动到评论框
            mainCommentForm.scrollIntoView({behavior: 'smooth', block: 'center'});
        });
    });
    
    // 初始化表单事件
    function initFormEvents(form) {
        // 添加提交按钮的波纹效果
        const submitBtn = form.querySelector('button.submit');
        if (submitBtn) {
            submitBtn.addEventListener('mousedown', function(e) {
                const x = e.clientX - this.getBoundingClientRect().left;
                const y = e.clientY - this.getBoundingClientRect().top;
                
                const ripple = document.createElement('span');
                ripple.classList.add('comment-submit-ripple');
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        }
        
        // 添加输入框动效
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('input-active');
            });
            input.addEventListener('blur', function() {
                this.parentNode.classList.remove('input-active');
            });
        });
    }
    
    // 为主评论框初始化事件
    if(commentForm) {
        initFormEvents(commentForm);
    }
    
    // 处理主表单的取消回复按钮
    const mainCancelReplyBtn = document.querySelector('.cancel-reply-btn-enhanced');
    if (mainCancelReplyBtn) {
        mainCancelReplyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 隐藏取消回复按钮
            const cancelReply = document.querySelector('#comment-form .cancel-comment-reply');
            if (cancelReply) {
                cancelReply.style.display = 'none';
            }
            
            // 恢复标题
            if(commentTitle) {
                commentTitle.textContent = originalTitle;
            }
            
            // 恢复提示文本
            const textarea = document.getElementById('textarea');
            if(textarea) {
                textarea.placeholder = '说点什么吧...';
                textarea.focus();
            }
            
            // 添加平滑滚动到评论框
            const mainCommentForm = document.querySelector('.main-comment-form');
            if(mainCommentForm) {
                mainCommentForm.scrollIntoView({behavior: 'smooth', block: 'center'});
            }
        });
    }
    
    // 初始化表情选择器
    try {
        const initOwO = function() {
            if (typeof OwO !== 'undefined') {
                const owoInstance = new OwO({
                    logo: '<i class="fas fa-smile"></i> 表情',
                    container: document.querySelector('.main-comment-form .OwO'),
                    target: document.getElementById('textarea'),
                    api: '<?php $this->options->themeUrl('OwO.json'); ?>?v=' + new Date().getTime(),
        position: 'down',
        width: '100%',
                    maxHeight: '250px'
                });
                
                // 触发自定义事件通知emoji-optimized.js处理表情元素
                setTimeout(() => {
                    document.dispatchEvent(new CustomEvent('owo-initialized'));
                }, 500);
            } else {
                setTimeout(initOwO, 500);
            }
        };
        
        // 检查是否已经初始化
        if (!window.owoInitialized) {
            window.owoInitialized = true;
            initOwO();
        }
    } catch (e) {
        // 初始化错误时静默处理
    }
    
    // 加载用户之前填写的信息
    if (!document.querySelector('.comment-login-info')) {
        const savedAuthor = localStorage.getItem('comment_author');
        const savedMail = localStorage.getItem('comment_mail');
        
        if (savedAuthor && document.getElementById('author')) {
            document.getElementById('author').value = savedAuthor;
        }
        
        if (savedMail && document.getElementById('mail')) {
            document.getElementById('mail').value = savedMail;
        }
    }
    
    // 修改嵌套评论处理逻辑
    // 限制嵌套深度，超过特定层级的评论显示为扁平结构
    const deepNestedComments = document.querySelectorAll('.comment-children .comment-children');
    deepNestedComments.forEach(nestedComment => {
        nestedComment.classList.add('deep-nested-comments');
        
        // 将所有更深层次的评论扁平化处理
        const deeperChildren = nestedComment.querySelectorAll('.comment-children');
        deeperChildren.forEach(deepChild => {
            // 添加特殊标记类
            deepChild.classList.add('flatten-comments');
            
            // 找到所有评论元素
            const comments = deepChild.querySelectorAll('.comment-body');
            comments.forEach(comment => {
                // 查找父评论信息
                const parentId = comment.querySelector('.comment-reply a')?.getAttribute('href')?.match(/#(comment-\d+)/)?.[1];
                if (parentId) {
                    const parentComment = document.getElementById(parentId);
                    if (parentComment) {
                        // 尝试不同方式获取父评论者名称
                        let parentAuthor = '匿名';
                        const nickElement = parentComment.querySelector('.vnick');
                        if (nickElement) {
                            parentAuthor = nickElement.textContent.trim();
                        } else {
                            const authorElement = parentComment.querySelector('.comment-author');
                            if (authorElement) {
                                const authorNameElement = authorElement.querySelector('a, cite');
                                if (authorNameElement) {
                                    parentAuthor = authorNameElement.textContent.trim();
                                }
                            }
                            
                            // 尝试获取评论者的名称 - 更多选择器
                            const possibleNameElements = [
                                parentComment.querySelector('.fn'), 
                                parentComment.querySelector('.comment-author-name'),
                                parentComment.querySelector('.author-name'),
                                parentComment.querySelector('.name'),
                                parentComment.querySelector('.user-name')
                            ];
                            
                            for (const element of possibleNameElements) {
                                if (element && element.textContent.trim()) {
                                    parentAuthor = element.textContent.trim();
                                    break;
                                }
                            }
                            
                            // 直接从data属性中获取
                            const dataAuthor = parentComment.getAttribute('data-author');
                            if (dataAuthor) {
                                parentAuthor = dataAuthor;
                            }
                            
                            // 如果还是匿名，尝试从父评论内容中提取
                            if (parentAuthor === '匿名') {
                                const parentMeta = parentComment.querySelector('.comment-meta');
                                if (parentMeta) {
                                    const metaText = parentMeta.textContent.trim();
                                    const nameMatch = metaText.match(/([^\s@]+)[@＠]/);
                                    if (nameMatch && nameMatch[1]) {
                                        parentAuthor = nameMatch[1];
                                    }
                                }
                            }
                        }
                        
                        // 添加回复指示
                        const replyIndicator = document.createElement('div');
                        replyIndicator.className = 'nested-reply-indicator';
                        replyIndicator.innerHTML = `<i class="fas fa-reply"></i> 回复给 <span>${parentAuthor}</span>`;
                        
                        const commentContent = comment.querySelector('.comment-content');
                        if (commentContent) {
                            comment.insertBefore(replyIndicator, commentContent);
                        }
                    }
                }
            });
        });
    });
    
    // 在移动设备上启用扁平化评论视图
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (isMobile) {
        document.querySelector('.comments-system').classList.add('flat-mobile-comments');
        document.querySelector('.comments-system').classList.add('mobile-device');
        
        // 查找所有评论项
        const allComments = document.querySelectorAll('.comment-list > li');
        
        // 为每个顶级评论添加折叠/展开功能
        allComments.forEach(comment => {
            const childrenContainer = comment.querySelector('.comment-children');
            if (childrenContainer && childrenContainer.children.length > 0) {
                // 创建折叠/展开按钮
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'toggle-replies-btn';
                
                // 获取回复数量
                const replyCount = childrenContainer.querySelectorAll('li').length;
                toggleBtn.innerHTML = `<i class="fas fa-chevron-down"></i> 显示${replyCount}条回复`;
                
                // 在评论之后、子评论之前插入按钮
                const commentBody = comment.querySelector('.comment-body');
                commentBody.insertAdjacentElement('afterend', toggleBtn);
                
                // 默认折叠子评论
                childrenContainer.style.display = 'none';
                
                // 添加点击事件
                toggleBtn.addEventListener('click', function() {
                    const isHidden = childrenContainer.style.display === 'none';
                    
                    // 切换显示状态
                    childrenContainer.style.display = isHidden ? 'block' : 'none';
                    
                    // 更新按钮文本
                    toggleBtn.innerHTML = isHidden 
                        ? `<i class="fas fa-chevron-up"></i> 隐藏回复` 
                        : `<i class="fas fa-chevron-down"></i> 显示${replyCount}条回复`;
                });
            }
        });
    }
    
    // 给评论添加渐入动画效果
    const commentItems = document.querySelectorAll('.comment-body');
    if (commentItems.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('comment-animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        commentItems.forEach(item => {
            observer.observe(item);
        });
    } else {
        // 如果不支持 IntersectionObserver，直接添加动画类
        commentItems.forEach(item => {
            item.classList.add('comment-animate-in');
        });
    }
});
    </script>

<style>
/* 动态生成的动画和特效样式 */
/* 添加主评论框容器样式 */
.main-comment-container {
    margin: 2rem 0;
    padding: 1.5rem;
    background: var(--comment-bg);
    border-radius: var(--comment-radius);
    box-shadow: var(--comment-shadow);
}

/* 取消回复按钮容器 */
.cancel-comment-reply-container {
    margin-top: 1rem;
    text-align: center;
    display: flex;
    justify-content: center;
}

/* 确保取消回复按钮样式匹配 */
.cancel-comment-reply {
    margin: 0 auto;
    display: none;
}

/* 纯文字回复按钮 */
.text-reply-link {
    display: inline-block;
    padding: 4px 12px;
    background: transparent;
    color: var(--comment-theme) !important;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
}

.text-reply-link:hover {
    background: transparent;
    color: var(--comment-accent) !important;
    transform: translateY(-2px);
    box-shadow: none;
}

/* 增强版取消回复按钮，类似于发表评论按钮 */
.cancel-reply-btn-enhanced {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(45deg, var(--comment-theme), var(--comment-theme-dark, #3a98d9));
    color: white !important;
    border: none;
    padding: 0.6rem 2rem;
    border-radius: 30px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(73, 177, 245, 0.3);
    position: relative;
    overflow: hidden;
    margin: 0 auto;
}

.cancel-reply-btn-enhanced::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
                rgba(255, 255, 255, 0) 0%, 
                rgba(255, 255, 255, 0.2) 50%, 
                rgba(255, 255, 255, 0) 100%);
    transition: all 0.6s ease;
}

.cancel-reply-btn-enhanced:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(73, 177, 245, 0.4);
    color: white !important;
}

.cancel-reply-btn-enhanced:hover::before {
    left: 100%;
}

.cancel-reply-btn-enhanced i {
    margin-right: 0.5rem;
    transition: all 0.3s ease;
}

.cancel-reply-btn-enhanced:hover i {
    transform: scale(1.2);
}

/* 其他必要的动画和效果样式 */
.comment-submit-ripple {
    position: absolute;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    transform: scale(0);
    animation: comment-ripple 0.6s linear;
    pointer-events: none;
}

@keyframes comment-ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.input-wrapper {
    position: relative;
    width: 100%;
}

.input-active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: var(--comment-radius);
    padding: 2px;
    background: linear-gradient(45deg, var(--comment-theme), var(--comment-accent));
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
    animation: border-pulse 1.5s infinite;
}

@keyframes border-pulse {
    0% { opacity: 0.8; }
    50% { opacity: 0.5; }
    100% { opacity: 0.8; }
}

.comment-body {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.comment-animate-in {
    opacity: 1;
    transform: translateY(0);
}

@media (prefers-reduced-motion: reduce) {
    .comment-body {
        opacity: 1;
        transform: none;
        transition: none;
    }
    
    .comment-submit-ripple {
        display: none;
    }
    
    .input-active::before {
        animation: none;
    }
}

/* 回复标题样式 */
#comment-reply-title {
    display: flex;
    align-items: center;
}

#comment-reply-title i {
    margin-right: 0.5rem;
    color: var(--comment-theme);
}

#comment-reply-title span {
    font-weight: 600;
    color: var(--comment-theme);
}

/* 增强表情选择器样式 */
.OwO .OwO-logo {
  padding: 8px 15px !important;
  font-size: 14px !important;
  background: rgba(73, 177, 245, 0.1) !important;
  border-radius: 4px !important;
  transition: all 0.3s ease !important;
}

.OwO .OwO-logo:hover {
  background: rgba(73, 177, 245, 0.2) !important;
}

.OwO .OwO-body {
  border: 1px solid #eee !important;
  border-radius: 8px !important;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
  max-height: 300px !important;
}

/* 修复表情选择器的网格布局 */
.OwO .OwO-body .OwO-items {
  display: grid !important;
  grid-template-columns: repeat(auto-fill, minmax(36px, 1fr)) !important;
  grid-gap: 5px !important;
  padding: 10px !important;
}

.OwO .OwO-body .OwO-items .OwO-item {
  background: #f9f9f9 !important;
  padding: 6px 10px !important;
  border-radius: 6px !important;
  transition: all 0.2s ease !important;
  border: 1px solid transparent !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  margin: 0 !important;
  width: auto !important;
  height: auto !important;
}

.OwO .OwO-body .OwO-items .OwO-item:hover {
  background: #fff !important;
  transform: scale(1.1) !important;
  border-color: rgba(73, 177, 245, 0.3) !important;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1) !important;
}

.OwO .OwO-body .OwO-items-emoticon .OwO-item {
  font-size: 18px !important;
  line-height: 24px !important;
}

.OwO .OwO-body .OwO-bar {
  background: #f5f5f5 !important;
}

.OwO .OwO-body .OwO-bar .OwO-packages li {
  transition: all 0.2s ease !important;
}

.OwO .OwO-body .OwO-bar .OwO-packages li:hover,
.OwO .OwO-body .OwO-bar .OwO-packages .OwO-package-active {
  background: rgba(73, 177, 245, 0.1) !important;
  color: #49b1f5 !important;
}

@media (max-width: 768px) {
  .OwO .OwO-body {
    max-height: 200px !important;
  }
  
  .OwO .OwO-body .OwO-items {
    grid-template-columns: repeat(auto-fill, minmax(30px, 1fr)) !important;
  }
  
  .OwO .OwO-body .OwO-items .OwO-item {
    padding: 5px 8px !important;
  }
  
  .OwO .OwO-body .OwO-items-emoticon .OwO-item {
    font-size: 16px !important;
  }
}

/* 表情点击通知样式 */
.emoji-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 14px;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    will-change: transform, opacity;
}

.emoji-notification.show {
    opacity: 1;
    transform: translateY(0);
}

/* 表情选择器提示文字 */
.OwO-tips {
    position: absolute;
    color: #888;
    font-size: 12px;
    right: 10px;
    top: -20px;
}

/* 强制表情显示为文本内容 */
.OwO .OwO-body .OwO-items .OwO-item {
    font-size: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 8px !important;
    will-change: transform;
    user-select: none;
}

/* 修复：隐藏任何可能的隐藏表情名称前缀 */
.OwO .OwO-body .OwO-items .OwO-item span.emoji-name,
.OwO .OwO-body .OwO-items .OwO-item span:first-child:not(:only-child) {
    display: none !important;
}

/* 强制只显示表情符号 */
.OwO .OwO-body .OwO-items .OwO-item:before,
.OwO .OwO-body .OwO-items .OwO-item:after {
    display: none !important;
    content: none !important;
}

/* 确保文本选择的表情只有表情符号 */
.OwO-text-item span:not(.emoji-text) {
    display: none !important;
}

/* 调整表情符号大小 */
.OwO .OwO-body .OwO-items[data-name="Emoji"] .OwO-item,
.OwO .OwO-body .OwO-items[data-name="小动物"] .OwO-item {
    font-size: 20px !important;
}

/* 颜文字部分表情大小调整 */
.OwO .OwO-body .OwO-items[data-name="颜文字"] .OwO-item {
    font-size: 14px !important;
}

/* 表情选择器加载动画 */
@keyframes owo-loading {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.OwO-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid rgba(73, 177, 245, 0.3);
    border-top-color: rgba(73, 177, 245, 0.9);
    border-radius: 50%;
    animation: owo-loading 0.6s linear infinite;
}

/* 触觉反馈 */
@media (hover: hover) {
    .OwO .OwO-body .OwO-items .OwO-item:hover {
        transform: scale(1.2) !important;
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }
}

/* 表情预览 */
.OwO-preview {
    position: absolute;
    bottom: 100%;
    left: 0;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 20px;
    margin-bottom: 5px;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.2s ease;
    pointer-events: none;
    z-index: 10;
}

.OwO-item:hover .OwO-preview {
    opacity: 1;
    transform: translateY(0);
}
</style>