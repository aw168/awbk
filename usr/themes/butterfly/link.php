<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<style>
/* 统一图标样式 */
.card-info-social-icons img,
.additional-links img {
    width: 36px !important;
    height: 36px !important;
    margin: 0 8px !important;
    transition: all 0.3s ease;
}

.card-info-social-icons a:hover img,
.additional-links a:hover img {
    transform: scale(1.2) !important;
}
</style>
<?php 
/**  
    * 友链
    *  
    * @package custom  
    */  
$this->need('page_header.php'); 
?>
<main class="layout" id="content-inner">
<div id="page">
<div id="article-container">
    <div class="flink">
<?php $this->content(); ?>
    <div class="flink-list">
<?php
// 首先检查是否使用插件模式
if($this->options->friendset==1){
    // 插件模式 - 检查Links插件是否激活
    if(array_key_exists("Links", Typecho_Plugin::export()['activated'])){
        $errorimg="'/usr/themes/butterfly/img/friend_404.gif'";
        $shuffle = Helper::options()->linksshuffle;
        $link_limit = Helper::options()->linksIndexNum;
        $Links = Links_Plugin::output("
        <div class='flink-list-item'>
        <a target='_blank' href='{url}'>
        <div class='flink-item-icon'>
        <img onerror=\"this.onerror=null;this.src='/usr/themes/butterfly/img/friend_404.gif'\" src='{GetLazyLoad()}' data-lazy-src='{image}' alt='{name}' class='entered'/></div>
        <div class='flink-item-name'>{name}</div>
        <div class='flink-item-desc' title='{description}'>{description}</div>
        </a></div>");
        for($i = 0; $i < count($Links); $i++){
            echo $Links[$i];
        }
    } else {
        // 如果插件未激活但选择了插件模式，提示用户
        echo '<div class="flink-list-item"><div class="flink-item-name">Links插件未激活，请在后台启用该插件或更改设置为主题模式</div></div>';
    }
} else {
    // 主题模式 - 从主题设置中获取友链数据
    if ($this->options->Friends){
        if (strpos($this->options->Friends, '||') !== false) {
            $errorimg="'/usr/themes/butterfly/img/friend_404.gif'";
            $list = "";
            $txt = $this->options->Friends;
            $string_arr = explode("\r\n", $txt);
            $long = count($string_arr);
            for ($i = 0; $i < $long; $i++) {
                if(count(explode("||", $string_arr[$i])) >= 4) {
                    $name = trim(explode("||", $string_arr[$i])[0]);
                    $url = trim(explode("||", $string_arr[$i])[1]);
                    $image = trim(explode("||", $string_arr[$i])[2]);
                    $description = trim(explode("||", $string_arr[$i])[3]);
                    
                    $list .= '<div class="flink-list-item">
                    <a target="_blank" title="' . $name . '" href="' . $url . '">
                    <div class="flink-item-icon">
                    <img onerror="this.onerror=null;this.src=' . $errorimg . '" src="' . GetLazyLoad() . '" data-lazy-src="' . $image . '" alt="' . $name . '" class="entered"/>
                    </div>
                    <div class="flink-item-name">' . $name . '</div>
                    <div class="flink-item-desc" title="' . $description . '">' . $description . '</div>
                    </a>
                    </div>';
                }
            }
            echo $list;
        } else {
            echo '<div class="flink-list-item"><div class="flink-item-name">友链格式不正确，请确保使用格式：博客名称 || 博客地址 || 博客头像 || 博客简介</div></div>';
        }
    } else {
        echo '<div class="flink-list-item"><div class="flink-item-name">尚未添加任何友链，请在主题设置中添加</div></div>';
    }
}
?>

            </div>
    </div>
</div>

<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('post_sidebar.php'); ?>
<script src="<?php $this->options->themeUrl('js/comjs.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/prism.js?v1.0'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/clipboard.min.js'); ?>"></script>
<script>
$(document).ready(function(){var tocState = $(".toc").html();if(tocState.length == "1") {
$("#card-toc,#mobile-toc-button").remove();}});
</script>
<?php if (!empty($this->options->beautifyBlock) && in_array('showLineNumber',
    $this->options->beautifyBlock)): ?> 
<script type="text/javascript">
	(function(){
		var pres = document.querySelectorAll('pre');
		var lineNumberClassName = 'line-numbers';
		pres.forEach(function (item, index) {
			item.className = item.className == '' ? lineNumberClassName : item.className + ' ' + lineNumberClassName;
		});
	})();
</script>
<?php endif; ?>
</main>
<!-- end #main-->
<?php $this->need('footer.php'); ?>
