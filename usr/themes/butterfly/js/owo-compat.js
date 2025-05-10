/**
 * OwO 表情兼容脚本
 * 防止 "OwO is not defined" 错误
 */

(function() {
    if (typeof OwO === 'undefined') {
        // 创建一个空的OwO构造函数
        window.OwO = function(config) {
            this.container = null;
            this.target = null;
            this.position = 'down';
            this.width = '100%';
            this.maxHeight = '250px';
            this.api = null;
            
            // 合并配置
            if (config) {
                for (var key in config) {
                    this[key] = config[key];
                }
            }
            
            console.warn('使用了OwO的兼容模式，表情功能可能不可用');
        };
        
        // 添加OwO原型方法
        window.OwO.prototype = {
            init: function() {
                console.log('OwO初始化（兼容模式）');
                return this;
            },
            toggle: function() {
                console.log('OwO切换显示（兼容模式）');
                return this;
            },
            addEmoji: function() {
                console.log('OwO添加表情（兼容模式）');
                return this;
            },
            insertText: function() {
                console.log('OwO插入文本（兼容模式）');
                return this;
            }
        };
        
        console.log('OwO兼容模式已加载');
    }
})(); 