/**
 * 简单的评论缓存脚本
 * 直接在浏览器端获取并显示最新评论
 */

// 评论容器ID
const COMMENTS_CONTAINER_ID = 'giscus-recent-comments';

// 本地存储键名
const STORAGE_KEY = 'recent-comments-cache-v4'; // 更新缓存键名，强制刷新缓存
const CACHE_TIME = 600000; // 缓存时间（毫秒）：10分钟

// 调试模式
const DEBUG = true;

// 日志函数
function log(...args) {
    if (DEBUG) {
        console.log('[评论系统]', ...args);
    }
}

// 错误日志
function logError(...args) {
    if (DEBUG) {
        console.error('[评论系统]', ...args);
    }
}

// GitHub仓库信息（默认值，会尝试从页面中获取）
const DEFAULT_REPO = {
    owner: 'iawooo',
    repo: 'vlty'
};

// 网站根URL，用于构建正确的链接
const SITE_ROOT = window.location.origin;

// 初始化
document.addEventListener('DOMContentLoaded', function() {
    loadRecentComments();
});

/**
 * 加载最新评论
 */
function loadRecentComments() {
    const container = document.getElementById(COMMENTS_CONTAINER_ID);
    if (!container) {
        logError('找不到评论容器:', COMMENTS_CONTAINER_ID);
        return;
    }
    
    // 隐藏重试按钮
    const refreshButton = document.querySelector('.comment-refresh');
    if (refreshButton) {
        refreshButton.style.display = 'none';
    }
    
    // 显示加载中
    container.innerHTML = '<div class="aside-list-item"><div class="content" style="text-align: center; width: 100%;"><span class="comment">正在加载最新评论...</span></div></div>';
    
    log('开始加载评论...');
    
    // 首先尝试从本地缓存加载
    const cachedData = getCommentsFromCache();
    if (cachedData) {
        log('找到缓存评论数据', cachedData);
        renderComments(cachedData.comments, container);
        // 如果缓存不是太旧，就使用缓存
        if (Date.now() - cachedData.timestamp < CACHE_TIME) {
            log('使用缓存评论数据（未过期）');
            return;
        }
        log('缓存已过期，将请求最新数据');
    } else {
        log('无可用缓存，将请求最新数据');
    }
    
    // 尝试从GitHub获取讨论数据
    fetchDiscussionsFromRepo();
}

/**
 * 从本地缓存获取评论
 */
function getCommentsFromCache() {
    try {
        const cachedData = localStorage.getItem(STORAGE_KEY);
        if (!cachedData) return null;
        return JSON.parse(cachedData);
    } catch (e) {
        logError('读取缓存评论失败:', e);
        return null;
    }
}

/**
 * 保存评论到本地缓存
 */
function saveCommentsToCache(comments) {
    try {
        const cacheData = {
            comments: comments,
            timestamp: Date.now()
        };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cacheData));
        log('评论数据已缓存', comments.length, '条评论');
    } catch (e) {
        logError('保存评论到缓存失败:', e);
    }
}

/**
 * 获取当前页面的giscus配置
 */
function getGiscusConfig() {
    // 先尝试从script标签获取
    const script = document.querySelector('script[src*="giscus.app/client.js"]');
    if (script) {
        const repo = script.getAttribute('data-repo');
        if (repo) {
            const [owner, repoName] = repo.split('/');
            if (owner && repoName) {
                log('从script标签获取仓库信息:', owner, repoName);
                return { owner, repo: repoName };
            }
        }
    }
    
    // 再尝试从giscus iframe获取
    const iframe = document.querySelector('iframe.giscus-frame');
    if (iframe) {
        const src = iframe.src;
        const repoMatch = src.match(/repo=([^&]+)/);
        if (repoMatch && repoMatch[1]) {
            const repo = decodeURIComponent(repoMatch[1]);
            const [owner, repoName] = repo.split('/');
            if (owner && repoName) {
                log('从iframe获取仓库信息:', owner, repoName);
                return { owner, repo: repoName };
            }
        }
    }
    
    // 找不到配置，使用默认值
    log('未找到仓库配置，使用默认值:', DEFAULT_REPO);
    return DEFAULT_REPO;
}

/**
 * 从GitHub仓库获取讨论数据
 */
function fetchDiscussionsFromRepo() {
    const container = document.getElementById(COMMENTS_CONTAINER_ID);
    const repoInfo = getGiscusConfig();
    
    log('开始获取GitHub讨论数据, 仓库:', repoInfo.owner + '/' + repoInfo.repo);
    
    // 使用REST API直接获取讨论列表
    fetch(`https://api.github.com/repos/${repoInfo.owner}/${repoInfo.repo}/discussions`)
        .then(response => {
            if (!response.ok) {
                throw new Error('获取讨论列表失败: ' + response.status);
            }
            return response.json();
        })
        .then(discussions => {
            if (!discussions || discussions.length === 0) {
                throw new Error('没有找到讨论');
            }
            
            log('找到', discussions.length, '个讨论');
            
            // 获取前10个讨论的评论
            return Promise.all(
                discussions.slice(0, 10).map(discussion => {
                    log('获取讨论评论:', discussion.title, '(#' + discussion.number + ')');
                    return fetch(`https://api.github.com/repos/${repoInfo.owner}/${repoInfo.repo}/discussions/${discussion.number}/comments`)
                        .then(response => {
                            if (!response.ok) {
                                console.warn(`获取讨论 ${discussion.number} 的评论失败: ${response.status}`);
                                return []; // 失败时返回空数组，不中断整个Promise.all
                            }
                            return response.json();
                        })
                        .then(comments => {
                            log('讨论', discussion.title, '有', comments.length, '条评论');
                            // 为每个评论添加文章标题信息
                            return comments.map(comment => ({
                                ...comment,
                                discussionTitle: discussion.title,
                                discussionUrl: discussion.html_url,
                                discussionNumber: discussion.number
                            }));
                        })
                        .catch(err => {
                            logError(`处理讨论 ${discussion.number} 时出错:`, err);
                            return []; // 出错时返回空数组，不中断整个Promise.all
                        });
                })
            );
        })
        .then(commentsByDiscussion => {
            // 将多个讨论的评论合并为一个数组
            const allComments = commentsByDiscussion.flat();
            
            log('合计获取到', allComments.length, '条评论');
            
            if (!allComments || allComments.length === 0) {
                throw new Error('没有找到评论');
            }
            
            // 按时间排序，最新的在前
            allComments.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            
            // 只取最新的6条评论
            const recentComments = allComments.slice(0, 6);
            
            log('处理后保留', recentComments.length, '条最新评论');
            
            // 处理评论数据
            const processedComments = recentComments.map(comment => ({
                author: {
                    name: comment.user.login,
                    avatarUrl: comment.user.avatar_url
                },
                bodyText: comment.body,
                url: comment.html_url,
                createdAt: comment.created_at,
                discussionTitle: comment.discussionTitle || '留言板',
                discussionUrl: comment.discussionUrl || '#',
                discussionNumber: comment.discussionNumber
            }));
            
            // 保存并渲染评论
            saveCommentsToCache(processedComments);
            renderComments(processedComments, container);
        })
        .catch(error => {
            logError('获取GitHub评论失败:', error);
            // 尝试获取单个讨论的评论
            tryGetSingleDiscussionComments(repoInfo, container);
        });
}

/**
 * 尝试获取单个讨论的评论（备用方法）
 */
function tryGetSingleDiscussionComments(repoInfo, container) {
    log('尝试备用方法：获取单个讨论的评论');
    
    // 直接获取讨论ID为1的评论
    fetch(`https://api.github.com/repos/${repoInfo.owner}/${repoInfo.repo}/discussions/1/comments`)
        .then(response => {
            if (!response.ok) {
                throw new Error('获取评论失败: ' + response.status);
            }
            return response.json();
        })
        .then(comments => {
            if (!comments || comments.length === 0) {
                throw new Error('没有找到评论');
            }
            
            log('从单个讨论获取到', comments.length, '条评论');
            
            // 处理评论数据
            const processedComments = comments.map(comment => ({
                author: {
                    name: comment.user.login,
                    avatarUrl: comment.user.avatar_url
                },
                bodyText: comment.body,
                url: comment.html_url,
                createdAt: comment.created_at,
                discussionTitle: '留言板', // 默认标题
                discussionUrl: `https://github.com/${repoInfo.owner}/${repoInfo.repo}/discussions/1`
            }));
            
            // 保存并渲染评论
            saveCommentsToCache(processedComments);
            renderComments(processedComments, container);
        })
        .catch(error => {
            logError('备用方法也失败:', error);
            // 使用硬编码的评论数据作为最后的备用
            useHardcodedComments();
        });
}

/**
 * 使用硬编码的评论数据
 */
function useHardcodedComments() {
    const container = document.getElementById(COMMENTS_CONTAINER_ID);
    if (!container) return;
    
    log('使用硬编码的评论数据');
    
    const hardcodedComments = [
        {
            author: {
                name: 'aw168',
                avatarUrl: 'https://avatars.githubusercontent.com/u/187385069'
            },
            bodyText: '祝大家心想事成！',
            url: 'https://github.com/iawooo/vlty/discussions/5#discussioncomment-12885294',
            createdAt: new Date().toISOString(),
            discussionTitle: '留言板'
        }
    ];
    
    saveCommentsToCache(hardcodedComments);
    renderComments(hardcodedComments, container);
    
    // 显示重试按钮
    const refreshButton = document.querySelector('.comment-refresh');
    if (refreshButton) {
        refreshButton.style.display = 'block';
    }
}

/**
 * 使用备用评论内容
 */
function useFallbackComments() {
    const container = document.getElementById(COMMENTS_CONTAINER_ID);
    if (!container) return;
    
    log('使用备用评论数据');
    
    // 先尝试硬编码的评论
    useHardcodedComments();
}

/**
 * 渲染评论到容器
 */
function renderComments(comments, container) {
    if (!container) return;
    
    if (!comments || comments.length === 0) {
        container.innerHTML = '<div class="aside-list-item"><div class="content" style="text-align: center; width: 100%;"><span class="comment">暂无评论数据</span></div></div>';
        return;
    }
    
    let commentsHtml = '';
    
    // 限制只显示6条评论
    const commentsToShow = comments.slice(0, 6);
    
    commentsToShow.forEach(comment => {
        // 裁剪评论内容
        const excerptLength = 35;
        let commentText = stripMarkdown(comment.bodyText || '');
        commentText = commentText.length > excerptLength ? 
            commentText.substring(0, excerptLength) + '...' : 
            commentText;
            
        // 获取评论的文章标题或默认标题
        const discussionTitle = comment.discussionTitle || '留言板';
        
        commentsHtml += `
        <div class="aside-list-item">
            <a href="${comment.url}" class="thumbnail" target="_blank">
                <img src="${comment.author.avatarUrl}" alt="${comment.author.name}的头像" onerror="this.src='/usr/themes/butterfly/img/avatar.jpg'">
            </a>
            <div class="content">
                <a class="comment" href="${comment.url}" target="_blank" title="来自《${discussionTitle}》的评论">
                    ${commentText}
                </a>
                <div class="name">
                    <span title="${new Date(comment.createdAt).toLocaleString()}">${comment.author.name} / ${formatTimeAgo(comment.createdAt)}</span>
                </div>
            </div>
        </div>
        `;
    });
    
    container.innerHTML = commentsHtml;
    log('评论渲染完成，显示', commentsToShow.length, '条评论');
}

/**
 * 清除Markdown标记
 */
function stripMarkdown(text) {
    if (!text) return '';
    
    return text
        .replace(/!\[.*?\]\(.*?\)/g, '[图片]') // 图片替换为[图片]
        .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '$1') // 链接替换为链接文本
        .replace(/[*_~`]/g, '') // 移除markdown标记
        .replace(/```[\s\S]*?```/g, '[代码]') // 代码块替换为[代码]
        .replace(/`([^`]+)`/g, '$1') // 内联代码移除标记
        .replace(/#+ /g, '') // 移除标题标记
        .replace(/> /g, '') // 移除引用标记
        .replace(/\n/g, ' ') // 换行替换为空格
        .replace(/\s+/g, ' ') // 多个空格替换为一个
        .trim(); // 去除首尾空格
}

/**
 * 格式化时间为友好显示
 */
function formatTimeAgo(timeStr) {
    const time = new Date(timeStr).getTime();
    const now = Date.now();
    const diff = now - time;
    
    if (diff < 60000) {
        return '刚刚';
    } else if (diff < 3600000) {
        return Math.floor(diff / 60000) + '分钟前';
    } else if (diff < 86400000) {
        return Math.floor(diff / 3600000) + '小时前';
    } else if (diff < 2592000000) {
        return Math.floor(diff / 86400000) + '天前';
    } else {
        const date = new Date(time);
        return date.getFullYear() + '-' + 
               (date.getMonth() + 1).toString().padStart(2, '0') + '-' + 
               date.getDate().toString().padStart(2, '0');
    }
}  