/**
 * 评论缓存和加载处理
 * 处理最新评论部分的加载和刷新
 * 针对Typecho原生评论系统优化
 */
document.addEventListener('DOMContentLoaded', function() {
  const commentsContainer = document.getElementById('card-newest-comments');
  if (!commentsContainer) return;

  // 本地缓存键名
  const COMMENTS_CACHE_KEY = 'butterfly_recent_comments';
  const CACHE_EXPIRY = 60 * 60 * 1000; // 缓存过期时间：1小时
  
  /**
   * 从服务器获取最新评论
   */
  function fetchRecentComments() {
    // 显示加载状态
    const asideList = commentsContainer.querySelector('.aside-list');
    if (asideList) {
      asideList.innerHTML = '<div class="loading-comments" style="text-align:center;padding:20px;"><i class="fas fa-spinner fa-pulse"></i><p>加载评论中...</p></div>';
    }

    // 生成默认头像的data URI
    const defaultAvatarDataURI = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2NjY2NjYyIgZD0iTTEyIDJDNi40OCAyIDIgNi40OCAyIDEyczQuNDggMTAgMTAgMTAgMTAtNC40OCAxMC0xMFMxNy41MiAyIDEyIDJ6bTAgM2MxLjY2IDAgMyAxLjM0IDMgM3MtMS4zNCAzLTMgMy0zLTEuMzQtMy0zIDEuMzQtMyAzLTN6bTAgMTQuMmMtMi41IDAtNC43MS0xLjI4LTYtMy4yMi4wMy0xLjk5IDQtMy4wOCA2LTMuMDggMS45OSAwIDUuOTcgMS4wOSA2IDMuMDgtMS4yOSAxLjk0LTMuNSAzLjIyLTYgMy4yMnoiLz48L3N2Zz4=';

    // 使用AJAX获取最新评论HTML
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          const parser = new DOMParser();
          const doc = parser.parseFromString(xhr.responseText, 'text/html');
          const newCommentsSection = doc.getElementById('card-newest-comments');
          
          if (newCommentsSection) {
            const newAsideList = newCommentsSection.querySelector('.aside-list');
            if (newAsideList && asideList) {
              // 处理获取到的评论内容
              processCommentsList(newAsideList);
              
              // 更新评论内容
              asideList.innerHTML = newAsideList.innerHTML;
              
              // 缓存评论数据
              cacheComments(newAsideList.innerHTML);
              
              // 绑定头像加载错误处理
              handleAvatarErrors();
            }
          } else {
            console.error('获取评论失败：未找到评论容器');
            showError('评论加载失败，请刷新页面重试');
          }
        } else {
          console.error('获取评论失败：', xhr.status);
          showError('评论加载失败，请刷新页面重试');
        }
      }
    };
    
    // 请求当前页面URL，附加时间戳防止缓存
    xhr.open('GET', `${window.location.href}?t=${new Date().getTime()}`, true);
    xhr.send();
  }
  
  /**
   * 处理评论列表，修复头像和内容
   */
  function processCommentsList(commentsList) {
    if (!commentsList) return;
    
    // 处理评论项
    const commentItems = commentsList.querySelectorAll('.aside-list-item');
    commentItems.forEach(item => {
      // 处理头像
      const avatarImg = item.querySelector('.thumbnail img');
      if (avatarImg) {
        // 确保头像有alt属性
        if (!avatarImg.hasAttribute('alt')) {
          avatarImg.alt = '用户头像';
        }
        
        // 设置头像尺寸和样式
        avatarImg.style.width = '40px';
        avatarImg.style.height = '40px';
        avatarImg.style.borderRadius = '50%';
        avatarImg.style.objectFit = 'cover';
        
        // 添加错误处理
        avatarImg.onerror = function() {
          if (!this.src.includes('data:image/svg')) {
            this.src = defaultAvatarDataURI;
          }
        };
        
        // 尝试修复gravatar头像的高DPI问题
        if (avatarImg.src.includes('gravatar.com') && !avatarImg.src.includes('s=80')) {
          let newSrc = avatarImg.src;
          // 添加尺寸参数
          if (newSrc.includes('?')) {
            newSrc += '&s=80&d=mp';
          } else {
            newSrc += '?s=80&d=mp';
          }
          avatarImg.src = newSrc;
        }
      }
      
      // 处理评论内容文本
      const commentText = item.querySelector('.comment');
      if (commentText) {
        // 确保内容不为空
        if (!commentText.textContent.trim()) {
          commentText.textContent = '[表情]';
        }
        
        // 添加样式
        commentText.style.wordBreak = 'break-word';
        commentText.style.overflow = 'hidden';
        commentText.style.textOverflow = 'ellipsis';
        commentText.style.display = '-webkit-box';
        commentText.style.webkitLineClamp = '2';
        commentText.style.webkitBoxOrient = 'vertical';
      }
        });
}

/**
   * 缓存评论数据到localStorage
   */
  function cacheComments(commentsHtml) {
    if (!commentsHtml) return;
    
    try {
      const cacheData = {
        timestamp: Date.now(),
        html: commentsHtml
      };
      localStorage.setItem(COMMENTS_CACHE_KEY, JSON.stringify(cacheData));
    } catch (error) {
      console.error('缓存评论失败：', error);
    }
}

/**
   * 从缓存加载评论
   */
  function loadCommentsFromCache() {
    try {
      const cache = localStorage.getItem(COMMENTS_CACHE_KEY);
      if (!cache) return false;
      
      const data = JSON.parse(cache);
      const isExpired = Date.now() - data.timestamp > CACHE_EXPIRY;
      
      if (isExpired) {
        localStorage.removeItem(COMMENTS_CACHE_KEY);
        return false;
      }
      
      // 加载缓存的评论内容
      const asideList = commentsContainer.querySelector('.aside-list');
      if (asideList && data.html) {
        asideList.innerHTML = data.html;
        handleAvatarErrors();
        return true;
      }
    } catch (error) {
      console.error('加载缓存评论失败：', error);
    }
    
    return false;
  }
  
  /**
   * 处理头像加载错误
   */
  function handleAvatarErrors() {
    // 生成默认头像的data URI
    const defaultAvatarDataURI = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2NjY2NjYyIgZD0iTTEyIDJDNi40OCAyIDIgNi40OCAyIDEyczQuNDggMTAgMTAgMTAgMTAtNC40OCAxMC0xMFMxNy41MiAyIDEyIDJ6bTAgM2MxLjY2IDAgMyAxLjM0IDMgM3MtMS4zNCAzLTMgMy0zLTEuMzQtMy0zIDEuMzQtMyAzLTN6bTAgMTQuMmMtMi41IDAtNC43MS0xLjI4LTYtMy4yMi4wMy0xLjk5IDQtMy4wOCA2LTMuMDggMS45OSAwIDUuOTcgMS4wOSA2IDMuMDgtMS4yOSAxLjk0LTMuNSAzLjIyLTYgMy4yMnoiLz48L3N2Zz4=';
    
    const avatars = commentsContainer.querySelectorAll('.thumbnail img');
    avatars.forEach(img => {
      // 设置样式
      img.style.width = '40px';
      img.style.height = '40px';
      img.style.borderRadius = '50%';
      img.style.objectFit = 'cover';
      
      // 添加错误处理
      img.onerror = function() {
        // 加载失败时使用默认头像
        this.src = defaultAvatarDataURI;
        this.onerror = null; // 防止循环加载失败
      };
      
      // 检查当前状态
      if (img.complete && (img.naturalWidth === 0 || img.naturalHeight === 0)) {
        img.src = defaultAvatarDataURI;
      }
    });
  }
  
  /**
   * 显示错误信息
   */
  function showError(message) {
    const asideList = commentsContainer.querySelector('.aside-list');
    if (asideList) {
      asideList.innerHTML = `<div class="error-message" style="text-align:center;padding:20px;color:#ff4d4f;">
        <i class="fas fa-exclamation-circle"></i>
        <p>${message}</p>
        <button onclick="window.location.reload()" style="padding:5px 10px;border-radius:4px;background:#49b1f5;color:white;border:none;cursor:pointer;margin-top:10px;">刷新页面</button>
      </div>`;
    }
  }
  
  // 尝试从缓存加载或从服务器获取
  if (!loadCommentsFromCache()) {
    fetchRecentComments();
  }
  
  // 每30分钟自动刷新评论
  setInterval(fetchRecentComments, 30 * 60 * 1000);
}); 