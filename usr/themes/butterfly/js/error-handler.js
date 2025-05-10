/**
 * 全局错误处理器
 * 解决常见的JavaScript错误和资源加载问题
 */

(function() {
    // 防止OwO未定义错误
    window.OwO = window.OwO || function() {
        console.warn('OwO未加载，调用被忽略');
        return {
            init: function() { return this; }
        };
    };
    
    // 防止Chart未定义错误
    window.Chart = window.Chart || function() {
        console.warn('Chart.js未加载，调用被忽略');
        return {};
    };
    
    // 全局错误监听器，过滤掉已知的无害错误
    window.addEventListener('error', function(event) {
        // 忽略404错误和MIME类型错误
        if (
            (event.filename && (
                event.filename.includes('/0') ||
                event.filename.includes('rightside.js') ||
                event.filename.includes('search.php')
            )) ||
            (event.message && (
                event.message.includes('MIME type') ||
                event.message.includes('OwO') ||
                event.message.includes('Chart') ||
                event.message.includes('reCAPTCHA') ||
                event.message.includes('placeholder')
            ))
        ) {
            console.warn('忽略非关键错误:', event.message || event.filename);
            event.preventDefault();
            return false;
        }
    }, true);
    
    // 修复懒加载
    document.addEventListener('DOMContentLoaded', function() {
        // 替换loading状态的图片为占位符
        document.querySelectorAll('img[data-ll-status="loading"]').forEach(function(img) {
            var placeholder = document.createElement('span');
            placeholder.className = 'lazy-img-placeholder';
            placeholder.title = '图片正在加载中...';
            if (img.parentNode) {
                img.parentNode.insertBefore(placeholder, img);
                img.style.display = 'none';
                
                // 3秒后尝试加载图片
                setTimeout(function() {
                    img.setAttribute('src', img.getAttribute('data-lazy-src') || img.getAttribute('src'));
                    img.style.display = '';
                    if (placeholder.parentNode) {
                        placeholder.parentNode.removeChild(placeholder);
                    }
                }, 3000);
            }
        });
    });
    
    // 全局变量补丁
    window.recaptchaCallback = window.recaptchaCallback || function() {
        console.log('reCAPTCHA回调被调用');
    };
    
    // hCaptcha错误修复
    if (typeof hcaptcha === 'undefined') {
        window.hcaptcha = {
            render: function() {
                console.warn('hCaptcha未正确配置，渲染被忽略');
                return '';
            }
        };
    }
    
    console.log('全局错误处理器已加载');
})(); 