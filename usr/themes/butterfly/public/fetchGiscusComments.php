<?php
/**
 * 从GitHub获取giscus评论的PHP脚本
 * 这个脚本应该通过cron作业定期运行（例如每小时一次）
 */

// 配置参数
$config = [
    'owner' => '', // 您的GitHub用户名，例如 'username'
    'repo' => '',  // 您的GitHub仓库名，例如 'your-repo'
    'per_page' => 30, // 每页获取的讨论数量
    'cache_file' => __DIR__ . '/data/recent_comments.json', // 缓存文件路径
    'cache_dir' => __DIR__ . '/data', // 缓存目录
    'comments_limit' => 6, // 最多保存多少条评论
    'cache_time' => 3600, // 缓存有效期（秒），1小时
];

// 确保配置了GitHub仓库信息
if (empty($config['owner']) || empty($config['repo'])) {
    die("错误：请在配置中设置GitHub用户名和仓库名\n");
}

// 确保缓存目录存在
if (!file_exists($config['cache_dir'])) {
    mkdir($config['cache_dir'], 0755, true);
}

// 检查是否需要更新缓存
if (file_exists($config['cache_file']) && (time() - filemtime($config['cache_file'])) < $config['cache_time']) {
    echo "缓存文件仍然有效，跳过更新\n";
    exit(0);
}

/**
 * 发送HTTP请求到GitHub API
 */
function sendRequest($url, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
        'User-Agent: GiscusCommentsFetcher',
        'Accept: application/vnd.github.v3+json',
    ], $headers));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode !== 200) {
        echo "API请求失败，HTTP代码: $httpCode\n";
        echo "URL: $url\n";
        echo "响应: $response\n";
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

/**
 * 获取仓库中的所有讨论
 */
function getDiscussions($owner, $repo, $perPage) {
    $url = "https://api.github.com/repos/$owner/$repo/discussions?per_page=$perPage";
    return sendRequest($url);
}

/**
 * 获取讨论的评论
 */
function getComments($owner, $repo, $discussionNumber, $perPage) {
    $url = "https://api.github.com/repos/$owner/$repo/discussions/$discussionNumber/comments?per_page=$perPage";
    return sendRequest($url);
}

/**
 * 获取讨论的标题（用于关联评论和文章）
 */
function getDiscussionTitle($discussion) {
    return $discussion['title'] ?? '未知文章';
}

/**
 * 处理评论数据，提取需要的信息
 */
function processComment($comment, $discussionTitle, $discussionUrl) {
    return [
        'id' => $comment['id'],
        'author' => [
            'name' => $comment['author']['login'] ?? '匿名用户',
            'avatarUrl' => $comment['author']['avatar_url'] ?? '',
            'url' => $comment['author']['html_url'] ?? '',
        ],
        'body' => $comment['body'] ?? '',
        'bodyText' => preg_replace('/\s+/', ' ', strip_tags($comment['body'] ?? '')),
        'url' => $comment['html_url'] ?? '',
        'createdAt' => $comment['created_at'] ?? '',
        'discussionTitle' => $discussionTitle,
        'discussionUrl' => $discussionUrl,
    ];
}

// 开始获取讨论和评论
echo "开始获取GitHub Discussions...\n";
$discussions = getDiscussions($config['owner'], $config['repo'], $config['per_page']);

if (!is_array($discussions)) {
    die("无法获取讨论列表\n");
}

echo "获取到" . count($discussions) . "个讨论\n";

$allComments = [];

// 处理每个讨论
foreach ($discussions as $discussion) {
    $discussionNumber = $discussion['number'];
    $discussionTitle = getDiscussionTitle($discussion);
    $discussionUrl = $discussion['html_url'] ?? '';
    
    echo "处理讨论: $discussionTitle\n";
    
    // 获取讨论的评论
    $comments = getComments($config['owner'], $config['repo'], $discussionNumber, 10);
    
    if (!is_array($comments)) {
        echo "无法获取讨论#$discussionNumber的评论，跳过\n";
        continue;
    }
    
    // 处理每条评论
    foreach ($comments as $comment) {
        $processedComment = processComment($comment, $discussionTitle, $discussionUrl);
        $allComments[] = $processedComment;
    }
}

// 按创建时间排序，最新的在前
usort($allComments, function($a, $b) {
    return strtotime($b['createdAt']) - strtotime($a['createdAt']);
});

// 只保留指定数量的评论
$allComments = array_slice($allComments, 0, $config['comments_limit']);

// 保存到缓存文件
$cacheData = [
    'comments' => $allComments,
    'updatedAt' => date('Y-m-d H:i:s'),
];

file_put_contents($config['cache_file'], json_encode($cacheData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "已成功获取并缓存" . count($allComments) . "条最新评论\n"; 