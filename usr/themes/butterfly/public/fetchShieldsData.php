<?php
/*
 * @Author: 林郅言 lllzzzyyy@buaa.edu.cn
 * @Date: 2024-02-28 12:07:20
 * @LastEditTime: 2024-02-28 14:35:25
 * @FilePath: \butterfly\public\fetchShieldsData.php
 * @Description: 
 * 
 */
function getBadge($badgeUrl)
{
    // 使用cURL进行请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $badgeUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // 获取响应内容
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$badgeUrl = 'https://img.shields.io/badge/Comments-Typecho-4DABF7?logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTTIwIDJINGMtMS4xIDAtMS45OS45LTEuOTkgMkwyIDIybDQtNGgxNGMxLjEgMCAyLS45IDItMlY0YzAtMS4xLS45LTItMi0yem0tMiAxMkg2di0yaDEydjJ6bTAtM0g2VjloMTJ2MnptMC0zSDZWNmgxMnYyeiI+PC9wYXRoPjwvc3ZnPg==';
echo getBadge($badgeUrl);
// 如果没有指定badge参数，可以返回错误信息或者默认页面
// echo "No badge specified";
