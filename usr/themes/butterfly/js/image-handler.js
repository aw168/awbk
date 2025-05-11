/**
 * 图片处理优化脚本
 * 解决图片加载问题和尺寸控制
 */

(function() {
    document.addEventListener('DOMContentLoaded', function() {
        // 处理邮件图标尺寸
        const mailIcons = document.querySelectorAll('img[src*="mail.svg"]');
        mailIcons.forEach(function(img) {
            img.style.maxWidth = '24px';
            img.style.maxHeight = '24px';
            img.style.width = '24px';
            img.style.height = '24px';
            img.style.verticalAlign = 'middle';
        });
        
        // 处理懒加载中的图片
        const lazyImages = document.querySelectorAll('img[data-lazy-src]');
        lazyImages.forEach(function(img) {
            // 添加错误处理
            img.onerror = function() {
                this.src = '/usr/themes/butterfly/img/404.jpg';
                this.classList.add('error');
                this.onerror = null; // 防止循环触发
            };
            
            // 提高图片质量设置 - 对所有图片应用更高的质量参数
            const dataSrc = img.getAttribute('data-lazy-src');
            if (dataSrc) {
                let optimizedSrc = dataSrc;
                // 为特定图床添加高质量参数
                if (dataSrc.includes('s2.loli.net') || 
                    dataSrc.includes('awtc.pp.ua') || 
                    dataSrc.includes('loli.net') || 
                    dataSrc.includes('pp.ua')) {
                    if (!dataSrc.includes('quality=')) {
                        const separator = dataSrc.includes('?') ? '&' : '?';
                        optimizedSrc = dataSrc + separator + 'quality=100';
                    } else if (dataSrc.match(/quality=\d+/) && !dataSrc.includes('quality=100')) {
                        // 替换为最高质量
                        optimizedSrc = dataSrc.replace(/quality=\d+/, 'quality=100');
                    }
                    img.setAttribute('data-lazy-src', optimizedSrc);
                }
                
                // 设置初始显示时的属性，以提高第一次显示时的质量
                img.setAttribute('decoding', 'async');
                img.setAttribute('loading', 'lazy');
                img.classList.add('high-quality-img');
            }
            
            // 500毫秒后如果还在加载中，尝试直接加载
            setTimeout(function() {
                if (!img.complete || img.naturalHeight === 0) {
                    const optimizedSrc = img.getAttribute('data-lazy-src');
                    if (optimizedSrc) {
                        img.src = optimizedSrc;
                        img.classList.add('loaded');
                        img.style.imageRendering = 'high-quality';
                    }
                }
            }, 500);
        });
        
        // 所有图片加载完成事件处理
        document.querySelectorAll('img').forEach(function(img) {
            if (img.complete) {
                applyImageOptimizations(img);
            } else {
                img.onload = function() {
                    applyImageOptimizations(img);
                };
            }
            
            // 为所有图片设置错误处理
            if (!img.hasAttribute('onerror')) {
                img.onerror = function() {
                    if (!this.src.includes('404.jpg')) {
                        console.log('图片加载失败:', this.src);
                        this.src = '/usr/themes/butterfly/img/404.jpg';
                        this.classList.add('error');
                        this.onerror = null; // 防止循环触发
                    }
                };
            }
        });
    });
    
    // 监听DOM变化，处理新加入的图片
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // 元素节点
                    const images = node.tagName === 'IMG' ? [node] : node.querySelectorAll('img');
                    images.forEach(function(img) {
                        // 处理新添加的图片
                        if (img.hasAttribute('data-lazy-src')) {
                            const dataSrc = img.getAttribute('data-lazy-src');
                            if (dataSrc && !dataSrc.includes('quality=') &&
                                (dataSrc.includes('s2.loli.net') || 
                                dataSrc.includes('awtc.pp.ua') || 
                                dataSrc.includes('loli.net') || 
                                dataSrc.includes('pp.ua'))) {
                                const separator = dataSrc.includes('?') ? '&' : '?';
                                img.setAttribute('data-lazy-src', dataSrc + separator + 'quality=100');
                            }
                        }
                        
                        // 设置图片加载事件
                        if (!img.complete) {
                            img.onload = function() {
                                applyImageOptimizations(img);
                            };
                        } else {
                            applyImageOptimizations(img);
                        }
                    });
                }
            });
        });
    });
    
    // 开始观察文档变化
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // 当新内容加载时，重新处理图片
    document.addEventListener('lazyloaded', function(e) {
        if (e.target.tagName === 'IMG') {
            applyImageOptimizations(e.target);
        }
    });
    
    // 集中处理图片优化逻辑
    function applyImageOptimizations(img) {
        img.classList.add('loaded');
        // 应用高质量渲染设置
        img.style.imageRendering = 'high-quality';
        img.style.transform = 'translateZ(0)'; // 启用GPU加速，减少模糊
        
        // 如果是文章内图片，添加额外处理
        if (img.closest('.article-content')) {
            img.addEventListener('click', function() {
                // 点击图片时使用高质量版本预览
                if (img.hasAttribute('data-lazy-src')) {
                    const highQualitySrc = img.getAttribute('data-lazy-src');
                    if (highQualitySrc && img.src !== highQualitySrc) {
                        img.setAttribute('data-original-src', img.src);
                        img.src = highQualitySrc;
                    }
                }
            });
        }
    }
    
    // 添加CSS来提高图片渲染质量
    const style = document.createElement('style');
    style.textContent = `
        img.loaded, img.high-quality-img {
            image-rendering: -webkit-optimize-contrast !important;
            image-rendering: high-quality !important;
            transform: translateZ(0);
            transition: filter 0.3s ease-out;
            filter: none !important; /* 移除可能的模糊滤镜 */
            backface-visibility: hidden; /* 减少闪烁 */
        }
        
        img[data-lazy-src]:not(.loaded) {
            filter: blur(0px) !important; /* 确保未加载时不模糊 */
        }
        
        .article-content img {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            display: block;
        }
        
        @media (max-width: 768px) {
            /* 移动设备上避免过度缩放图片 */
            .article-content img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
    `;
    document.head.appendChild(style);
    
    console.log('增强版图片处理脚本已加载');
})(); 