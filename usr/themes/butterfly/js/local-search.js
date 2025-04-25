/**
 * 简化版搜索功能
 * 只使用表单搜索，不再加载本地搜索数据
 */
window.addEventListener('load', () => {
  // 处理表单搜索
  const setupFormSearch = () => {
    // 获取所有搜索表单和按钮
    const searchForms = document.querySelectorAll('form[role="search"]');
    const searchButtons = document.querySelectorAll('#search-button .site-page.search, .social-icon.search');
    
    // 处理搜索表单提交
    searchForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const input = form.querySelector('input[name="s"]');
        if (!input || !input.value.trim()) {
          e.preventDefault();
        }
      });
    });
    
    // 添加搜索输入框伸长效果
    const setupExpandableInput = () => {
      const searchInputs = document.querySelectorAll('#dSearchIn, input[name="s"]');
      
      searchInputs.forEach(input => {
        if (!input) return;
        
        // 设置初始样式
        if (!input.dataset.initialWidth) {
          input.dataset.initialWidth = window.getComputedStyle(input).width;
          // 添加过渡效果
          input.style.transition = 'width 0.3s ease-in-out';
        }
        
        // 设置焦点获取和失去时的效果
        input.addEventListener('focus', function() {
          this.style.width = '150px'; // 伸长后的宽度
        });
        
        input.addEventListener('blur', function() {
          if (!this.value.trim()) {
            this.style.width = this.dataset.initialWidth || '35px'; // 恢复初始宽度
          }
        });
      });
    };
    
    // 执行搜索输入框伸长设置
    setupExpandableInput();
    
    // 处理搜索按钮点击
    searchButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        const form = this.querySelector('form');
        const input = form ? form.querySelector('input[name="s"]') : null;
        
        if (input) {
          // 伸长输入框
          input.style.width = '150px';
          
          // 聚焦到输入框
          input.focus();
          
          // 如果已经有内容，提交表单
          if (input.value.trim()) {
            form.submit();
          }
          
          // 阻止其他事件处理
          e.preventDefault();
          e.stopPropagation();
        }
      });
    });
    
    // 阻止输入框点击事件冒泡
    const searchInputs = document.querySelectorAll('form[role="search"] input, #dSearchIn');
    searchInputs.forEach(input => {
      input.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    });
  };

  // 初始化搜索功能
  try {
    setupFormSearch();
    
    // 支持PJAX
    if (window.addEventListener) {
      window.addEventListener('pjax:complete', setupFormSearch);
    }
  } catch (e) {
    console.log('搜索功能设置失败:', e);
  }
}); 