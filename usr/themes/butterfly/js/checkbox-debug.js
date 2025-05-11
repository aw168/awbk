/**
 * 复选框调试工具 - 用于检查复选框保存问题
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('复选框调试工具已加载');
    
    // 寻找表单
    var form = document.querySelector('.typecho-page-main form');
    if (!form) {
        console.log('未找到主题设置表单');
        return;
    }
    
    // 找到所有复选框
    var allCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    console.log('找到复选框数量: ' + allCheckboxes.length);
    
    // 收集所有复选框组的信息
    var checkboxGroups = {};
    allCheckboxes.forEach(function(checkbox) {
        if (checkbox.name && checkbox.name.endsWith('[]')) {
            var groupName = checkbox.name.slice(0, -2);
            if (!checkboxGroups[groupName]) {
                checkboxGroups[groupName] = [];
            }
            checkboxGroups[groupName].push({
                element: checkbox,
                name: checkbox.name,
                value: checkbox.value,
                checked: checkbox.checked
            });
        }
    });
    
    // 打印复选框组信息
    console.log('复选框组信息:', checkboxGroups);
    
    // 为每个复选框添加修改监听器
    allCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            console.log('复选框已修改:', this.name, this.value, this.checked);
        });
    });
    
    // 监听表单提交
    form.addEventListener('submit', function(e) {
        console.log('表单正在提交...');
        
        // 查看每个复选框组的状态
        var submitState = {};
        for (var groupName in checkboxGroups) {
            var checked = [];
            checkboxGroups[groupName].forEach(function(item) {
                if (item.element.checked) {
                    checked.push(item.value);
                }
            });
            submitState[groupName] = checked;
        }
        
        console.log('提交时复选框状态:', submitState);
        
        // 确保每个复选框组都有值提交
        for (var groupName in submitState) {
            if (submitState[groupName].length === 0) {
                console.log('添加空值提交给:', groupName);
                
                // 添加一个隐藏字段，确保提交空值
                var hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = groupName;
                hiddenField.value = '';
                form.appendChild(hiddenField);
            }
        }
        
        // 添加提交标记
        var submitFlag = document.createElement('input');
        submitFlag.type = 'hidden';
        submitFlag.name = 'checkbox_debug_submitted';
        submitFlag.value = '1';
        form.appendChild(submitFlag);
    });
    
    // 高亮显示复选框，方便用户看到它们的位置
    allCheckboxes.forEach(function(checkbox) {
        var parent = checkbox.parentElement;
        if (parent) {
            parent.style.border = '1px dashed blue';
            parent.style.padding = '5px';
            parent.style.margin = '2px';
        }
    });
    
    // 创建调试面板
    var debugPanel = document.createElement('div');
    debugPanel.style.position = 'fixed';
    debugPanel.style.bottom = '10px';
    debugPanel.style.right = '10px';
    debugPanel.style.width = '300px';
    debugPanel.style.padding = '10px';
    debugPanel.style.background = 'rgba(0,0,0,0.8)';
    debugPanel.style.color = '#fff';
    debugPanel.style.borderRadius = '5px';
    debugPanel.style.zIndex = '9999';
    debugPanel.innerHTML = '<h3 style="margin:0;padding:0">复选框调试面板</h3>' + 
                          '<p>已找到 ' + allCheckboxes.length + ' 个复选框</p>' +
                          '<p>复选框组: ' + Object.keys(checkboxGroups).join(', ') + '</p>' +
                          '<button id="debug-check-all" style="margin:5px">全选</button>' +
                          '<button id="debug-uncheck-all" style="margin:5px">全不选</button>' +
                          '<button id="debug-toggle-highlight" style="margin:5px">切换高亮</button>' +
                          '<button id="debug-close" style="margin:5px">关闭面板</button>';
    
    document.body.appendChild(debugPanel);
    
    // 调试按钮功能
    document.getElementById('debug-check-all').addEventListener('click', function() {
        allCheckboxes.forEach(function(checkbox) {
            checkbox.checked = true;
        });
    });
    
    document.getElementById('debug-uncheck-all').addEventListener('click', function() {
        allCheckboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    });
    
    var highlighted = true;
    document.getElementById('debug-toggle-highlight').addEventListener('click', function() {
        highlighted = !highlighted;
        allCheckboxes.forEach(function(checkbox) {
            var parent = checkbox.parentElement;
            if (parent) {
                if (highlighted) {
                    parent.style.border = '1px dashed blue';
                } else {
                    parent.style.border = 'none';
                }
            }
        });
    });
    
    document.getElementById('debug-close').addEventListener('click', function() {
        debugPanel.style.display = 'none';
    });
}); 