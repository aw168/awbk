// 搜索功能简化版
// 由于旧版过于复杂，这个版本采用最简单的方式实现搜索功能

// 当用户点击搜索按钮时打开搜索对话框
function openSimpleSearch() {
    console.log('打开简化版搜索对话框');
    const searchBox = document.getElementById('local-search');
    if (searchBox) {
        searchBox.style.display = 'block';
        
        const searchInput = document.getElementById('local-search-input');
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 100);
        }
    } else {
        console.error('未找到搜索对话框元素 #local-search');
    }
}

// 关闭搜索对话框
function closeSimpleSearch() {
    console.log('关闭简化版搜索对话框');
    const searchBox = document.getElementById('local-search');
    if (searchBox) {
        searchBox.style.display = 'none';
    }
}

// 在页面加载完成后初始化搜索功能
document.addEventListener('DOMContentLoaded', function() {
    // 初始化搜索按钮
    const searchButtons = document.querySelectorAll('#search-button, .search-button');
    searchButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openSimpleSearch();
        });
    });
    
    // 初始化关闭按钮
    const closeButton = document.querySelector('.search-close-button');
    if (closeButton) {
        closeButton.addEventListener('click', closeSimpleSearch);
    }
    
    // 点击背景关闭搜索
    const searchContainer = document.getElementById('local-search');
    if (searchContainer) {
        searchContainer.addEventListener('click', function(e) {
            if (e.target.id === 'local-search') {
                closeSimpleSearch();
            }
        });
    }
    
    // 定义全局函数供其他地方调用
    window.openSearch = openSimpleSearch;
    window.closeSearch = closeSimpleSearch;
});
