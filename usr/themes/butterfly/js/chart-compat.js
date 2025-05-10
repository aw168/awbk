/**
 * Chart.js 兼容脚本
 * 防止 "Chart is not defined" 错误
 */

(function() {
    if (typeof Chart === 'undefined') {
        // 创建一个模拟的Chart对象
        window.Chart = function(context, config) {
            this.context = context;
            this.config = config;
            this.data = config.data || {};
            this.options = config.options || {};
            
            console.warn('使用了Chart.js的兼容模式，图表功能不可用');
            
            return {
                update: function() { return this; },
                destroy: function() { return this; },
                render: function() { return this; },
                resize: function() { return this; },
                clear: function() { return this; },
                stop: function() { return this; },
                toBase64Image: function() { return ''; },
                generateLegend: function() { return document.createElement('div'); },
                getElementAtEvent: function() { return []; },
                getElementsAtEvent: function() { return []; },
                getDatasetAtEvent: function() { return []; },
                getDatasetMeta: function() { return {}; }
            };
        };
        
        // 添加Chart静态属性和方法
        window.Chart.defaults = {
            global: {
                responsive: true,
                maintainAspectRatio: true,
                events: ['mousemove', 'mouseout', 'click', 'touchstart', 'touchmove', 'touchend'],
                hover: {
                    onHover: null,
                    mode: 'nearest',
                    intersect: true,
                    animationDuration: 400
                },
                onClick: null,
                defaultColor: 'rgba(0,0,0,0.1)',
                defaultFontColor: '#666',
                defaultFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                defaultFontSize: 12,
                defaultFontStyle: 'normal',
                showLines: true
            }
        };
        
        // 必要的图表类型
        window.Chart.controllers = {};
        
        // 模拟注册方法
        window.Chart.register = function() { return window.Chart; };
        window.Chart.unregister = function() { return window.Chart; };
        
        console.log('Chart.js兼容模式已加载');
    }
})(); 