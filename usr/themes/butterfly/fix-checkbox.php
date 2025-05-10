<?php
require_once 'config.inc.php';

// 权限检查
if (!defined('__TYPECHO_ADMIN__')) {
    define('__TYPECHO_ADMIN__', true);
}

try {
    $db = Typecho_Db::get();
    $options = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterfly'));
    
    if ($options) {
        echo "找到主题设置，正在检查...<br>";
        
        $optionsValue = @unserialize($options['value']);
        if (!$optionsValue) {
            echo "错误：无法反序列化主题设置！<br>";
            exit;
        }
        
        // 需要检查和修复的复选框选项
        $checkboxKeys = ['sidebarBlock', 'PostSidebarBlock', 'beautifyBlock'];
        $updated = false;
        
        foreach ($checkboxKeys as $key) {
            echo "检查复选框选项：{$key}<br>";
            
            // 输出当前值
            echo "当前值：";
            if (isset($optionsValue[$key])) {
                if (is_array($optionsValue[$key])) {
                    echo "数组，包含 " . count($optionsValue[$key]) . " 个元素<br>";
                    echo "元素：[" . implode(", ", $optionsValue[$key]) . "]<br>";
                } else {
                    echo "非数组值：" . var_export($optionsValue[$key], true) . "<br>";
                    
                    // 修复非数组值
                    echo "修复为空数组<br>";
                    $optionsValue[$key] = array();
                    $updated = true;
                }
            } else {
                echo "未设置<br>";
                
                // 初始化未设置的选项为空数组
                echo "初始化为空数组<br>";
                $optionsValue[$key] = array();
                $updated = true;
            }
        }
        
        // 更新设置到数据库
        if ($updated) {
            $serialized = serialize($optionsValue);
            $db->query($db->update('table.options')->rows(['value' => $serialized])->where('name = ?', 'theme:butterfly'));
            echo "已更新主题设置！<br>";
        } else {
            echo "复选框选项已经正常，无需修复。<br>";
        }
        
        echo "<br>完成。请返回主题设置页面重新配置复选框选项。<br>";
        echo "<a href=\"" . Typecho_Common::url('options-theme.php', Helper::options()->adminUrl) . "\">返回主题设置</a>";
    } else {
        echo "错误：未找到主题设置！<br>";
    }
} catch (Exception $e) {
    echo "发生错误：" . $e->getMessage() . "<br>";
} 