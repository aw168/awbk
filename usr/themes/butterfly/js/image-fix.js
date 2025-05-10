/**
 * 图片修复脚本
 * 解决图片加载失败和模糊问题
 */

(function() {
    // 在DOMContentLoaded后执行主要操作
    document.addEventListener('DOMContentLoaded', function() {
        console.log('图片修复脚本已启动');
        
        // 彻底修复模糊图片问题
        fixBlurryImages();
        
        // 监听DOM变化，处理新添加的图片
        observeDOMChanges();
        
        // 监听图片错误事件
        handleImageErrors();
        
        // 添加图片预览功能
        enableImagePreview();
        
        // 添加修复白色模糊的CSS
        addAntiBlurCSS();
    });
    
    // 修复模糊图片
    function fixBlurryImages() {
        // 所有文章内的图片
        const articleImages = document.querySelectorAll('.article-content img, .post-content img, .fancybox img, #post img');
        
        articleImages.forEach(function(img) {
            // 设置高质量渲染
            applyHighQualitySettings(img);
            
            // 检查是否是懒加载图片
            if (img.hasAttribute('data-lazy-src')) {
                const realSrc = img.getAttribute('data-lazy-src');
                
                // 移除懒加载模糊效果
                if (img.style.filter && img.style.filter.includes('blur')) {
                    img.style.filter = 'none';
                }
                
                // 立即加载图片
                if (!img.classList.contains('loaded')) {
                    // 复制所有属性的新图像替换
                    replaceWithHighQualityImage(img, realSrc);
                }
            }
            
            // 绑定加载完成事件，清除任何残留的模糊效果
            img.addEventListener('load', function() {
                this.classList.add('loaded');
                this.style.filter = 'none';
                
                // 确保图片清晰度
                this.style.imageRendering = 'high-quality';
                this.style.transform = 'translateZ(0)';
                this.style.willChange = 'transform';
            });
        });
        
        // 修复所有图片的渲染模式
        document.querySelectorAll('img').forEach(function(img) {
            applyHighQualitySettings(img);
        });
    }
    
    // 替换为高质量图像
    function replaceWithHighQualityImage(oldImg, highQualitySrc) {
        if (!highQualitySrc || oldImg.classList.contains('no-replace')) return;
        
        // 创建新图像
        const newImg = new Image();
        
        // 复制所有样式和属性
        Array.from(oldImg.attributes).forEach(attr => {
            if (attr.name !== 'src' && attr.name !== 'data-lazy-src') {
                newImg.setAttribute(attr.name, attr.value);
            }
        });
        
        // 设置新图像的样式
        newImg.style.cssText = oldImg.style.cssText;
        newImg.style.filter = 'none';
        newImg.classList.add('high-quality-replacement');
        newImg.classList.add('loaded');
        
        // 设置新的高质量图像源
        newImg.src = highQualitySrc;
        
        // 替换旧图像
        if (oldImg.parentNode) {
            oldImg.parentNode.replaceChild(newImg, oldImg);
        }
    }
    
    // 监听DOM变化
    function observeDOMChanges() {
        // 创建MutationObserver实例
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                // 处理新添加的节点
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // 元素节点
                        const newImages = node.tagName === 'IMG' ? 
                            [node] : node.querySelectorAll('img');
                            
                        newImages.forEach(function(img) {
                            applyHighQualitySettings(img);
                            handleImageError(img);
                            
                            // 如果图片有懒加载属性，立即加载高质量版本
                            if (img.hasAttribute('data-lazy-src') && !img.classList.contains('loaded')) {
                                const realSrc = img.getAttribute('data-lazy-src');
                                replaceWithHighQualityImage(img, realSrc);
                            }
                        });
                    }
                });
            });
        });
        
        // 开始观察
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    // 处理图片加载错误
    function handleImageErrors() {
        document.querySelectorAll('img').forEach(handleImageError);
    }
    
    // 处理单个图片错误
    function handleImageError(img) {
        if (!img.hasAttribute('onerror')) {
            img.onerror = function() {
                // 避免处理已经是错误图片的情况
                if (!this.src.includes('404.jpg') && !this.classList.contains('error-handled')) {
                    console.warn('图片加载失败，尝试恢复:', this.src);
                    
                    // 标记为已处理错误
                    this.classList.add('error-handled');
                    
                    // 尝试从data-lazy-src恢复
                    if (this.hasAttribute('data-lazy-src')) {
                        const originalSrc = this.getAttribute('data-lazy-src');
                        // 如果原始源与当前源不同，尝试使用原始源
                        if (originalSrc && originalSrc !== this.src) {
                            console.log('尝试从原始源恢复:', originalSrc);
                            this.src = originalSrc;
                            return;
                        }
                    }
                    
                    // 如果无法恢复，显示404图片
                    this.src = '/usr/themes/butterfly/img/404.jpg';
                    this.style.opacity = '0.7';
                    this.onerror = null; // 防止循环
                }
            };
        }
    }
    
    // 为图片应用高质量设置
    function applyHighQualitySettings(img) {
        // 设置样式属性
        img.style.imageRendering = '-webkit-optimize-contrast';
        img.style.imageRendering = 'high-quality';
        img.style.transform = 'translateZ(0)'; // 触发GPU加速
        img.style.backfaceVisibility = 'hidden'; // 减少闪烁
        img.style.willChange = 'transform'; // 提示浏览器元素将被改变
        img.style.filter = 'none'; // 移除任何可能的模糊滤镜
        
        // 添加类以便CSS选择器可以应用
        img.classList.add('high-quality-img');
        
        // 设置loading属性以利用浏览器原生懒加载
        if (!img.hasAttribute('loading')) {
            img.setAttribute('loading', 'lazy');
        }
        
        // 设置decoding属性提高解码效率
        if (!img.hasAttribute('decoding')) {
            img.setAttribute('decoding', 'async');
        }
        
        // 确保图片在加载后不会有模糊效果
        img.addEventListener('load', function() {
            this.classList.add('loaded');
            setTimeout(() => {
                this.style.filter = 'none';
            }, 50); // 短暂延迟确保过渡效果被移除
        });
    }
    
    // 启用图片预览功能
    function enableImagePreview() {
        const articleContainer = document.querySelector('.article-content, .post-content');
        
        if (articleContainer) {
            // 为文章中的所有图片添加点击事件
            articleContainer.querySelectorAll('img').forEach(function(img) {
                img.addEventListener('click', function(e) {
                    // 防止与其他事件冲突
                    if (window.isEditMode || img.closest('a')) return;
                    
                    e.preventDefault();
                    
                    // 获取高质量图片源
                    const highQualitySrc = img.getAttribute('data-lazy-src') || img.src;
                    
                    // 创建预览层
                    const overlay = document.createElement('div');
                    overlay.className = 'image-preview-overlay';
                    overlay.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.8);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                        cursor: zoom-out;
                    `;
                    
                    // 创建图片元素
                    const previewImg = document.createElement('img');
                    previewImg.src = highQualitySrc;
                    previewImg.style.cssText = `
                        max-width: 90%;
                        max-height: 90%;
                        object-fit: contain;
                        border-radius: 4px;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                        transform: translateZ(0);
                        image-rendering: high-quality;
                    `;
                    
                    // 添加到页面
                    overlay.appendChild(previewImg);
                    document.body.appendChild(overlay);
                    
                    // 添加关闭功能
                    overlay.addEventListener('click', function() {
                        document.body.removeChild(overlay);
                    });
                });
            });
        }
    }
    
    // 添加防止图片模糊的CSS
    function addAntiBlurCSS() {
        const style = document.createElement('style');
        style.textContent = `
            /* 提高所有图片渲染质量 */
            img {
                image-rendering: -webkit-optimize-contrast !important;
                image-rendering: crisp-edges !important;
                image-rendering: high-quality !important;
                -ms-interpolation-mode: nearest-neighbor !important;
                backface-visibility: hidden !important;
                transform: translateZ(0) !important;
                will-change: transform !important;
                filter: none !important;
            }
            
            /* 修复懒加载过程中可能出现的白色模糊 */
            img[data-lazy-src]:not(.loaded) {
                filter: blur(0px) !important;
                opacity: 1 !important;
                transition: none !important;
            }
            
            /* 防止图片加载过程中的过渡效果导致模糊 */
            img.loaded {
                filter: none !important;
                transition: none !important;
                opacity: 1 !important;
            }
            
            /* 完全加载后的图片 */
            .high-quality-img, .high-quality-replacement {
                transform: translateZ(0);
                backface-visibility: hidden;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
                filter: none !important;
            }
            
            /* 防止图片因为GPU渲染问题导致的模糊 */
            .post-content img, .article-content img, .fancybox img {
                transform: translateZ(0);
                backface-visibility: hidden;
                will-change: transform;
                filter: none !important;
            }
            
            /* 禁用任何模糊过渡或特效 */
            [class*="lazyload"], [class*="lazy-load"] {
                filter: none !important;
                transition: none !important;
            }
            
            /* 强制所有图片使用高质量渲染 */
            * {
                image-rendering: auto !important;
            }
            
            img {
                image-rendering: -webkit-optimize-contrast !important;
                image-rendering: crisp-edges !important;
            }
            
            /* 消除任何会导致图片模糊的滤镜效果 */
            .blur-up, .blur-load {
                filter: none !important;
            }
        `;
        document.head.appendChild(style);
    }
})(); 