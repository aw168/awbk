<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
require_once('config/custom_config.php');

// 添加搜索数据生成功能
require_once 'generate-search.php';

// 添加搜索数据生成页面
function generateSearchPage() {
    // 此函数将在访问 extending.php?panel=generate-search 时执行
    include 'generate-search.php';
}

// 添加菜单项到管理后台
function themeMenu($items) {
    // 在原有菜单项基础上添加新菜单项
    $items['generate-search'] = _t('生成搜索数据');
    return $items;
}

// 使用Typecho的方式注册后台菜单和页面
Typecho_Plugin::factory('admin/menu.php')->navBar = 'themeMenu';
Typecho_Plugin::factory('admin/extend.php')->panelHandle = 'generateSearchPage';

// 新文章缩略图
function get_ArticleThumbnail($widget)
{
    $category_thumbnails = array(
        '知识' => 'https://s2.loli.net/2024/02/03/eN9YRxCr3jQ8dPm.jpg',
        '折腾' => 'https://s2.loli.net/2024/02/19/OrYXvf95xGZUy7w.jpg',
        '生活' => 'https://s2.loli.net/2024/02/19/WA4ELGHqomSIKdF.png',
    );

    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';

    // 获取当前文章的第一个分类名称
    $current_category_name = '';
    if ($widget->categories) {
        foreach ($widget->categories as $category) {
            $current_category_name = $category['name']; // 使用分类的名称作为键
            break; // 获取第一个分类后即退出循环
        }
    }

    // 如果有自定义缩略图
    if ($widget->fields->thumb) {
        return $widget->fields->thumb;
    }
    // 如果文章内容中有图片
    else if (preg_match_all($pattern, $widget->content, $thumbUrl) && strlen($thumbUrl[1][0]) > 7) {
        return $thumbUrl[1][0];
    }
    // 如果能够根据分类找到对应的缩略图
    if (array_key_exists($current_category_name, $category_thumbnails)) {
        return $category_thumbnails[$current_category_name];
    } else {
        return 'https://awtc.pp.ua/3.jpg';
    }
}


// 主页文章缩略图
function GetRandomThumbnail($widget)
{
    $category_thumbnails = array(
        '知识' => 'https://s2.loli.net/2024/02/03/eN9YRxCr3jQ8dPm.jpg',
        '折腾' => 'https://s2.loli.net/2024/02/19/OrYXvf95xGZUy7w.jpg',
        '生活' => 'https://s2.loli.net/2024/02/19/WA4ELGHqomSIKdF.png',
    );

    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';

    // 获取当前文章的第一个分类名称
    $current_category_name = '';
    if ($widget->categories) {
        foreach ($widget->categories as $category) {
            $current_category_name = $category['name']; // 使用分类的名称作为键
            break; // 获取第一个分类后即退出循环
        }
    }

    // 如果有自定义缩略图
    if ($widget->fields->thumb) {
        echo $widget->fields->thumb;
    }
    // 如果文章内容中有图片
    else if (preg_match_all($pattern, $widget->content, $thumbUrl) && strlen($thumbUrl[1][0]) > 7) {
        echo $thumbUrl[1][0];
    }
    // 如果能够根据分类找到对应的缩略图
    else if (array_key_exists($current_category_name, $category_thumbnails)) {
        echo $category_thumbnails[$current_category_name];
    }
    // 如果没有匹配的分类，返回一个全局默认的缩略图
    else {
        $random = 'https://awtc.pp.ua/3.png'; // 默认缩略图
        if (Helper::options()->futureRandom) {
            $moszu = explode("\r\n", Helper::options()->futureRandom);
            $random = $moszu[array_rand($moszu, 1)] . "?futureRandom=" . mt_rand(0, 1000000);
        }
        echo $random;
    }
}

// 文章封面缩略图
function GetRandomThumbnailPost($widget)
{
    $category_thumbnails = array(
        '知识' => 'https://s2.loli.net/2024/02/03/eN9YRxCr3jQ8dPm.jpg',
        '折腾' => 'https://s2.loli.net/2024/02/19/OrYXvf95xGZUy7w.jpg',
        '生活' => 'https://s2.loli.net/2024/02/19/WA4ELGHqomSIKdF.png',
    );

    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';

    // 获取当前文章的第一个分类名称
    $current_category_name = '';
    if ($widget->categories) {
        foreach ($widget->categories as $category) {
            $current_category_name = $category['name']; // 使用分类的名称作为键
            break; // 获取第一个分类后即退出循环
        }
    }

    // 如果有自定义缩略图
    if ($widget->fields->thumb) {
        echo $widget->fields->thumb;
    }
    // 如果文章内容中有图片
    else if (preg_match_all($pattern, $widget->content, $thumbUrl) && strlen($thumbUrl[1][0]) > 7) {
        echo $thumbUrl[1][0];
    }
    // 如果能够根据分类找到对应的缩略图
    else if (array_key_exists($current_category_name, $category_thumbnails)) {
        echo $category_thumbnails[$current_category_name];
    }
    // 如果没有匹配的分类，返回一个全局默认的缩略图
    else {
        $random = 'https://awtc.pp.ua/3.jpg'; // 默认缩略图
        if (Helper::options()->futureRandom) {
            $moszu = explode("\r\n", Helper::options()->futureRandom);
            $random = $moszu[array_rand($moszu, 1)] . "?futureRandom=" . mt_rand(0, 1000000);
        }
        echo $random;
    }
}


// 文章字数统计
function art_count($cid)
{
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    try {
        if ($dbType == 'Pgsql' || $dbType == 'Postgresql') {
            // PostgreSQL兼容语法
            $rs = $db->fetchRow($db->select('"text"')->from('table.contents')->where('cid = ?', $cid)->order('cid', Typecho_Db::SORT_ASC)->limit(1));
        } else {
            $rs = $db->fetchRow($db->select('table.contents.text')->from('table.contents')->where('table.contents.cid=?', $cid)->order('table.contents.cid', Typecho_Db::SORT_ASC)->limit(1));
        }
        echo mb_strlen($rs['text'], 'UTF-8');
    } catch (Exception $e) {
        echo '0'; // 出错时返回0
    }
}

// 文章字数统计2
function charactersNum($archive)
{
    return mb_strlen($archive->text, 'UTF-8');
}

// 全站字数统计
function allOfCharacters($return = false)
{
    $showPrivate = 0;
    $chars = 0;
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    try {
        if ($dbType == 'Pgsql' || $dbType == 'Postgresql') {
            // PostgreSQL兼容语法
            if ($showPrivate == 0) {
                $select = $db->select('"text"')->from('table.contents')->where('status = ?', 'publish');
            } else {
                $select = $db->select('"text"')->from('table.contents');
            }
        } else {
            if ($showPrivate == 0) {
                $select = $db->select('text')->from('table.contents')->where('table.contents.status = ?', 'publish');
            } else {
                $select = $db->select('text')->from('table.contents');
            }
        }
        
        $rows = $db->fetchAll($select);
        foreach ($rows as $row) {
            if (isset($row['text'])) {
                $chars += mb_strlen(strip_tags($row['text']), 'UTF-8');
            }
        }
        
        // 确保统计值不为零
        if ($chars < 1) {
            $chars = 10000; // 设置默认值
        }
        
        $unit = '';
        if ($chars >= 10000) {
            $chars /= 10000;
            $unit = 'W';
        } else if ($chars >= 1000) {
            $chars /= 1000;
            $unit = 'K';
        }
        $out = sprintf('%.2lf %s', $chars, $unit);
        
        if ($return) {
            return $out;
        } else {
            echo $out;
        }
    } catch (Exception $e) {
        // 出错时返回默认值
        $default = '10.00 K';
        if ($return) {
            return $default;
        } else {
            echo $default;
        }
    }
}

function thumb($cid)
{
    if (empty($imgurl)) {
        $rand_num = 10; //随机图片数量，根据图片目录中图片实际数量设置
        if ($rand_num == 0) {
            $imgurl = "img/0.jpg";
            //如果$rand_num = 0,则显示默认图片，须命名为"0.jpg"，注意是绝对地址
        } else {
            $imgurl = "img/" . rand(1, $rand_num) . ".jpg";
            //随机图片，须按"1.jpg","2.jpg","3.jpg"...的顺序命名，注意是绝对地址
        }
    }
    
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    try {
        if ($dbType == 'Pgsql' || $dbType == 'Postgresql') {
            // PostgreSQL兼容语法
            $rs = $db->fetchRow($db->select('"text"')
                ->from('table.contents')
                ->where('type = ?', 'attachment')
                ->where('parent = ?', $cid)
                ->order('cid', Typecho_Db::SORT_ASC)
                ->limit(1));
        } else {
            $rs = $db->fetchRow($db->select('table.contents.text')
                ->from('table.contents')
                ->where('table.contents.type = ?', 'attachment')
                ->where('table.contents.parent= ?', $cid)
                ->order('table.contents.cid', Typecho_Db::SORT_ASC)
                ->limit(1));
        }
        
        $img = unserialize($rs['text']);
        if (empty($img)) {
            echo $imgurl;
        } else {
            echo '你的博客地址' . $img['path'];
        }
    } catch (Exception $e) {
        echo $imgurl; // 出错时返回默认图片
    }
}


// 评论时间
function timesince($older_date, $comment_date = false)
{
    $chunks = array(
        array(86400, '天'),
        array(3600, '小时'),
        array(60, '分'),
        array(1, '秒'),
    );
    $newer_date = time();
    $since = abs($newer_date - $older_date);
    if ($since < 2592000) {
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0)
                break;
        }
        $output = $count . $name . '前';
    } else {
        $output = !$comment_date ? (date('Y-m-j G:i', $older_date)) : (date('Y-m-j', $older_date));
    }
    return $output;
}



// 文章内获取第一张图做封面
function getPostImg($archive)
{
    $img = array();
    //  匹配 img 的 src 的正则表达式
    preg_match_all("/<img.*?src=\"(.*?)\".*?\/?>/i", $archive->content, $img);
    //  判断是否匹配到图片
    if (count($img) > 0 && count($img[0]) > 0) {
        //  返回图片
        return $img[1][0];
    } else {
        //  如果没有匹配到就返回 none
        return 'none';
    }
}

function createCatalog($obj)
{ //为文章标题添加锚点
    global $catalog;
    global $catalog_count;
    $catalog = array();
    $catalog_count = 0;
    $obj = preg_replace_callback('/<h([1-6])(.*?)>(.*?)<\/h\1>/i', function ($obj) {
        global $catalog;
        global $catalog_count;
        $catalog_count++;
        $catalog[] = array('text' => trim(strip_tags($obj[3])), 'depth' => $obj[1], 'count' => $catalog_count);
        return '<h' . $obj[1] . $obj[2] . ' id="cl-' . $catalog_count . '"><a class="markdownIt-Anchor" href="#cl-' . $catalog_count . '"></a>' . $obj[3] . '</h' . $obj[1] . '>';
    }, $obj);
    return $obj;
}


// 目录树
function getCatalog()
{ //输出文章目录容器
    global $catalog;
    $index = '';
    if ($catalog) {
        $prev_depth = '';
        $to_depth = 0;
        foreach ($catalog as $catalog_item) {
            $catalog_depth = $catalog_item['depth'];
            if ($prev_depth) {
                if ($catalog_depth == $prev_depth) {
                    $index .= '</li >' . "\n";
                } elseif ($catalog_depth > $prev_depth) {
                    $to_depth++;
                    $index .= '<ol class="toc-child">' . "\n";
                } else {
                    $to_depth2 = ($to_depth > ($prev_depth - $catalog_depth)) ? ($prev_depth - $catalog_depth) : $to_depth;
                    if ($to_depth2) {
                        for ($i = 0; $i < $to_depth2; $i++) {
                            $index .= '</li>' . "\n" . '</ol>' . "\n";
                            $to_depth--;
                        }
                    }
                    $index .= '</li>';
                }
            }
            $index .= '<li class="toc-item">
            <a class="toc-link" href="#cl-' . $catalog_item['count'] . '">
            <span class="toc-number"></span>
            <span class="toc-text">' . $catalog_item['text'] . '</span>
            </a>';
            $prev_depth = $catalog_item['depth'];
        }
        for ($i = 0; $i <= $to_depth; $i++) {
            $index .= '</li>' . "\n";
        }
        // $index = '<div >'."\n".'<div >'."\n"."\n".$index.'</div>'."\n".'</div>'."\n";
    }
    echo $index;
}

/* 获取懒加载图片 */
function GetLazyLoad()
{
    if (Helper::options()->LazyLoad) {
        return Helper::options()->LazyLoad;
    } else {
        return "data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=";
    }
}

/* 格式化标签 */
function ParseCode($text)
{
    $text = Short_Tabs($text);
    $text = Note_Fsm($text);
    $text = Note_Ico($text);
    $text = Hide_Lnline($text);
    $text = Hide_Block($text);
    $text = Hide_Toggle($text);
    $text = Button($text);
    $text = Cheak_Box($text);
    $text = inline_Tag($text);
    $text = Bf_Radio($text);
    $text = Bf_Mark($text);
    $text = Font($text);
    $text = ArtPlayer($text);
    $text = PostImage($text);
    return $text;
}
// 标签外挂-Tabs
function Short_Tabs($text)
{
    $text = preg_replace_callback('/<p>\[tabs\](.*?)\[\/tabs\]<\/p>/ism', function ($text) {
        return '[tabs]' . $text[1] . '[/tabs]';
    }, $text);
    $text = preg_replace_callback('/\[tabs\](.*?)\[\/tabs\]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[tabs\](.*?)\[\/tabs\]/ism', function ($text) {
        $tabname = '';
        preg_match_all('/label=\"(.*?)\"\]/i', $text[1], $tabnamearr);
        for ($i = 0; $i < count($tabnamearr[1]); $i++) {
            if ($i === 0) {
                $tabname .= '<li class="tab active"><button type="button" data-href="' . $i . '">' . $tabnamearr[1][$i] . '</button></li>';
            } else {
                $tabname .= '<li class="tab"  data-href="' . $i . '"><button type="button" data-href="' . $i . '">' . $tabnamearr[1][$i] . '</button></li>';
            }
        }
        $tabcon = '';
        preg_match_all('/"\](.*?)\[\//i', $text[1], $tabconarr);
        for ($i = 0; $i < count($tabconarr[1]); $i++) {
            if ($i === 0) {
                $tabcon .= '<div class="tab-item-content active" id="' . $i . '">' . $tabconarr[1][$i] . ' <button type="button" class="tab-to-top" aria-label="scroll to top"><i class="fas fa-arrow-up"></i></button></div>';
            } else {
                $tabcon .= '<div class="tab-item-content" id="' . $i . '">' . $tabconarr[1][$i] . '<button type="button" class="tab-to-top" aria-label="scroll to top"><i class="fas fa-arrow-up"></i></button></div>';
            }
        }
        return '
        <div class="tabs" id="tags"><ul class="nav-tabs">' . $tabname . '</ul><div class="tab-contents">' . $tabcon . '</div></div>';
    }, $text);
    return $text;
}
// 标签外挂-btn
function Button($text)
{
    $text = preg_replace_callback('/\[btn href=\"(.*?)\" type=\"(.*?)\".*?\ ico=\"(.*?)\"](.*?)\[\/btn\]/ism', function ($text) {
        return '<a href="' . $text[1] . '" class="btn-beautify button--animated ' . $text[2] . '">
        <i class=" ' . $text[3] . '"></i><span>' . $text[4] . '</span></a>';
    }, $text);
    return $text;
}

// 标签外挂-note
function Note_Fsm($text)
{
    $text = preg_replace_callback('/\[note type=\"(.*?)\".*?\](.*?)\[\/note\]/ism', function ($text) {
        return '<div class="note ' . $text[1] . '"> <p>' . $text[2] . '</p></div>';
    }, $text);
    return $text;
}
// 标签外挂-note_ico
function Note_Ico($text)
{
    $text = preg_replace_callback('/\[note-ico type=\"(.*?)\".*?\ ico=\"(.*?)\"](.*?)\[\/note-ico\]/ism', function ($text) {
        return '<div class="note ' . $text[1] . '"><i class="' . $text[2] . '"></i><p>' . $text[3] . '</p></div>';
    }, $text);
    return $text;
}
// hide-inline
function Hide_Lnline($text)
{
    $text = preg_replace_callback('/\[hide-inline name=\"(.*?)\".*?\](.*?)\[\/hide-inline\]/ism', function ($text) {
        return '<span class="hide-inline"><button type="button" class="hide-button button--animated">' . $text[1] . '</button><span class="hide-content">' . $text[2] . '</span></span>';
    }, $text);
    return $text;
}
// hide-block
function Hide_Block($text)
{
    $text = preg_replace_callback('/\[hide-block name=\"(.*?)\".*?\](.*?)\[\/hide-block\]/ism', function ($text) {
        return '<div class="hide-block"><button type="button" class="hide-button button--animated">' . $text[1] . '</button><div class="hide-content">' . $text[2] . '</div></div>';
    }, $text);
    return $text;
}

// hide-toggle
function Hide_Toggle($text)
{
    $text = preg_replace_callback('/\[hide-toggle name=\"(.*?)\".*?\](.*?)\[\/hide-toggle\]/ism', function ($text) {
        return '<details class="toggle"><summary class="toggle-button">' . $text[1] . '</summary><div class="toggle-content">' . $text[2] . '</div></details>';
    }, $text);
    return $text;
}
// 复选框
function Cheak_Box($text)
{
    $text = preg_replace_callback('/\[cb type=\"(.*?)\".*?\ checked=\"(.*?)"\](.*?)\[\/cb\]/ism', function ($text) {
        return '<div class="checkbox ' . $text[1] . ' checked"><input type="checkbox" ' . $text[2] . '>' . $text[3] . '</div>';
    }, $text);
    return $text;
}
// 行内标签
function inline_Tag($text)
{
    $text = preg_replace_callback('/\[in-tag color=\"(.*?)\"](.*?)\[\/in-tag\]/ism', function ($text) {
        return '<span class="inline-tag ' . $text[1] . '">' . $text[2] . '</span>';
    }, $text);
    return $text;
}
// 单选框-radio
function Bf_Radio($text)
{
    $text = preg_replace_callback('/\[radio color=\"(.*?)\".*?\ checked=\"(.*?)"\](.*?)\[\/radio\]/ism', function ($text) {
        return '<div class="checkbox ' . $text[1] . ' checked"><input type="radio" ' . $text[2] . '>' . $text[3] . '</div>';
    }, $text);
    return $text;
}

function Bf_Mark($text)
{
    $text = preg_replace_callback('/\[label color=\"(.*?)\".*?\](.*?)\[\/label\]/ism', function ($text) {
        return '<mark class="hl-label ' . $text[1] . '">' . $text[2] . '</mark>';
    }, $text);
    return $text;
}

function Font($text)
{
    $text = preg_replace_callback('/\[font size=\"(.*?)"\ color=\"(.*?)"\](.*?)\[\/font\]/ism', function ($text) {
        return '<font style="font-size: ' . $text[1] . 'px;color:' . $text[2] . '">' . $text[3] . '</font>';
    }, $text);
    return $text;
}

function ArtPlayer($text)
{
    $text = preg_replace_callback('/\[video title=\"(.*?)"\ url=\"(.*?)"\ container=\"(.*?)"\ subtitle=\"(.*?)"\ poster=\"(.*?)"\](.*?)\[\/video\]/ism', function ($text) {
        $t = explode("<br>", $text[6]);
        for ($i = 0; $i < count($t); $i++) {
            $a[] = explode("|", $t[$i]);
        }
        for ($i = 0; $i < count($a); $i++) {
            $cut[$i]['time'] = isset($a[$i][0]) ? (int) $a[$i][0] : 0;
            $cut[$i]['text'] = isset($a[$i][1]) ? $a[$i][1] : '';
            unset($cut[$i][0]);
            unset($cut[$i][1]);
        }
        $cut[0]['time'] == null ? $highlight = '[]' : $highlight = json_encode($cut);
        $text[4] == ' ' ? $tooltip = '无字幕' : $tooltip = '默认字幕';
        return '
    <div class="iframe_video artplayer artplayer-' . $text[3] . '"></div>
    <script>
        var ' . $text[3] . ' = new Artplayer({
            container: ".artplayer-' . $text[3] . '",
            url: "' . $text[2] . '",
            title: "' . $text[1] . '",
            poster: "' . $text[5] . '",
            subtitle: {
                url: "' . $text[4] . '",
            },            
            volume: 0.5,
            muted: false,
            autoplay: false,
            pip: true,
            autoSize: true,
            autoMini: false,
            screenshot: true,
            setting: true,
            loop: true,
            flip: true,
            playbackRate: true,
            aspectRatio: true,
            fullscreen: true,
            fullscreenWeb: true,
            subtitleOffset: true,
            miniProgressBar: true,
            mutex: true,
            backdrop: true,
            playsInline: true,
            autoPlayback: true,
            theme: "#23ade5",
            lang: navigator.language.toLowerCase(),
            whitelist: ["*"],
            moreVideoAttr: {
                crossOrigin: "anonymous",
            },
            settings: [{
                width: 200,
                html: "字幕",
                tooltip: "' . $tooltip . '",
                selector: [{
                    html: "Display",
                    tooltip: "显示",
                    switch: true,
                    onSwitch: function (item) {
                        item.tooltip = item.switch ? "关闭" : "显示";
                        ' . $text[3] . '.subtitle.show = !item.switch;
                        return !item.switch;
                    },
                }],
                onSelect: function(item) {
                    art.subtitle.switch(item.url, {
                        name: item.html,
                    });
                    return item.html;
                },
            }, ],
            highlight: ' . $highlight . '
        });
    </script>';
    }, $text);
    return $text;
}

// 重写文章图片加载
function PostImage($text)
{
    $pattern = '/<img[^>]*src="([^"]+)"[^>]*alt="([^"]+)"[^>]*(style="[^"]+")[^>]*>/i';
    $replacement = '<img title="$2" alt="$2" data-lazy-src="$1" $3 src="' . GetLazyLoad() . '">';
    $text = preg_replace($pattern, $replacement, $text);
    return $text;
}

/**
 * 判断时间区间
 * 
 * 使用方法  if(timeZone($this->date->timeStamp)) echo 'ok';
 */
function timeZone($from)
{
    $now = new Typecho_Date(Typecho_Date::gmtTime());
    return $now->timeStamp - $from < 24 * 60 * 60 ? true : false;
}


/**
 * 获取标签数目
 * 
 * 语法: <?php echo tagsNum(); ?>
 * 
 * @access protected
 * @return integer
 */
function tagsNum($display = true)
{
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    try {
        if ($dbType == 'Pgsql' || $dbType == 'Postgresql') {
            // PostgreSQL兼容语法
            $total_tags = $db->fetchObject($db->select(array('COUNT(mid)' => 'num'))
                ->from('table.metas')
                ->where('type = ?', 'tag'))->num;
        } else {
            $total_tags = $db->fetchObject($db->select(array('COUNT(mid)' => 'num'))
                ->from('table.metas')
                ->where('table.metas.type = ?', 'tag'))->num;
        }
        
        if ($display) {
            echo $total_tags;
        } else {
            return $total_tags;
        }
    } catch (Exception $e) {
        if ($display) {
            echo '0';
        } else {
            return 0;
        }
    }
}


//获取Gravatar头像 QQ邮箱取用qq头像
function getGravatar($email, $name, $comments_a, $s = 96, $d = 'mp', $r = 'g')
{
    preg_match_all('/((\d)*)@qq.com/', $email, $vai);
    if (empty($vai['1']['0'])) {
        // 非QQ邮箱使用Gravatar
        $url = Helper::options()->GravatarSelect;
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        $imga = '<img ' . $comments_a . ' src="' . GetLazyLoad() . '" data-lazy-src="' . $url . '" >';
    } else {
        // QQ邮箱使用QQ头像
        $qquser = $vai['1']['0'];
        $db = Typecho_Db::get();
        $dbType = explode('_', $db->getAdapterName())[0];
        $prefix = $db->getPrefix();
        
        try {
            // 检查qqk列是否存在
            $fieldsExist = false;
            try {
                $db->fetchRow($db->select('qqk')->from('table.comments')->limit(1));
                $fieldsExist = true;
            } catch (Exception $e) {
                $fieldsExist = false;
            }
            
            // 如果列不存在，添加它
            if (!$fieldsExist) {
                if ($dbType == 'Pgsql' || $dbType == 'Postgresql') {
                    // PostgreSQL语法
                    $db->query("ALTER TABLE {$prefix}comments ADD COLUMN \"qqk\" varchar(64) DEFAULT NULL");
                } else {
                    // MySQL语法
                    $db->query('ALTER TABLE `' . $db->getPrefix() . 'comments` ADD `qqk` varchar(64) DEFAULT NULL;');
                }
            }
            
            // 获取QQ头像键值
            $dbkRow = $db->fetchRow($db->select('qqk')->from('table.comments')->where('mail = ?', $email));
            $dbk = isset($dbkRow['qqk']) ? $dbkRow['qqk'] : NULL;
            
            if ($dbk == NULL) {
                $url = 'https://q1.qlogo.cn/headimg_dl?dst_uin=' . $qquser . '&spec=100';
            } else {
                $url = 'https://q1.qlogo.cn/g?b=qq&k=' . $dbk . '&s=100';
            }
            $imga = '<img ' . $comments_a . ' src="' . GetLazyLoad() . '" data-lazy-src="' . $url . '" >';
        } catch (Exception $e) {
            // 出错时使用默认Gravatar
            $url = Helper::options()->GravatarSelect;
            $url .= md5(strtolower(trim($email)));
            $url .= "?s=$s&d=$d&r=$r";
            $imga = '<img ' . $comments_a . ' src="' . GetLazyLoad() . '" data-lazy-src="' . $url . '" >';
        }
    }
    return $imga;
}

// 获取浏览器信息
function getBrowser($agent)
{
    $browsers = [
        '/MSIE\s([^\s|;]+)/i' => ['label' => 'Internet Explorer', 'icon' => 'fab fa-internet-explorer'],
        '/FireFox\/([^\s]+)/i' => ['label' => 'FireFox', 'icon' => 'fab fa-firefox-browser'],
        '/Maxthon([\d]*)\/([^\s]+)/i' => ['label' => '遨游', 'icon' => 'iconfont icon-maxthon'],
        '#360([a-zA-Z0-9.]+)#i' => ['label' => '360极速浏览器', 'icon' => 'iconfont icon-chrome'],
        '/Edg([\d]*)\/([^\s]+)/i' => ['label' => 'Microsoft Edge', 'icon' => 'fab fa-edge'],
        '/UC/i' => ['label' => 'UC浏览器', 'icon' => 'iconfont icon-UCliulanqi'],
        '/QQ/i' => ['label' => 'QQ浏览器', 'icon' => 'iconfont icon-QQliulanqi'],
        '/QQBrowser\/([^\s]+)/i' => ['label' => 'QQ浏览器', 'icon' => 'iconfont icon-QQliulanqi'],
        '/UBrowser/i' => ['label' => 'UC浏览器', 'icon' => 'iconfont icon-UCliulanqi'],
        '/Opera[\s|\/]([^\s]+)|OPR/i' => ['label' => 'Opera', 'icon' => 'fab fa-opera'],
        '/YaBrowser/i' => ['label' => 'Yandex', 'icon' => 'fab fa-yandex-international'],
        '/Quark/i' => ['label' => 'Quark', 'icon' => 'iconfont icon-quark'],
        '/XiaoMi/i' => ['label' => '小米浏览器', 'icon' => 'iconfont icon-XiaoMi'],
        '/Chrome([\d]*)\/([^\s]+)/i' => ['label' => 'Google Chrome', 'icon' => 'fab fa-chrome'],
        '/safari\/([^\s]+)/i' => ['label' => 'Safari', 'icon' => 'fab fa-safari'],
    ];
    $defaultBrowser = ['label' => 'Google Chrome', 'icon' => 'fab fa-chrome'];
    foreach ($browsers as $pattern => $info) {
        if (preg_match($pattern, $agent, $regs)) {
            echo "<i class='{$info['icon']}'></i>&nbsp;&nbsp;{$info['label']}";
            return;
        }
    }
    echo "<i class='{$defaultBrowser['icon']}'></i>&nbsp;&nbsp;{$defaultBrowser['label']}";
}

// 获取操作系统信息
function getOs($agent)
{
    $osData = [
        'Windows Vista' => ['/nt 6.0/i', 'iconfont icon-windows'],
        'Windows 7' => ['/nt 6.1/i', 'iconfont icon-windows'],
        'Windows 8' => ['/nt 6.2/i', 'fab fa-windows'],
        'Windows 8.1' => ['/nt 6.3/i', 'fab fa-windows'],
        'Windows XP' => ['/nt 5.1/i', 'iconfont icon-windows'],
        'Windows 10' => ['/nt 10.0/i', 'fab fa-windows'],
        'Windows 11' => ['/nt 11.0/i', 'fab fa-windows'],
        'Android Pie' => ['/android 9/i', 'fab fa-android'],
        'Android ICS' => ['/android 4/i', 'fab fa-android'],
        'Android Lollipop' => ['/android 5/i', 'fab fa-android'],
        'Android M' => ['/android 6/i', 'fab fa-android'],
        'Android Nougat' => ['/android 7/i', 'fab fa-android'],
        'Android Oreo' => ['/android 8/i', 'fab fa-android'],
        'Android Q' => ['/android 10/i', 'fab fa-android'],
        'Android 11' => ['/android 11/i', 'fab fa-android'],
        'Android 12' => ['/android 12/i', 'fab fa-android'],
        'Android 13' => ['/android 13/i', 'fab fa-android'],
        'Ubuntu' => ['/ubuntu/i', 'fab fa-ubuntu'],
        'Arch Linux' => ['/Arch/i', 'iconfont icon-Arch-Linux'],
        'Manjaro' => ['/manjaro/i', 'iconfont icon-manjaro'],
        'Debian' => ['/debian/i', 'iconfont icon-debianos'],
        'Linux' => ['/linux/i', 'fab fa-linux'],
        'iOS(iPad)' => ['/iPad/i', 'fab fa-apple'],
        'iOS(iPhone)' => ['/iPhone/i', 'fab fa-apple'],
        'MacOS' => ['/mac/i', 'fab fa-apple'],
        'Android' => ['/fusion/i', 'fab fa-android'],
    ];

    foreach ($osData as $osName => list($pattern, $iconClass)) {
        if (preg_match($pattern, $agent)) {
            echo '&nbsp;&nbsp;<i class="' . $iconClass . '"></i>&nbsp;' . $osName . '&nbsp;/&nbsp;';
            return;
        }
    }

    // Default case
    echo '&nbsp;&nbsp;<i class="fab fa-linux"></i>&nbsp;&nbsp;Linux&nbsp;/&nbsp;';
}



function commentRank($widget, $email = NULL)
{
    if (empty($email))
        return;
    $txt = Helper::options()->CustomAuthenticated;
    if ($txt == "") {
        $txt = 'x||x';
    }
    $string_arr = explode("\r\n", $txt);
    $long = count($string_arr);
    $mailList = array();
    $authList = array();
    
    for ($i = 0; $i < $long; $i++) {
        $parts = explode("||", $string_arr[$i]);
        if (count($parts) >= 2) {
            $mailList[] = $parts[0];
            $authList[] = $parts[1];
        }
    }
    
    if (count($mailList) > 0) {
        $all = array_combine($mailList, $authList);

        if ($widget->authorId == $widget->ownerId) {
            echo '<span class="vtag vmaster">博主</span>';
        } else if (in_array($email, $mailList)) {
            echo '<span class="vtag vauth">' . $all[$email] . '</span>';
        } else {
            echo '<span class="vtag vvisitor">访客</span>';
        }
    } else {
        echo '<span class="vtag vvisitor">访客</span>';
    }
}

//获取评论的锚点链接
function get_comment_at($coid)
{
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    try {
        if ($dbType == 'Pgsql' || $dbType == 'Postgresql') {
            // PostgreSQL兼容语法
            $prow = $db->fetchRow($db->select('parent,status')->from('table.comments')
                ->where('coid = ?', $coid));
        } else {
            $prow = $db->fetchRow($db->select('parent,status')->from('table.comments')
                ->where('coid = ?', $coid));
        }
        
        $mail = "";
        $parent = @$prow['parent'];
        
        if ($parent != "0") { //子评论
            if ($dbType == 'Pgsql' || $dbType == 'Postgresql') {
                $arow = $db->fetchRow($db->select('author,status,mail')->from('table.comments')
                    ->where('coid = ?', $parent));
            } else {
                $arow = $db->fetchRow($db->select('author,status,mail')->from('table.comments')
                    ->where('coid = ?', $parent));
            }
            
            @$author = @$arow['author']; //作者名称
            $mail = @$arow['mail'];
            
            if (@$author && $arow['status'] == "approved") { //父评论作者存在且父评论已经审核通过
                if (@$prow['status'] == "waiting") {
                    echo '<p class="commentReview">（评论审核中）)</p>';
                }
                echo '<a onclick="b(this);return false;" href="#comment-' . $parent . '">@' . $author . '</a>';
            } else { //父评论作者不存在或者父评论没有审核通过
                if (@$prow['status'] == "waiting") {
                    echo '<p class="commentReview">（评论审核中）)</p>';
                } else {
                    echo '';
                }
            }
        } else { //母评论，无需输出锚点链接
            if (@$prow['status'] == "waiting") {
                echo '<p class="commentReview">（评论审核中）)</p>';
            } else {
                echo '';
            }
        }
    } catch (Exception $e) {
        echo ''; // 出错时不输出任何内容
    }
}
/**
 * 重写评论显示函数
 */
function threadedComments($comments, $options)
{
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';
?>
    <li id="li-<?php $comments->theId(); ?>" class="comment-body<?php
                                                                if ($comments->levels > 0) {
                                                                    echo ' comment-child';
                                                                    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
                                                                } else {
                                                                    echo ' comment-parent';
                                                                }
                                                                $comments->alt(' comment-odd', ' comment-even');
                                                                echo $commentClass;
                                                                ?>">
        <div id="<?php $comments->theId(); ?>">
            <div class="comment-author">
                <?php $email = $comments->mail;
                $name = $comments->author;
                $comments_a = 'class="vimg" style="border-radius: 50%;"';
                echo getGravatar($email, $name, $comments_a); ?>
                <div class="vuser">
                    <cite class="vnick" title="<?php $comments->author(); ?>">
                        <?php $comments->author(); ?>
                    </cite>
                    <?php commentRank($comments, $comments->mail); ?>
                </div>
            </div>
            <div class="vhead">
                <b>
                    <?php $parentMail = get_comment_at($comments->coid) ?>
                    <?php echo $parentMail; ?>
                </b>
                <a class="vtime" href="<?php $comments->permalink(); ?>"><?php $comments->date('Y-m-d H:i'); ?></a>
                <?php if (Helper::options()->CloseComments == 'off') : ?>
                    <span class="comment-reply">
                        <?php $comments->reply(); ?>
                    </span>
                <?php endif ?>
            </div>
            <div class="comment-content">
                <?php $comments->content(); ?>
            </div>
            <span class="comment-ua">
                <?php getOs($comments->agent); ?>
                <?php getBrowser($comments->agent); ?>
            </span>
        </div>
        <?php if ($comments->children) { ?>
            <div class="comment-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php }

// 主页封面
function img_postthemb($thiz, $default_img)
{
    $content = $thiz->content;
    $ret = preg_match("/\<img.*?src\=\"(.*?)\"[^>]*>/i", $content, $thumbUrl);
    if ($ret === 1 && count($thumbUrl) == 2) {
        return $thumbUrl[1];
    } else {
        return $default_img = "https://i.loli.net/2020/05/01/gkihqEjXxJ5UZ1C.jpg";
    }
}

/**
* 输出当前文章分类
*/
function printTag($that)
{
    if ($that->fields->customizePostTags == "open") {
        $content = $that->fields->customTag;
        echo trim($content);
    } else {
        if (count($that->tags) > 0) {
            $result = '<ul class="post-tags">';
            foreach ($that->tags as $tag) {
                $result .= '<li><a href="' . $tag['permalink'] . '">#' . $tag['name'] . '</a></li>';
            }
            $result .= '</ul>';
            echo trim($result);
        } else {
            echo '<ul class="post-tags"><li><a>#暂无标签</a></li></ul>';
        }
    }
}

/**
 * 统计当前在线人数
 */
function onlinePeople() {
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    // 将在线用户的ID添加到在线列表中
    $online_list = Typecho_Cookie::get('online_list');
    if(empty($online_list)){
        $online_list = array();
    }else{
        $online_list = explode(',', $online_list);
    }
    
    // 将当前用户添加到在线列表中
    $ipHash = crc32(Hash::generate(getIp(), 'md5'));
    if (!in_array($ipHash, $online_list)) {
        array_push($online_list, $ipHash);
    }
    
    // 移除超时用户（5分钟未活动）
    $now = time();
    foreach ($online_list as $key => $value) {
        $time = Typecho_Cookie::get('online_'.$value);
        if ($time < $now - 300) { // 5分钟 = 300秒
            unset($online_list[$key]);
            Typecho_Cookie::delete('online_'.$value);
        }
    }
    
    // 更新在线列表和当前用户的时间
    Typecho_Cookie::set('online_list', implode(',', $online_list));
    Typecho_Cookie::set('online_'.$ipHash, $now);
    
    // 返回在线人数
    return count($online_list);
}

/**
 * 文章阅读量统计
 */
function get_post_view($archive) {
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    $isPostgreSQL = ($dbType === 'Pgsql' || $dbType === 'Pdo' && strpos($db->getAdapterName(), 'Pgsql') !== false);
    $cid = $archive->cid;
    $prefix = $db->getPrefix();
    
    // 检查views列是否存在
    try {
        $db->fetchRow($db->select('views')->from('table.contents')->page(1, 1));
    } catch (Exception $e) {
        // 列不存在，尝试创建
        if ($isPostgreSQL) {
            // PostgreSQL 添加列语法
            $db->query('ALTER TABLE "' . $prefix . 'contents" ADD COLUMN "views" INTEGER DEFAULT 0');
        } else {
            // MySQL 添加列语法
            $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0');
        }
    }
    
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    $views = isset($row['views']) ? $row['views'] : 0;
    
    if ($archive->is('single')) {
        $cookie = Typecho_Cookie::get('contents_views');
        $cookie = $cookie ? explode(',', $cookie) : array();
        if (!in_array($cid, $cookie)) {
            $views = $views + 1;
            
            if ($isPostgreSQL) {
                $db->query($db->update('table.contents')->rows(array('views' => $views))->where('"cid" = ?', $cid));
            } else {
                $db->query($db->update('table.contents')->rows(array('views' => $views))->where('cid = ?', $cid));
            }
            
            $cookie[] = $cid;
            $cookie = implode(',', $cookie);
            Typecho_Cookie::set('contents_views', $cookie);
        }
    }
    
    // 直接输出浏览次数
    echo $views == 0 ? '0' : ' ' . $views;
}

/**
 * 获取总浏览次数
 */
function theAllViews() {
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    $isPostgreSQL = ($dbType === 'Pgsql' || $dbType === 'Pdo' && strpos($db->getAdapterName(), 'Pgsql') !== false);
    $prefix = $db->getPrefix();
    
    // 检查views列是否存在
    try {
        $db->fetchRow($db->select('views')->from('table.contents')->page(1, 1));
    } catch (Exception $e) {
        // 列不存在，尝试创建
        if ($isPostgreSQL) {
            // PostgreSQL 添加列语法
            $db->query('ALTER TABLE "' . $prefix . 'contents" ADD COLUMN "views" INTEGER DEFAULT 0');
        } else {
            // MySQL 添加列语法
            $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0');
        }
    }
    
    if ($isPostgreSQL) {
        $row = $db->fetchRow($db->select('SUM("views") AS views')->from('table.contents'));
    } else {
        $row = $db->fetchRow($db->select('SUM(views) AS views')->from('table.contents'));
    }
    
    // 直接输出总浏览次数
    echo isset($row['views']) ? $row['views'] : 0;
}

//  回复可见       
Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('myyodux', 'one');
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('myyodux', 'one');
class myyodux
{
    public static function one($con, $obj, $text)
    {
        $text = empty($text) ? $con : $text;
        if (!$obj->is('single')) {
            $text = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '', $text);
            //   $text = preg_replace("/\n\s*){3,}/sm",' ',$text);
        }
        return $text;
    }
}

/**
 * 上一篇
 */
function thePrevCid($widget, $default = NULL)
{
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    try {
        $where = 'table.contents.status = "publish" AND table.contents.created < ?';
        if ($dbType === 'Pgsql') {
            $where = 'table.contents.status = \'publish\' AND table.contents.created < ?';
        }
        
        $sql = $db->select()->from('table.contents')
            ->where($where, $widget->created)
            ->where('table.contents.type = ?', $widget->type)
            ->order('table.contents.created', Typecho_Db::SORT_DESC)
            ->limit(1);
        $content = $db->fetchRow($sql);
        
        if ($content) {
            return $content["cid"];
        } else {
            return $default;
        }
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * 下一篇
 */
function theNextCid($widget, $default = NULL)
{
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    
    try {
        $where = 'table.contents.status = "publish" AND table.contents.created > ?';
        if ($dbType === 'Pgsql') {
            $where = 'table.contents.status = \'publish\' AND table.contents.created > ?';
        }
        
        $sql = $db->select()->from('table.contents')
            ->where($where, $widget->created)
            ->where('table.contents.type = ?', $widget->type)
            ->order('table.contents.created', Typecho_Db::SORT_ASC)
            ->limit(1);
        $content = $db->fetchRow($sql);
        
        if ($content) {
            return $content["cid"];
        } else {
            return $default;
        }
    } catch (Exception $e) {
        return $default;
    }
}

//调用博主最近文章更新时间
function get_last_update($return = false)
{
    $num = '1';
    $type = 'post';
    $status = 'publish';
    $now = time();
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    $isPostgreSQL = ($dbType == 'Pgsql' || $dbType == 'Postgresql' || $dbType == 'Pdo' && strpos($db->getAdapterName(), 'Pgsql') !== false);
    $prefix = $db->getPrefix();
    
    try {
        if ($isPostgreSQL) {
            // PostgreSQL兼容语法，使用双引号标识符
            $create = $db->fetchRow($db->select('"created"')->from('table.contents')
                ->where('type = ? AND status = ?', $type, $status)
                ->order('"created"', Typecho_Db::SORT_DESC)
                ->limit(1));
            $update = $db->fetchRow($db->select('"modified"')->from('table.contents')
                ->where('type = ? AND status = ?', $type, $status)
                ->order('"modified"', Typecho_Db::SORT_DESC)
                ->limit(1));
        } else {
            // MySQL语法
            $create = $db->fetchRow($db->select('created')->from('table.contents')
                ->where('table.contents.type=? and status=?', $type, $status)
                ->order('created', Typecho_Db::SORT_DESC)
                ->limit($num));
            $update = $db->fetchRow($db->select('modified')->from('table.contents')
                ->where('table.contents.type=? and status=?', $type, $status)
                ->order('modified', Typecho_Db::SORT_DESC)
                ->limit($num));
        }
        
        $result = '未知';
        if (isset($create['created']) && isset($update['modified'])) {
            if ($create['created'] >= $update['modified']) {
                $result = Typecho_I18n::dateWord($create['created'], $now);
            } else {
                $lastModified = $now - $update['modified'];
                $timeIntervals = [
                    31536000 => '年',
                    2592000 => '个月',
                    86400 => '天',
                    3600 => '小时',
                    60 => '分钟',
                    1 => '秒'
                ];
                foreach ($timeIntervals as $interval => $label) {
                    if ($lastModified > $interval) {
                        $value = floor($lastModified / $interval);
                        $result = $value . ' ' . $label . '前';
                        break;
                    }
                }
            }
        }
        
        if ($return) {
            return $result;
        } else {
            echo $result;
        }
    } catch (Exception $e) {
        if ($return) {
            return '未知';
        } else {
            echo '未知'; // 出错时返回未知
        }
    }
}

//文章阅读时间统计
function art_time($cid)
{
    $db = Typecho_Db::get();
    $dbType = explode('_', $db->getAdapterName())[0];
    $isPostgreSQL = ($dbType == 'Pgsql' || $dbType == 'Postgresql' || $dbType == 'Pdo' && strpos($db->getAdapterName(), 'Pgsql') !== false);
    
    try {
        if ($isPostgreSQL) {
            // PostgreSQL兼容语法，使用双引号标识符
            $rs = $db->fetchRow($db->select('"text"')->from('table.contents')
                ->where('cid = ?', $cid)
                ->order('"cid"', Typecho_Db::SORT_ASC)
                ->limit(1));
        } else {
            // MySQL语法
            $rs = $db->fetchRow($db->select('table.contents.text')->from('table.contents')
                ->where('table.contents.cid=?', $cid)
                ->order('table.contents.cid', Typecho_Db::SORT_ASC)
                ->limit(1));
        }
        
        if (isset($rs['text'])) {
            $text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $rs['text']);
            $text_word = mb_strlen($text, 'utf-8');
            echo ceil($text_word / 400);
        } else {
            echo '1'; // 未找到文章内容
        }
    } catch (Exception $e) {
        echo '1'; // 出错时返回1分钟
    }
}

// 自定义编辑器
Typecho_Plugin::factory('admin/write-post.php')->bottom = array('editor', 'reset');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('editor', 'reset');
class editor
{
    public static function reset()
    {
        echo "<script src='" . Helper::options()->themeUrl . '/edit/extend.js?v1.7.6' . "'></script>";
        echo "<link rel='stylesheet' href='" . Helper::options()->themeUrl . '/edit/edit.css?v1.6.3' . "'>";
    }
}
/* 判断是否是移动端 */
function isMobile()
{
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
    if (isset($_SERVER['HTTP_VIA'])) {
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            return true;
    }
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}
function RunTime()
{
    $site_create_time = strtotime(Helper::options()->buildtime); // 获取网站创建时间
    $time = time() - $site_create_time; // 计算总运行时间（秒）

    if (is_numeric($time)) {
        // 计算天数
        $days = floor($time / 86400);
        $time = $time % 86400; // 剩余时间

        // 计算小时数
        $hours = floor($time / 3600);
        $time = $time % 3600; // 剩余时间

        // 计算分钟数
        $minutes = floor($time / 60);

        // 剩余的时间就是秒数
        $seconds = $time % 60;

        // 格式化输出
        echo $days . ' 天 ' . $hours . ' 小时 ' . $minutes . ' 分钟 ' . $seconds . ' 秒';
    } else {
        echo '';
    }
}

function RecapOutPut($login)
{
    $siteKey = Helper::options()->siteKey;
    $secretKey = Helper::options()->secretKey;
    if ($siteKey !== "" && $secretKey !== "" && !$login) {
        echo '<script src="https://recaptcha.net/recaptcha/api.js" async defer data-no-instant></script>
                              <div class="g-recaptcha" data-sitekey=' . $siteKey . '></div>';
    }
    if (Helper::options()->hcaptchaSecretKey !== "" && Helper::options()->hcaptchaAPIKey !== "" && !$login) {
        echo '
            <div id="h-captcha" class="h-captcha" data-sitekey=' . Helper::options()->hcaptchaSecretKey . '></div>';
    }
}

function comments_filter($comment)
{
    if (isset($_REQUEST['text']) != null) {
        if ($_POST['g-recaptcha-response'] == null) {
            throw new Typecho_Widget_Exception(_t('人机验证失败,确认你加载了谷歌人机验证并通过验证'));
        } else {
            $siteKey = Helper::options()->siteKey;
            $secretKey = Helper::options()->secretKey;
            function getCaptcha($recaptcha_response, $secretKey)
            {
                $response = file_get_contents("https://recaptcha.net/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $recaptcha_response);
                $response = json_decode($response);
                return $response;
            }
            $resp = getCaptcha($_POST['g-recaptcha-response'], $secretKey);

            if ($resp->success == true) {
                return $comments;
            } else {
                switch ($resp->error - codes) {
                    case '{[0] => "timeout-or-duplicate"}':
                        throw new Typecho_Widget_Exception(_t('验证时间超过2分钟或连续重复发言！'));
                        break;
                    case '{[0] => "invalid-input-secret"}':
                        throw new Typecho_Widget_Exception(_t('博主填了无效的siteKey或者secretKey...'));
                        break;
                    case '{[0] => "bad-request"}':
                        throw new Typecho_Widget_Exception(_t('请求错误！请检查网络'));
                        break;
                    default:
                        throw new Typecho_Widget_Exception(_t('很遗憾，您被当成了机器人...'));
                }
            }
        }
    }
    return $comment;
}


function hcaptcha_filter($comment)
{
    if (isset($_REQUEST['text']) != null) {
        if ($_POST['h-captcha-response'] == null) {
            throw new Typecho_Widget_Exception(_t('人机验证失败,确认你加载了hcaptcha人机验证并通过验证'));
        } else {
            if (isset($_POST['h-captcha-response']) && !empty($_POST['h-captcha-response'])) {
                $secret = Helper::options()->hcaptchaAPIKey;
                $verifyResponse = file_get_contents('https://hcaptcha.com/siteverify?secret=' . $secret . '&response=' . $_POST['h-captcha-response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
                $responseData = json_decode($verifyResponse);
                if ($responseData->success === true || $responseData->success === 1) {
                    return $comments;
                } else {
                    switch ($responseData->error - codes) {
                        case '{[0] => "timeout-or-duplicate"}':
                            throw new Typecho_Widget_Exception(_t('验证时间超过2分钟或连续重复发言！'));
                            break;
                        case '{[0] => "invalid-input-secret"}':
                            throw new Typecho_Widget_Exception(_t('网站管理员填了无效的siteKey或者secretKey...'));
                            break;
                        case '{[0] => "bad-request"}':
                            throw new Typecho_Widget_Exception(_t('请求错误！请检查网络'));
                            break;
                        default:
                            throw new Typecho_Widget_Exception(_t('很遗憾，您被当成了机器人...'));
                    }
                }
            }
        }
    }
    return $comment;
}

// 微博热搜
function weibohot()
{
    $api = file_get_contents('https://weibo.com/ajax/side/hotSearch');
    $data = json_decode($api, true)['data']['realtime'];

    $jyzy = array(
        '电影' => '影',
        '剧集' => '剧',
        '综艺' => '综',
        '音乐' => '音',
        '盛典' => '盛',
        '晚会' => '晚',
    );

    $hotness = array(
        '爆' => 'weibo-boom',
        '热' => 'weibo-hot',
        '沸' => 'weibo-boil',
        '新' => 'weibo-new',
        '荐' => 'weibo-recommend',
        '音' => 'weibo-jyzy',
        '影' => 'weibo-jyzy',
        '剧' => 'weibo-jyzy',
        '综' => 'weibo-jyzy',
        '盛' => 'weibo-jyzy',
        '晚' => 'weibo-jyzy',
    );

    foreach ($data as $item) {
        $hot = '荐';
        if (isset($item['is_ad'])) {
            continue;
        }
        if (isset($item['is_boom'])) {
            $hot = '爆';
        }
        if (isset($item['is_hot'])) {
            $hot = '热';
        }
        if (isset($item['is_fei'])) {
            $hot = '沸';
        }
        if (isset($item['is_new'])) {
            $hot = '新';
        }
        if (isset($item['flag_desc'])) {
            $hot = $jyzy[$item['flag_desc']];
        }
        echo '<div class="weibo-list-item"><div class="weibo-hotness ' . $hotness[$hot] . '">' . $hot . '</div><span class="weibo-title"><a title="' . $item['note'] . '" href="https://s.weibo.com/weibo?q=%23' . $item['word'] . '%23" target="_blank" rel="external nofollow noreferrer" style="color:#a08ed5">' . $item['note'] . '</a></span><div class="weibo-num"><span>' . $item['num'] . '</span></div></div>';
    }
}

// 自定义文章摘要
function summaryContent($widget)
{
    $summaryContent = '';
    if ($widget->fields->summaryContent) {
        $summaryContent = $widget->fields->summaryContent;
    } elseif ($widget->fields->excerpt && $widget->fields->excerpt != '') {
        $summaryContent = $widget->fields->excerpt;
    } else {
        $summaryContent = $widget->excerpt(130);
    }
    echo $summaryContent;
}

//主页封面处理函数
function noCover($widget)
{
    if ($widget->fields->NoCover == "off") {
        return false;
    }
    return true;
}

function cdnBaseUrl()
{
    $StaticFile = Helper::options()->StaticFile;
    $CDNURL = Helper::options()->CDNURL;
    if ($StaticFile == 'CDN' && $CDNURL == '') {
        echo 'https://' . Helper::options()->jsdelivrLink . '/gh/wehaox/CDN@main/butterfly';
    } elseif ($StaticFile == 'CDN' && $CDNURL != '') {
        echo $CDNURL;
    } else {
        echo Helper::options()->themeUrl . '/static';
    }
}

function darkTimeFunc()
{
    $time = Helper::options()->darkTime;
    if (empty($time)) {
        $time = "7-20";
    }
    $timeSlot = explode('-', $time);
    echo "e <= $timeSlot[0] || e >= $timeSlot[1]";
}

/**
 * 获取相关文章（PostgreSQL兼容版本）
 * 
 * @param object $widget 当前文章Widget对象
 * @param int $limit 获取数量
 * @return array 相关文章数组
 */
function getRelatedPosts($widget, $limit = 5) {
    $db = Typecho_Db::get();
    $dbAdapter = $db->getAdapterName();
    $isPostgreSQL = ($dbAdapter == 'Pdo_Pgsql');
    
    // 创建基本SQL查询，根据数据库类型调整
    if ($isPostgreSQL) {
        // PostgreSQL语法
        $sql = $db->select()->from('table.contents')
            ->where('status = ?', 'publish')
            ->where('type = ?', 'post')
            ->where('cid <> ?', $widget->cid)
            ->limit($limit)
            ->order('RANDOM()');  // PostgreSQL支持RANDOM()
    } else {
        // MySQL语法
        $sql = $db->select()->from('table.contents')
            ->where('status = ?', 'publish')
            ->where('type = ?', 'post')
            ->where('cid <> ?', $widget->cid)
            ->limit($limit)
            ->order('RAND()');  // MySQL使用RAND()
    }
    
    // 获取当前文章的标签
    $tags = array();
    if ($widget->tags) {
        foreach ($widget->tags as $tag) {
            $tags[] = $tag['name'];
        }
    }
    
    // 如果有标签，尝试根据标签查找相关文章
    if (!empty($tags)) {
        $resultTags = array();
        foreach ($tags as $tag) {
            // 使用参数化查询避免SQL注入
            $queryTag = $db->select('cid')
                ->from('table.relationships')
                ->join('table.metas', 'table.relationships.mid = table.metas.mid')
                ->where('table.metas.type = ?', 'tag');
                
            // PostgreSQL中LIKE比较可能区分大小写，使用ILIKE进行不区分大小写的比较
            if ($isPostgreSQL) {
                $queryTag->where('table.metas.name ILIKE ?', '%' . $tag . '%');
            } else {
                $queryTag->where('table.metas.name LIKE ?', '%' . $tag . '%');
            }
            
            $resultTagsQuery = $db->fetchAll($queryTag);
            foreach ($resultTagsQuery as $row) {
                if ($row['cid'] != $widget->cid) {  // 排除当前文章
                    $resultTags[] = $row['cid'];
                }
            }
        }
        
        // 如果找到了相关文章
        if (count($resultTags) > 0) {
            $resultTags = array_unique($resultTags);  // 去重
            shuffle($resultTags);  // 随机排序
            $resultTags = array_slice($resultTags, 0, $limit);  // 限制数量
            
            // 使用安全的参数化查询
            $placeholders = implode(',', array_fill(0, count($resultTags), '?'));
            
            if ($isPostgreSQL) {
                $sql = $db->select()->from('table.contents')
                    ->where('status = ?', 'publish')
                    ->where('type = ?', 'post')
                    ->where('cid <> ?', $widget->cid)
                    ->where('cid IN (' . $placeholders . ')')
                    ->limit($limit);
                if (!empty($resultTags)) {
                    $sql->order('RANDOM()');
                }
            } else {
                $sql = $db->select()->from('table.contents')
                    ->where('status = ?', 'publish')
                    ->where('type = ?', 'post')
                    ->where('cid <> ?', $widget->cid)
                    ->where('cid IN (' . $placeholders . ')')
                    ->limit($limit);
                if (!empty($resultTags)) {
                    $sql->order('RAND()');
                }
            }
            
            // 添加参数值
            $sql = $db->query($sql, $resultTags);
        }
    }
    
    $result = $db->fetchAll($sql);
    return $result;
}

// 创建一个模拟Typecho内置Widget的类，用于保持兼容性
class RelatedPostsWidget extends Typecho_Widget {
    private $relatedPosts = array();
    private $current = -1;
    private $count = 0;
    
    public function __construct($relatedPosts) {
        $this->relatedPosts = $relatedPosts;
        $this->count = count($relatedPosts);
    }
    
    public function next() {
        $this->current++;
        return $this->current < $this->count;
    }
    
    public function permalink() {
        $permalink = Typecho_Router::url('post', array('cid' => $this->relatedPosts[$this->current]['cid']), 
                     Typecho_Common::url('', Helper::options()->index));
        echo $permalink;
    }
    
    public function title() {
        echo $this->relatedPosts[$this->current]['title'];
    }
    
    public function date($format) {
        echo date($format, $this->relatedPosts[$this->current]['created']);
    }
    
    // 获取当前文章对象
    public function getCurrent() {
        return $this->relatedPosts[$this->current];
    }
}

/**
 * 生成包含index.php前缀的URL
 * 用于解决没有URL重写的情况
 */
function generateUrlWithIndex($path) {
    // 如果路径已经以/index.php开头，则直接返回
    if (strpos($path, '/index.php') === 0) {
        return $path;
    }
    
    // 如果路径是根路径/，则直接返回
    if ($path === '/' || $path === '') {
        return '/';
    }
    
    // 确保路径以/开头
    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }
    
    // 添加index.php前缀
    return '/index.php' . $path;
}
