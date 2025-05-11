/**
 * 修复Typecho主题设置中复选框无法保存的问题
 */
document.addEventListener('DOMContentLoaded', function() {
    // 获取表单
    var form = document.querySelector('.typecho-page-main form');
    if (!form) return;
    
    // 设置所有复选框的autocomplete属性为off
    var allCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    allCheckboxes.forEach(function(checkbox) {
        checkbox.setAttribute('autocomplete', 'off');
    });
    
    // 在表单提交前处理复选框
    form.addEventListener('submit', function(e) {
        // 彻底处理复选框问题
        var checkboxGroups = {};
        
        // 收集所有复选框组
        allCheckboxes.forEach(function(checkbox) {
            if (checkbox.name && checkbox.name.endsWith('[]')) {
                var groupName = checkbox.name.slice(0, -2); // 移除[]
                if (!checkboxGroups[groupName]) {
                    checkboxGroups[groupName] = [];
                }
                checkboxGroups[groupName].push(checkbox);
            }
        });
        
        // 处理每个复选框组
        for (var groupName in checkboxGroups) {
            var hasChecked = false;
            
            // 检查该组是否有选中的复选框
            checkboxGroups[groupName].forEach(function(checkbox) {
                if (checkbox.checked) {
                    hasChecked = true;
                }
            });
            
            // 如果没有选中的复选框，创建一个隐藏字段，确保提交空数组
            if (!hasChecked) {
                var hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = groupName;
                hiddenField.value = ''; // 空值表示没有选择
                form.appendChild(hiddenField);
                
                console.log('添加空值隐藏字段:', groupName);
            }
        }
    });
    
    // 监控复选框变化，确保其值能被正确保存
    allCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // 将复选框的值存储在会话存储中，作为备份
            if (checkbox.name && checkbox.name.endsWith('[]')) {
                var groupName = checkbox.name.slice(0, -2);
                var checkboxId = checkbox.id || checkbox.name + '_' + checkbox.value;
                
                // 创建或更新sessionStorage
                var groupData = sessionStorage.getItem('checkbox_' + groupName) ? 
                                JSON.parse(sessionStorage.getItem('checkbox_' + groupName)) : {};
                
                groupData[checkboxId] = checkbox.checked;
                sessionStorage.setItem('checkbox_' + groupName, JSON.stringify(groupData));
            }
        });
    });
    
    // 特殊处理：强制刷新表单后恢复复选框状态
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // 页面从浏览器缓存中恢复，尝试恢复复选框状态
            allCheckboxes.forEach(function(checkbox) {
                if (checkbox.name && checkbox.name.endsWith('[]')) {
                    var groupName = checkbox.name.slice(0, -2);
                    var checkboxId = checkbox.id || checkbox.name + '_' + checkbox.value;
                    
                    var groupData = sessionStorage.getItem('checkbox_' + groupName) ? 
                                   JSON.parse(sessionStorage.getItem('checkbox_' + groupName)) : {};
                    
                    if (groupData[checkboxId] !== undefined) {
                        checkbox.checked = groupData[checkboxId];
                    }
                }
            });
        }
    });
}); 