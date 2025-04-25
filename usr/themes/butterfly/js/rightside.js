/* 侧边栏功能 */
document.addEventListener('DOMContentLoaded', function() {
    const rightSide = document.querySelector('#rightside');
    
    if (!rightSide) {
        console.warn('找不到侧边栏元素，跳过初始化');
        return;
    }

    // 返回顶部按钮
    const goUpButton = document.querySelector('#go-up');
    if (goUpButton) {
        goUpButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // 控制返回顶部按钮的显示/隐藏
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > 300) {
                goUpButton.classList.add('show');
            } else {
                goUpButton.classList.remove('show');
            }
            
            // 更新滚动百分比
            if (goUpButton.classList.contains('show-percent')) {
                const h = document.documentElement;
                const scrollPercent = Math.round((scrollTop / (h.scrollHeight - h.clientHeight)) * 100);
                const scrollPercentElem = goUpButton.querySelector('.scroll-percent');
                if (scrollPercentElem) {
                    scrollPercentElem.textContent = scrollPercent + '%';
                }
            }
        });
    }

    // 阅读模式
    const readModeBtn = document.querySelector('#readmode');
    if (readModeBtn) {
        readModeBtn.addEventListener('click', function() {
            document.body.classList.toggle('read-mode');
            const exitButton = document.createElement('button');
            exitButton.classList.add('exit-readmode');
            exitButton.innerHTML = '<i class="fas fa-times"></i>';
            exitButton.onclick = function() {
                document.body.classList.remove('read-mode');
                this.remove();
            };
            
            if (document.body.classList.contains('read-mode')) {
                document.body.appendChild(exitButton);
            } else {
                const existingExitButton = document.querySelector('.exit-readmode');
                if (existingExitButton) {
                    existingExitButton.remove();
                }
            }
        });
    }

    // 简繁转换
    const translateBtn = document.querySelector('#translate');
    if (translateBtn) {
        translateBtn.addEventListener('click', function() {
            const isTraditional = document.documentElement.classList.contains('chinese-traditional');
            
            if (isTraditional) {
                document.documentElement.classList.remove('chinese-traditional');
                document.documentElement.classList.add('chinese-simplified');
            } else {
                document.documentElement.classList.remove('chinese-simplified');
                document.documentElement.classList.add('chinese-traditional');
            }
        });
    }
    
    // 暗黑模式切换
    const darkModeBtn = document.querySelector('#darkmode');
    if (darkModeBtn) {
        darkModeBtn.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const targetTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', targetTheme);
            localStorage.setItem('theme', targetTheme);
            
            // 根据当前主题切换图标
            if (targetTheme === 'dark') {
                this.classList.add('darkmode-active');
            } else {
                this.classList.remove('darkmode-active');
            }
        });
        
        // 初始化主题状态
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                darkModeBtn.classList.add('darkmode-active');
            }
        }
    }
    
    // 隐藏侧边栏
    const hideAsideBtn = document.querySelector('#hide-aside-btn');
    if (hideAsideBtn) {
        hideAsideBtn.addEventListener('click', function() {
            const htmlDom = document.documentElement;
            htmlDom.classList.toggle('hide-aside');
            
            // 保存状态到本地存储
            if (htmlDom.classList.contains('hide-aside')) {
                localStorage.setItem('hide-aside', 'true');
            } else {
                localStorage.removeItem('hide-aside');
            }
        });
        
        // 初始化侧边栏状态
        if (localStorage.getItem('hide-aside') === 'true') {
            document.documentElement.classList.add('hide-aside');
        }
    }
}); 