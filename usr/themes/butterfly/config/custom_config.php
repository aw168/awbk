<?php
function themeConfig($form)
{
    // 处理复选框选项未被提交的问题
    if (isset($_POST['do']) && $_POST['do'] == 'settings') {
        $checkboxKeys = ['sidebarBlock', 'PostSidebarBlock', 'beautifyBlock'];
        
        // 确保复选框值正确保存到数据库
        // 此处直接修改数据库中的值
        if (isset($_POST['submit'])) {
            $db = Typecho_Db::get();
            $themeOptions = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', 'theme:butterfly'));
            
            if ($themeOptions) {
                $options = unserialize($themeOptions['value']);
                
                // 处理每个复选框选项
                foreach ($checkboxKeys as $key) {
                    if (!isset($_POST[$key]) || (isset($_POST[$key]) && $_POST[$key] === '')) {
                        // 如果没有提交该选项或为空值，则设置为空数组
                        $options[$key] = array();
                    } else if (isset($_POST[$key]) && is_string($_POST[$key])) {
                        // 如果提交的是字符串（单个值），则转换为数组
                        $options[$key] = array($_POST[$key]);
                    }
                    // 如果提交的已经是数组，则不需要处理
                }
                
                // 保存修改后的选项回数据库
                $updateRows = array('value' => serialize($options));
                $db->query($db->update('table.options')->rows($updateRows)->where('name = ?', 'theme:butterfly'));
            }
        }
        
        foreach ($checkboxKeys as $key) {
            if (!isset($_POST[$key]) && isset($_POST['do'])) {
                $_POST[$key] = [];
            }
        }
        
        // 处理typecho-option-submit按钮，确保表单提交时能正确处理复选框
        echo "<script>
        window.onload = function() {
            var form = document.querySelector('.typecho-page-main form');
            if (!form) return;
            
            // 添加一个隐藏的提交标记，用于标识表单提交
            var submitInput = document.createElement('input');
            submitInput.type = 'hidden';
            submitInput.name = 'submit';
            submitInput.value = '1';
            form.appendChild(submitInput);
            
            form.addEventListener('submit', function() {
                var checkboxGroups = ['sidebarBlock', 'PostSidebarBlock', 'beautifyBlock'];
                
                checkboxGroups.forEach(function(groupName) {
                    var checkboxes = document.querySelectorAll('input[name=\"' + groupName + '[]\"]');
                    var hasChecked = false;
                    
                    checkboxes.forEach(function(checkbox) {
                        if (checkbox.checked) {
                            hasChecked = true;
                        }
                    });
                    
                    if (!hasChecked && checkboxes.length > 0) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = groupName;
                        input.value = '';
                        form.appendChild(input);
                    }
                });
            });
        };
        </script>";
    }
    
    ?>
<link rel="stylesheet" href="<?php Helper::options()->themeUrl('css/themedash.css?v1.5.3'); ?>">
<div class='set_toc'>
    <div class='mtoc'>
        <a href='#themeBackup'>主题备份与还原</a>
        <a href='#cids'>文章置顶及公共部分</a>
        <a href='#pjax'>pjax设置</a>
        <a href='#friends'>友情链接设置</a>
        <a href='#reward'>打赏功能</a>
        <a href='#aside'>侧边栏显示设置</a>
        <a href='#beautifyBlock'>美化选项</a>
        <a href='#otherCustom'>其他自定义内容</a>
        <a href='#CustomColor'>自定义颜色</a>
        <a href='#NULL' id='point'>返回上次保存设置时的锚点</a>
    </div>
</div>
<form class="protected" action="?butterflybf" method="post" id="themeBackup">
    <input type="submit" name="type" class="btn btn-s" value="备份主题数据" />&nbsp;&nbsp;<input type="submit" name="type"
        class="btn btn-s" value="还原主题数据" />&nbsp;&nbsp;<input type="submit" name="type" class="btn btn-s"
        value="删除备份数据" />
</form>
<script src='https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js'></script>
<script src="<?php Helper::options()->themeUrl('js/themecustom.js?v1.5.3'); ?>"></script>
<?php
    $sticky_cids = new Typecho_Widget_Helper_Form_Element_Text('sticky_cids', NULL, NULL, '置顶文章的 cid', '<div style="font-family:arial; background:#E8EFD1; padding:8px">按照排序输入, 请以半角逗号或空格分隔 cid</div>');
    $sticky_cids->setAttribute('id', 'cids');
    $form->addInput($sticky_cids);

    $StaticFile = new Typecho_Widget_Helper_Form_Element_Select(
        'StaticFile',
        array(
            'CDN' => 'CDN加载(默认)',
            'local' => '本地加载',
        ),
        'CDN',
        '博客静态资源加载方式',
        '介绍：无网络服务器或者CDN炸了可开启此项<br>
         将博客静态资源，如js、css、图片从服务器加载(会稍微增加服务器流量消耗)<br>
         注意：你需要额外<a href="https://github.com/wehaox/Typecho-Butterfly/releases/download/1.7.7/static-23.11.zip">下载</a>对应版本的静态资源放进主题根目录直接解压即可<br>
         此文件与下方的自定义CDN文件通用'
    );
    $form->addInput($StaticFile->multiMode());

    $CDNURL = new Typecho_Widget_Helper_Form_Element_Text(
        'CDNURL',
        NULL,
        NULL,
        '自定义CDNURL(由@origami-tech提供)',
        '需要选择博客静态资源加载方式为CDN加载 此项才会生效 且<b>本地加载>自定义CDNURL>jsdelivr源</b><br>
    注意：你需要额外<a href="https://github.com/wehaox/Typecho-Butterfly/releases/download/1.7.7/static-23.11.zip">下载</a>静态资源放CDN解压<br>
    链接填写规则：填写static文件夹的父文件夹 无需最后的/ 例如 https://pub-gcdn.starsdust.cn/libs/butterfly '
    );
    $form->addInput($CDNURL);

    $jsdelivrLink = new Typecho_Widget_Helper_Form_Element_Select(
        'jsdelivrLink',
        array(
            'cdn.jsdelivr.net' => '官方默认源',
            'gcore.jsdelivr.net' => 'gcore源',
            'fastly.jsdelivr.net' => 'fastly源',
            'raw.fastgit.org' => 'fastgit源',
        ),
        'gcore.jsdelivr.net',
        'jsdelivr提供的cdn源切换(默认采用gcore源)',
        '需要开启上方的CDN加载'
    );
    $form->addInput($jsdelivrLink->multiMode());

    $NewTabLink = new Typecho_Widget_Helper_Form_Element_Select(
        'NewTabLink',
        array(
            'on' => '开启（默认）',
            'off' => '关闭',
        ),
        'on',
        '是否开启新标签打开外部链接',
        '介绍：非站内链接在新标签打开'
    );
    $form->addInput($NewTabLink->multiMode());

    $showFramework = new Typecho_Widget_Helper_Form_Element_Select(
        'showFramework',
        array(
            'on' => '开启（默认）',
            'off' => '关闭',
        ),
        'on',
        '是否显示底部博客框架和主题',
        '介绍：如果你是小白自行修改主题名会导致侵权提示，你可以在这里关闭同时希望你可以<b>尊重本主题</b>'
    );
    $form->addInput($showFramework->multiMode());

    $Defend = new Typecho_Widget_Helper_Form_Element_Select(
        'Defend',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启网站维护或密码访问',
        '介绍： 下方密码留空则显示网站维护否则显示输入密码访问，登录用户不受限制'
    );
    $form->addInput($Defend->multiMode());

    // 添加帮助处理复选框的JavaScript代码
    ?>
    <script type="text/javascript">
    (function() {
        // 等待文档加载完成
        document.addEventListener('DOMContentLoaded', function() {
            // 获取表单
            var form = document.querySelector('.typecho-page-main form');
            if (!form) return;
            
            // 在表单提交前处理复选框
            form.addEventListener('submit', function(e) {
                // 检查是否存在复选框
                var checkboxGroups = ['sidebarBlock', 'PostSidebarBlock', 'beautifyBlock'];
                
                checkboxGroups.forEach(function(groupName) {
                    var checkboxes = document.querySelectorAll('input[name="' + groupName + '[]"]');
                    if (checkboxes.length > 0) {
                        // 创建隐藏字段，确保即使没有选择任何复选框也会提交该组的名称
                        var hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.name = groupName;
                        hiddenField.value = ''; // 空值表示没有选择
                        form.appendChild(hiddenField);
                    }
                });
            });
        });
    })();
    </script>
    <?php

    $ThemePassword = new Typecho_Widget_Helper_Form_Element_Text('ThemePassword', NULL, NULL, _t('全站密码访问(非必填)'), _t('输入访问网站的密码，<b>需要在上方开启网站维护或密码访问</b>'));
    $form->addInput($ThemePassword);

    $NoQQ = new Typecho_Widget_Helper_Form_Element_Select(
        'NoQQ',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启网站禁止手机QQ访问',
        '介绍：烦人的QQ'
    );
    $form->addInput($NoQQ->multiMode());

    $Sitefavicon = new Typecho_Widget_Helper_Form_Element_Text('Sitefavicon', NULL, NULL, _t('网站图标'), _t('网站图标，使用png格式，大小建议不超过64x64'));
    $form->addInput($Sitefavicon);

    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, _t('#null'), _t('作者头像'), _t('在这里填入图片地址，它会显示在右侧栏的作者头像'));
    $form->addInput($logoUrl);

    $author_name = new Typecho_Widget_Helper_Form_Element_Text('author_name', NULL, _t('IAWooo'), _t('作者名称'), _t('在这里填入作者名称，它会显示在侧边栏的作者信息中'));
    $form->addInput($author_name);

    $author_link = new Typecho_Widget_Helper_Form_Element_Text('author_link', NULL, _t(''), _t('作者名称链接'), _t('在这里填入作者名称的点击跳转链接，将使作者名称可点击，留空则不可点击'));
    $form->addInput($author_link);

    $author_description = new Typecho_Widget_Helper_Form_Element_Text('author_description', NULL, _t('作者描述'), _t('作者描述'), _t('在这里填入站点描述，它会显示在右侧栏的作者信息'));
    $form->addInput($author_description);

    $authorLinks = new Typecho_Widget_Helper_Form_Element_Textarea('authorLinks', NULL, _t(''), _t('社交链接HTML'), _t('在这里填入社交链接的HTML代码，将显示在侧边栏作者头像下方'));
    $form->addInput($authorLinks);

    $author_site_description = new Typecho_Widget_Helper_Form_Element_Text('author_site_description', NULL, _t('个人网站'), _t('作者链接描述'), _t('作者链接描述'));
    $form->addInput($author_site_description);

    $author_site = new Typecho_Widget_Helper_Form_Element_Text('author_site', NULL, _t('#null'), _t('作者链接'), _t('在这里填入作者链接，它会显示在右侧栏的作者信息的个人网站上'));
    $form->addInput($author_site);

    $author_bottom = new Typecho_Widget_Helper_Form_Element_Textarea('author_bottom', NULL, _t(''), _t('侧栏作者信息最底部内容（非必填）'), _t('这里填入html代码,不会勿填'));
    $form->addInput($author_bottom);

    $announcement = new Typecho_Widget_Helper_Form_Element_Textarea('announcement', NULL, _t('这里是公告<br>'), _t('公告'), _t('在这里填入公告，它会显示在右侧栏的公告上,采用html写法'));
    $form->addInput($announcement);

    $AD = new Typecho_Widget_Helper_Form_Element_Textarea('AD', NULL, NULL, _t('广告(由@yzl3014提供)'), _t('在这里填入广告，填入后自动显示在侧栏中公告栏的下方，支持html'));
    $form->addInput($AD);

    $headerimg = new Typecho_Widget_Helper_Form_Element_Text('headerimg', NULL, _t('https://s2.loli.net/2023/01/18/bIJTVaR3MLPzcZ7.jpg'), _t('主页顶图(banner image)'), _t('填入主页头图链接'));
    $form->addInput($headerimg);

    $headerblackimg = new Typecho_Widget_Helper_Form_Element_Text('headerblackimg', NULL, _t('https://s2.loli.net/2023/01/18/bIJTVaR3MLPzcZ7.jpg'), _t('主页深色模式顶图(banner image)'), _t('填入主页头图链接'));
    $form->addInput($headerblackimg);

    $buildtime = new Typecho_Widget_Helper_Form_Element_Text('buildtime', NULL, _t('2021/04/05'), _t('建站时间'), _t('按照输入框内格式填写'));
    $form->addInput($buildtime);

    $outoftime = new Typecho_Widget_Helper_Form_Element_Text('outoftime', NULL, _t('15'), _t('文章过时提醒'), _t('设置文章过时提醒最大天数，默认15天，可在文章管理单独设置是否显示过期提醒'));
    $form->addInput($outoftime);

    $archivelink = new Typecho_Widget_Helper_Form_Element_Text('archivelink', NULL, _t('#null'), _t('侧栏文章(归档)链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($archivelink);

    $tagslink = new Typecho_Widget_Helper_Form_Element_Text('tagslink', NULL, _t('#null'), _t('侧栏标签链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($tagslink);

    $categorylink = new Typecho_Widget_Helper_Form_Element_Text('categorylink', NULL, _t('#null'), _t('侧栏分类链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($categorylink);

    $CloseComments = new Typecho_Widget_Helper_Form_Element_Select(
        'CloseComments',
        array(
            'off' => '关闭（默认）',
            "on" => '开启'
        ),
        'off',
        '全站关闭评论',
        '介绍：开启后所有文章不能评论'
    );
    $form->addInput($CloseComments->multiMode());

    $ShowRelatedPosts = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowRelatedPosts',
        array(
            'off' => '关闭（默认）',
            'on' => '开启',
        ),
        'off',
        '是否显示文章内相关推荐',
        '介绍：开启后文章结束后会显示相关的推荐文章(根据文章标签推荐，不一定每篇文章都会显示)'
    );
    $form->addInput($ShowRelatedPosts->multiMode());

    $RelatedPostsNum = new Typecho_Widget_Helper_Form_Element_Select(
        'RelatedPostsNum',
        array(
            '3' => '3篇（默认）',
            '6' => '6篇',
        ),
        '3',
        '相关推荐显示数量',
        '介绍：最多显示3篇或者6篇相关推荐文章'
    );
    $form->addInput($RelatedPostsNum->multiMode());

    $DefaultEncoding = new Typecho_Widget_Helper_Form_Element_Select(
        'DefaultEncoding',
        array(
            '2' => '简体（默认）',
            '1' => '繁体',
        ),
        '2',
        '博客默认字体',
        '介绍：如果你使用繁体写文章请选择繁体'
    );
    $form->addInput($DefaultEncoding->multiMode());

    $themeFontSize = new Typecho_Widget_Helper_Form_Element_Text('themeFontSize', NULL, _t(''), _t('默认字体大小'), _t('填入像素值，例如14px'));
    $form->addInput($themeFontSize);

    $GravatarSelect = new Typecho_Widget_Helper_Form_Element_Select(
        'GravatarSelect',
        array(
            "https://gravatar.loli.net/avatar/" => 'loli（默认）',
            'https://gravatar.helingqi.com/wavatar/' => '禾令奇',
            "https://sdn.geekzu.org/avatar/" => '极客族',
            "https://cdn.sep.cc/avatar/" => '九月的风',
            "https://gravatar.com/avatar/" => '官方源(被墙)',
            "https://cravatar.cn/avatar/" => '中国官方源(推荐)',
        ),
        'loli',
        'gravatar源选择',
        '介绍：评论区头像gravatar源'
    );
    $GravatarSelect->setAttribute('id', 'gravatarlist');
    $form->addInput($GravatarSelect->multiMode());

    $baidustatistics = new Typecho_Widget_Helper_Form_Element_Text('baidustatistics', NULL, _t(''), _t('百度统计'), _t('仅需要https://hm.baidu.com/hm.js?xxxxxxxxxxxxxxxxxx部分即可'));
    $form->addInput($baidustatistics);

    $EnablePjax = new Typecho_Widget_Helper_Form_Element_Select(
        'EnablePjax',
        array(
            'off' => '关闭（默认）',
            "on" => '开启'
        ),
        'off',
        '开启PJAX',
        '介绍：页面无刷新加载,有效提高页面加载速度<br>
         请先查看<a href="https://blog.wehaox.com/archives/typecho-butterfly.html#cl-13">使用文档</a>'
    );
    $EnablePjax->setAttribute('id', 'pjax');
    $form->addInput($EnablePjax->multiMode());

    $PjaxCallBack = new Typecho_Widget_Helper_Form_Element_Textarea(
        'PjaxCallBack',
        NULL,
        NULL,
        'PJAX回调函数（非必填）',
        '用于解决开启pjax导致某些js失效问题(填入js代码)'
    );
    $form->addInput($PjaxCallBack);

    /* 友链设置 */
    $friendset = new Typecho_Widget_Helper_Form_Element_Select(
        'friendset',
        array(
            '0' => '主题模式',
            '1' => '插件模式',
        ),
        '0',
        '是否使用Link插件进行友链(需点击<a href="https://static.wehao.org/Links.zip">这里</a>下载)',
        '介绍：新手和手残党极其友好,默认从主题读取防止报错'
    );
    $friendset->setAttribute('id', 'friends');
    $form->addInput($friendset);

    $Friends = new Typecho_Widget_Helper_Form_Element_Textarea(
        'Friends',
        NULL,
        NULL,
        '友情链接（非必填）',
        '介绍：用于填写友情链接 <br />
         注意：需在独立页面创建友链，该项才会生效 <br />
         格式：博客名称 || 博客地址 || 博客头像 || 博客简介 <br />
         其他：一行一个，一行代表一个友链'
    );
    $form->addInput($Friends);

    $LazyLoad = new Typecho_Widget_Helper_Form_Element_Text(
        'LazyLoad',
        NULL,
        'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=',
        '图片懒加载占位图',
        '介绍：图片未加载前的占位图，填写图片链接。默认为透明图片'
    );
    $form->addInput($LazyLoad);


    $ShowGlobalReward = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowGlobalReward',
        array(
            'off' => '关闭（默认）',
            'show' => '开启打赏',
        ),
        'off',
        '是否开启全局文章打赏',
        '介绍：开启此功能所有文章将显示打赏'
    );
    $ShowGlobalReward->setAttribute('id', 'reward');
    $form->addInput($ShowGlobalReward->multiMode());

    /* 打赏设置 */
    $RewardInfo = new Typecho_Widget_Helper_Form_Element_Textarea(
        'RewardInfo',
        NULL,
        _t('微信 || https://cdn.jsdelivr.net/gh/wehaox/CDN@main/reward/wechat.jpg
    支付宝 || https://cdn.jsdelivr.net/gh/wehaox/CDN@main/reward/alipay.jpg'),
        '打赏信息（非必填）',
        '注意：需在开启打赏功能，该项才会显示 <br />
         格式：打赏名称 || 图片地址 <br />一行一个'
    );
    $form->addInput($RewardInfo);

    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'sidebarBlock',
        array(
            'ShowAuthorInfo' => _t('显示作者信息'),
            'ShowAnnounce' => _t('显示公告'),
            'ShowRecentPosts' => _t('显示最新文章'),
            'ShowRecentComments' => _t('显示最近回复'),
            'ShowCategory' => _t('显示分类'),
            'ShowTag' => _t('显示标签'),
            'ShowArchive' => _t('显示归档'),
            'ShowMobileSide' => _t('手机端显示侧栏'),
            'ShowWeiboHot' => _t('显示微博热搜')
        ),
        array('ShowAuthorInfo', 'ShowAnnounce', 'ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowTag', 'ShowArchive', 'ShowMobileSide'),
        _t('侧边栏显示')
    );
    $sidebarBlock->setAttribute('id', 'aside');
    // 确保复选框能正确保存值
    $sidebarBlock->input->setAttribute('autocomplete', 'off');
    $form->addInput($sidebarBlock->multiMode());

    $sidderArchiveNum = new Typecho_Widget_Helper_Form_Element_Text('sidderArchiveNum', NULL, _t('5'), _t('侧栏归档显示行数'), _t('默认为5'));
    $form->addInput($sidderArchiveNum);

    // 文章侧边栏设置
    $PostSidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'PostSidebarBlock',
        array(
            'ShowAuthorInfo' => _t('显示作者信息'),
            'ShowAnnounce' => _t('显示公告'),
            'ShowRecentPosts' => _t('显示最新文章'),
            'ShowWeiboHot' => _t('显示微博热搜')
        ),
        array('ShowAuthorInfo', 'ShowAnnounce', 'ShowRecentPosts'),
        _t('文章侧边栏显示'),
        _t('说明:单独设置文章内侧栏')
    );
    // 确保复选框能正确保存值
    $PostSidebarBlock->input->setAttribute('autocomplete', 'off');
    $form->addInput($PostSidebarBlock->multiMode());

    // 美化选项
    $beautifyBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'beautifyBlock',
        array(
            'ShowBeautifyChange' => _t('是否开启美化(基于butterfly小康的魔改)'),
            'ShowTopimg' => _t('是否显示主页顶图'),
            'PostShowTopimg' => _t('是否显示文章示顶图'),
            'PageShowTopimg' => _t('是否显示独立页面顶图'),
            'showLineNumber' => _t('是否显示代码块行号'),
            'showSnackbar' => _t('是否显示主题以及简繁切换弹窗'),
            'showLazyloadBlur' => _t('是否开启懒加载模糊效果'),
            'showNoAlertSearch' => _t('是否开启无弹窗搜索框'),
            'showFooterSiteLinks' => _t('是否RSS和站点地图链接'),
        ),
        array('ShowBeautifyChange', 'ShowTopimg', 'PostShowTopimg', 'PageShowTopimg', 'showLineNumber', 'showSnackbar', 'showLazyloadBlur', 'showNoAlertSearch', 'showFooterSiteLinks'),
        _t('美化选项')
    );
    $beautifyBlock->setAttribute('id', 'beautifyBlock');
    // 确保复选框能正确保存值
    $beautifyBlock->input->setAttribute('autocomplete', 'off');
    $form->addInput($beautifyBlock->multiMode());

    // 文章列表显示样式
    $articleListStyle = new Typecho_Widget_Helper_Form_Element_Select(
        'articleListStyle',
        array(
            'card' => '卡片式(默认)',
            'interactive' => '交互式',
            'left' => '靠左式',
            'right' => '靠右式',
        ),
        'card',
        '文章列表显示样式(全局设置)',
        '选择文章在列表中的显示方式，卡片式为卡片网格布局；交互式为文章概述和照片左右交替显示；靠左式为照片统一靠左，文章概述靠右；靠右式为照片统一靠右，文章概述靠左'
    );
    $form->addInput($articleListStyle->multiMode());

    // 弹窗提示
    $SnackbarPosition = new Typecho_Widget_Helper_Form_Element_Select(
        'SnackbarPosition',
        array(
            'top-left' => '左上(默认)',
            'top-center' => '中上',
            'top-right' => '右上',
            'bottom-left' => '左下',
            'bottom-center' => '中下',
            'bottom-right' => '右下',
        ),
        'top-left',
        '主题以及简繁切换弹窗位置',
        '选择其中一个,需要开启是否显示主题以及简繁切换弹窗 '
    );
    $form->addInput($SnackbarPosition->multiMode());

    $CursorEffects = new Typecho_Widget_Helper_Form_Element_Select(
        'CursorEffects',
        array(
            'off' => '关闭（默认）',
            'heart' => '鼠标点击效果:爱心',
            'fireworks' => '烟火效果',
        ),
        'off',
        '选择鼠标点击特效',
        '介绍：用于切换鼠标点击特效 '
    );
    $form->addInput($CursorEffects->multiMode());
    
    // 自定义subtitle
    $CustomSubtitle = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomSubtitle',
        NULL,
        NULL,
        '自定义主页副标题/subtitle（非必填）',
        '介绍：不填则使用默认的一言api。'
    );
    $form->addInput($CustomSubtitle);

    $SubtitleLoop = new Typecho_Widget_Helper_Form_Element_Select(
        'SubtitleLoop',
        array(
            'true' => '开启循环打字（默认）',
            "false" => '关闭循环打字'
        ),
        'true',
        '副标题循环打字',
        '介绍：开启后主页副标题循环打字'
    );
    $form->addInput($SubtitleLoop->multiMode());


    $EnableAutoHeaderLink = new Typecho_Widget_Helper_Form_Element_Select(
        'EnableAutoHeaderLink',
        array(
            'on' => '开启（默认）',
            "off" => '关闭'
        ),
        'on',
        '自动生成导航栏独立页面链接',
        '介绍：如果你要自定义导航栏链接部分,你可以选择关闭此项'
    );
    $form->addInput($EnableAutoHeaderLink->multiMode());

    // 自定义导航栏链接
    $CustomHeaderLink = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomHeaderLink',
        NULL,
        NULL,
        '自定义导航栏链接',
        '介绍：目前使用html写法 <b style="color:red">完全自定义链接记得关闭上方选项</b>'
    );
    $CustomHeaderLink->setAttribute('id', 'otherCustom');
    $form->addInput($CustomHeaderLink);

    // 添加自定义头部和侧边栏社交链接设置
    $headerSocialLinks = new Typecho_Widget_Helper_Form_Element_Textarea(
        'headerSocialLinks',
        NULL,
        '<a target="_BLANK" href="https://github.com/iawooo" title="GitHub主页"><img src="/usr/themes/butterfly/img/github.svg" style="width:36px;height:36px;"></a>
<a target="_BLANK" href="https://t.me/AwcttBot" title="telegram"><img src="/usr/themes/butterfly/img/telegram.svg" style="width:36px;height:36px;"></a>
<a href="mailto:iawooo@qq.com" title="邮箱"><img src="/usr/themes/butterfly/img/mail.svg" style="width:36px;height:36px;"></a>',
        _t('自定义头部社交链接'),
        _t('介绍：显示在头部和侧边栏的社交链接')
    );
    $form->addInput($headerSocialLinks);

    // 添加三个社交图标跳转地址的设置项
    $githubUrl = new Typecho_Widget_Helper_Form_Element_Text(
        'githubUrl',
        NULL,
        'https://github.com/iawooo',
        _t('GitHub主页链接'),
        _t('设置GitHub图标跳转地址')
    );
    $form->addInput($githubUrl);

    $telegramUrl = new Typecho_Widget_Helper_Form_Element_Text(
        'telegramUrl',
        NULL,
        'https://t.me/AwcttBot',
        _t('Telegram链接'),
        _t('设置Telegram图标跳转地址')
    );
    $form->addInput($telegramUrl);

    $emailUrl = new Typecho_Widget_Helper_Form_Element_Text(
        'emailUrl',
        NULL,
        'mailto:iawooo@qq.com',
        _t('邮箱链接'),
        _t('设置邮箱图标跳转地址，一般格式为"mailto:your-email@example.com"')
    );
    $form->addInput($emailUrl);

    // 自定义认证用户
    $CustomAuthenticated = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomAuthenticated',
        NULL,
        NULL,
        '自定义认证用户',
        '介绍：评论区认证用户专属头衔<br>
         格式：邮箱||认证头衔 如:<br> xxx@xxx.com||xxx认证<br>
        (一行一个)'
    );
    $form->addInput($CustomAuthenticated);

    // 自定义css和js
    $CustomCSS = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomCSS',
        NULL,
        NULL,
        '自定义CSS样式（非必填）',
        '介绍：请填写自定义CSS内容，填写时无需填写style标签。'
    );
    $form->addInput($CustomCSS);

    $CustomScript = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomScript',
        NULL,
        NULL,
        '自定义JS代码（非必填，请看下方介绍）',
        '介绍：请填写自定义JS内容，填写时无需填写script标签。<br />
         非专业人士请勿填写！'
    );
    $form->addInput($CustomScript);

    $CustomHead = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomHead',
        NULL,
        NULL,
        '自定义head标签内位置内容',
        '介绍：填写如cdn的&lt;link&gt;标签、百度统计代码等等'
    );
    $form->addInput($CustomHead);

    $CustomBodyEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomBodyEnd',
        NULL,
        NULL,
        '自定义body标签末尾位置内容',
        '介绍：填写如cdn的&lt;script&gt;&lt;/script&gt;标签等等'
    );
    $form->addInput($CustomBodyEnd);

    $Customfooter = new Typecho_Widget_Helper_Form_Element_Textarea(
        'Customfooter',
        NULL,
        NULL,
        '自定义Footer内容',
        '介绍：网页底部的信息，如备案号等等(可使用html)'
    );
    $form->addInput($Customfooter);

    $themeColor = new Typecho_Widget_Helper_Form_Element_Text('themeColor', NULL, 
    _t('#eee'), _t('主题色'), _t('主要用于支持沉浸式状态栏的浏览器,默认为#eee'));
    $themeColor->setAttribute('id', 'CustomColor');
    $form->addInput($themeColor);
    //暗色模式选项
    $darkModeSelect = new Typecho_Widget_Helper_Form_Element_Select(
        'darkModeSelect',
        array(
            "1" => '始终亮色模式',
            '2' => '跟随系统（默认）',
            '3' => '跟随系统且按时间自动深色',
            '4' => '始终深色',
        ),
        '2',
        '暗色模式相关',
        '介绍：如果用户在左下角设置了颜色模式这里将不会生效'
    );
    $form->addInput($darkModeSelect->multiMode());

    $darkTime = new Typecho_Widget_Helper_Form_Element_Text('darkTime', NULL, 
    _t('7-20'), _t('自动暗色时间段'), _t('默认为7-20,24小时格式,按照格式(7-20)填写'));
    $form->addInput($darkTime);

    //自定义颜色    
    $EnableCustomColor = new Typecho_Widget_Helper_Form_Element_Select(
        'EnableCustomColor',
        array(
            "false" => '关闭（默认）',
            'true' => '开启'
        ),
        'false',
        '开启主题自定义颜色(实验性功能)',
        '介绍：需要开启此选项下面的自定义颜色才能生效，且下面关于颜色的必填'
    );
    $form->addInput($EnableCustomColor->multiMode());

    $CustomColorMain = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorMain',
        NULL,
        _t('#49b1f5'),
        '自定主题主要颜色',
        '介绍：使用hex格式或者颜色英文，如#fff、white'
    );
    $form->addInput($CustomColorMain);

    $CustomColorButtonBG = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorButtonBG',
        NULL,
        _t('#49b1f5'),
        '自定按钮颜色',
        '介绍：同上'
    );
    $form->addInput($CustomColorButtonBG);

    $CustomColorButtonHover = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorButtonHover',
        NULL,
        _t('#ff7242'),
        '自定按钮悬停色',
        '介绍：同上'
    );
    $form->addInput($CustomColorButtonHover);

    $CustomColorSelection = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorSelection',
        NULL,
        _t('#00c4b6'),
        '自定文本选择色',
        '介绍：同上'
    );
    $form->addInput($CustomColorSelection);
    //自定义颜色end

    $siteKey = new Typecho_Widget_Helper_Form_Element_Text(
        'siteKey',
        NULL,
        null,
        '评论区谷歌验证码 <br> Site Key for reCAPTCHAv2:',
        '<a href="https://www.google.com/recaptcha/admin/create">点击获取密钥</a>'
    );

    $secretKey = new Typecho_Widget_Helper_Form_Element_Text('secretKey', NULL, null, _t('Serect Key for reCAPTCHAv2:'), _t('填写两处密钥评论区自动开启谷歌验证码'));
    $form->addInput($siteKey);
    $form->addInput($secretKey);

    $db = Typecho_Db::get();
    $sjdq = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterfly'));
    $ysj = $sjdq['value'];
    if (isset($_POST['type'])) {
        if ($_POST["type"] == "备份主题数据") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'))) {
                $update = $db->update('table.options')->rows(array('value' => $ysj))->where('name = ?', 'theme:butterflybf');
                $updateRows = $db->query($update);
                echo '<div class="tongzhi">备份已更新，请等待自动刷新！如果等不到请点击';
                ?>
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">
window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);
</script>
<?php
            } else {
                if ($ysj) {
                    $insert = $db->insert('table.options')->rows(array('name' => 'theme:butterflybf', 'user' => '0', 'value' => $ysj));
                    $insertId = $db->query($insert);
                    echo '<div class="tongzhi">备份完成，请等待自动刷新！如果等不到请点击';
                    ?>
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">
window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);
</script>
<?php
                }
            }
        }
        if ($_POST["type"] == "还原主题数据") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'))) {
                $sjdub = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'));
                $bsj = $sjdub['value'];
                $update = $db->update('table.options')->rows(array('value' => $bsj))->where('name = ?', 'theme:butterfly');
                $updateRows = $db->query($update);
                echo '<div class="tongzhi">检测到主题备份数据，恢复完成，请等待自动刷新！如果等不到请点击';
                ?>
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">
window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2000);
</script>
<?php
            } else {
                echo '<div class="tongzhi">没有主题备份数据，恢复不了哦！</div>';
            }
        }
        if ($_POST["type"] == "删除备份数据") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'))) {
                $delete = $db->delete('table.options')->where('name = ?', 'theme:butterflybf');
                $deletedRows = $db->query($delete);
                echo '<div class="tongzhi">删除成功，请等待自动刷新，如果等不到请点击';
                ?>
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">
window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);
</script>
<?php
            } else {
                echo '<div class="tongzhi">不用删了！备份不存在！！！</div>';
            }
        }
    }
    // 结束

    // 添加图片质量设置选项
    $imageQuality = new Typecho_Widget_Helper_Form_Element_Select(
        'imageQuality',
        array(
            '75' => '普通 (75%)',
            '85' => '良好 (85%)',
            '95' => '高质量 (95%)',
            '100' => '最高质量 (100%)',
        ),
        '95',
        '图片质量设置',
        '介绍：设置文章中图片的质量，对于部分图床如s2.loli.net等生效。质量越高，图片越清晰但加载越慢'
    );
    $form->addInput($imageQuality->multiMode());
}

function themeFields($layout)
{
    $thumb = new Typecho_Widget_Helper_Form_Element_Text(
        'thumb',
        NULL,
        NULL,
        '自定义文章缩略图',
        '填写时：将会显示填写的文章缩略图 <br>不填写时采用默认图片'
    );
    $layout->addItem($thumb);

    $summaryContent = new Typecho_Widget_Helper_Form_Element_Textarea(
        'summaryContent',
        NULL,
        NULL,
        '自定义文章摘要',
        '不喜欢自动生成的摘要？那就来自定义吧！'
    );
    $layout->addItem($summaryContent);

    $desc = new Typecho_Widget_Helper_Form_Element_Text(
        'desc',
        NULL,
        NULL,
        'SEO描述',
        '用于填写文章或独立页面的SEO描述，如果不填写则没有'
    );
    $layout->addItem($desc);

    $keywords = new Typecho_Widget_Helper_Form_Element_Text(
        'keywords',
        NULL,
        NULL,
        'SEO关键词',
        '用于填写文章或独立页面的SEO关键词，如果不填写则没有'
    );
    $layout->addItem($keywords);


    $showTimeWarning = new Typecho_Widget_Helper_Form_Element_Select(
        'showTimeWarning',
        array(
            'on' => '开启（默认）',
            'off' => '关闭'
        ),
        'on',
        '是否开启当前页面的文章过期警告',
        '用于单独设置当前文章的过期警告 <br /> <b>仅在文章内作用,独立页面无需改动</b> <br />'
    );
    $layout->addItem($showTimeWarning);

    $ShowReward = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowReward',
        array(
            'off' => '关闭（默认）',
            'show' => '开启打赏',
        ),
        'off',
        '是否开启文章打赏',
        '介绍：开启此功能需要在主题设置中添加二维码图片'
    );
    $layout->addItem($ShowReward);
    $ShowToc = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowToc',
        array(
            'show' => '开启（默认）',
            'off' => '关闭',
        ),
        'show',
        '是否显示文章目录',
        '介绍：或许有的文章不需要目录功能,默认是开启的,一般不需要设置'
    );
    $layout->addItem($ShowToc);

    $CopyRight = new Typecho_Widget_Helper_Form_Element_Select(
        'CopyRight',
        array(
            'on' => ' CC BY-NC-SA 4.0（默认）',
            'off' => '禁止转载',
        ),
        'on',
        '文章版权说明',
        '介绍：默认为CC BY-NC-SA 4.0'
    );
    $layout->addItem($CopyRight);

    $NoCover = new Typecho_Widget_Helper_Form_Element_Select(
        'NoCover',
        array(
            'on' => '显示封面',
            'off' => '不显示封面',
        ),
        'on',
        '主页是否显示封面',
        '介绍：这篇文章看来不需要封面'
    );
    $layout->addItem($NoCover);
}

function customThemeInit($archive)
{
    // 处理复选框选项未被提交的问题
    if (Helper::options()->request->isPost() && (Helper::options()->request->get('do') == 'settings' || Helper::options()->request->get('do') == 'backup') && Helper::options()->request->get('config') == 'butterfly') {
        $checkboxKeys = ['sidebarBlock', 'PostSidebarBlock', 'beautifyBlock'];
        
        foreach ($checkboxKeys as $key) {
            if (!isset($_POST[$key]) || (isset($_POST[$key]) && $_POST[$key] === '')) {
                // 如果复选框组不存在或者值为空字符串，设置为空数组
                $_POST[$key] = [];
            }
        }
    }
    
    // 直接在保存后处理复选框选项
    if (Helper::options()->request->isPost() && Helper::options()->request->get('do') == 'settings' && Helper::options()->request->get('config') == 'butterfly') {
        $db = Typecho_Db::get();
        $options = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterfly'));
        
        if ($options) {
            $value = unserialize($options['value']);
            
            // 检查并修复复选框的值
            $checkboxKeys = ['sidebarBlock', 'PostSidebarBlock', 'beautifyBlock'];
            $needUpdate = false;
            
            foreach ($checkboxKeys as $key) {
                // 如果提交的表单中有该键但值为空字符串，则将其设置为空数组
                if (isset($_POST[$key]) && $_POST[$key] === '') {
                    $value[$key] = [];
                    $needUpdate = true;
                }
            }
            
            // 如果需要更新，将修改后的值保存回数据库
            if ($needUpdate) {
                $db->query($db->update('table.options')
                    ->rows(['value' => serialize($value)])
                    ->where('name = ?', 'theme:butterfly'));
            }
        }
    }
    
    if (Helper::options()->EnablePjax == "on") {
        Helper::options()->commentsAntiSpam = false;
    }
    if ($archive->is('single')) {
        $archive->content = createCatalog($archive->content);
        $archive->content = ParseCode($archive->content);
    }
    $loginStatus = $archive->widget('Widget_User')->hasLogin();
    if (Helper::options()->siteKey !== "" && Helper::options()->secretKey !== "" && !$loginStatus) {
        comments_filter($archive);
    }
    if ($archive->is('index')) {
        // echo '<script src="'..'"></script>';        
    }
}
?>