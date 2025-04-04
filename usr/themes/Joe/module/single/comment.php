<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$this->comments()->to($comments);
$is_comment = ($this->allow('comment') && $this->options->JCommentStatus != "off") ? true : false;
$login_comment = $this->options->JcommentLogin == 'on' && !is_numeric(USER_ID) ? true : false;
?>
<div class="joe_comment__title title-theme">评论 <small><?= $is_comment ? (empty($this->commentsNum) ? '抢沙发' : '共' . $this->commentsNum . '条') : null ?></small></div>
<div class="joe_comment" id="comment_module">
	<?php
	if (($is_comment && is_numeric($this->options->JcommentAutoRefresh) && !$login_comment)) {
	?>
		<a href="<?= $this->request->getRequestUrl() ?>" style="display: none;" auto-refresh="<?= $this->options->JcommentAutoRefresh ?>"></a>
	<?php
	}
	if ($this->hidden) {
	?>
		<div class="joe_comment__close">
			<svg class="joe_comment__close-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
				<path d="M512.307.973c282.317 0 511.181 201.267 511.181 449.587a402.842 402.842 0 0 1-39.27 173.26 232.448 232.448 0 0 0-52.634-45.977c16.384-39.782 25.293-82.688 25.293-127.283 0-211.098-199.117-382.157-444.621-382.157-245.555 0-444.57 171.06-444.57 382.157 0 133.427 79.514 250.88 200.039 319.18v107.982l102.041-65.127a510.157 510.157 0 0 0 142.49 20.122l19.405-.359c19.405-.716 38.758-2.508 57.958-5.427l3.584 13.415a230.607 230.607 0 0 0 22.323 50.688l-20.633 3.328a581.478 581.478 0 0 1-227.123-12.288L236.646 982.426c-19.66 15.001-35.635 7.168-35.635-17.664v-157.39C79.411 725.198 1.024 595.969 1.024 450.56 1.024 202.24 229.939.973 512.307.973zm318.464 617.011c97.485 0 176.794 80.435 176.794 179.2S928.256 976.23 830.77 976.23c-97.433 0-176.742-80.281-176.742-179.046 0-98.816 79.309-179.149 176.742-179.149zM727.757 719.002a131.174 131.174 0 0 0-25.754 78.182c0 71.885 57.805 130.406 128.768 130.406 28.877 0 55.552-9.625 77.056-26.01zm103.014-52.327c-19.712 0-39.117 4.557-56.678 13.312L946.33 854.58c8.499-17.305 13.158-36.864 13.158-57.395 0-71.987-57.805-130.509-128.717-130.509zM512.307 383.13l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072 67.072 67.072 0 0 1 66.662-67.43zm266.752 0l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072h-.051l.307-6.86a67.072 67.072 0 0 1 66.406-60.57zm-533.504 0l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072 67.072 67.072 0 0 1 66.662-67.43z" />
			</svg>
			<span>当前文章受密码保护，无法评论</span>
		</div>
	<?php
	} else if ($this->options->JCommentStatus == "off") {
	?>
		<div class="joe_comment__close">
			<svg class="joe_comment__close-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
				<path d="M512.307.973c282.317 0 511.181 201.267 511.181 449.587a402.842 402.842 0 0 1-39.27 173.26 232.448 232.448 0 0 0-52.634-45.977c16.384-39.782 25.293-82.688 25.293-127.283 0-211.098-199.117-382.157-444.621-382.157-245.555 0-444.57 171.06-444.57 382.157 0 133.427 79.514 250.88 200.039 319.18v107.982l102.041-65.127a510.157 510.157 0 0 0 142.49 20.122l19.405-.359c19.405-.716 38.758-2.508 57.958-5.427l3.584 13.415a230.607 230.607 0 0 0 22.323 50.688l-20.633 3.328a581.478 581.478 0 0 1-227.123-12.288L236.646 982.426c-19.66 15.001-35.635 7.168-35.635-17.664v-157.39C79.411 725.198 1.024 595.969 1.024 450.56 1.024 202.24 229.939.973 512.307.973zm318.464 617.011c97.485 0 176.794 80.435 176.794 179.2S928.256 976.23 830.77 976.23c-97.433 0-176.742-80.281-176.742-179.046 0-98.816 79.309-179.149 176.742-179.149zM727.757 719.002a131.174 131.174 0 0 0-25.754 78.182c0 71.885 57.805 130.406 128.768 130.406 28.877 0 55.552-9.625 77.056-26.01zm103.014-52.327c-19.712 0-39.117 4.557-56.678 13.312L946.33 854.58c8.499-17.305 13.158-36.864 13.158-57.395 0-71.987-57.805-130.509-128.717-130.509zM512.307 383.13l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072 67.072 67.072 0 0 1 66.662-67.43zm266.752 0l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072h-.051l.307-6.86a67.072 67.072 0 0 1 66.406-60.57zm-533.504 0l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072 67.072 67.072 0 0 1 66.662-67.43z" />
			</svg>
			<span>所有页面的评论已关闭</span>
		</div>
	<?php
	} else if (!$this->allow('comment')) {
	?>
		<div class="joe_comment__close">
			<svg class="joe_comment__close-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
				<path d="M512.307.973c282.317 0 511.181 201.267 511.181 449.587a402.842 402.842 0 0 1-39.27 173.26 232.448 232.448 0 0 0-52.634-45.977c16.384-39.782 25.293-82.688 25.293-127.283 0-211.098-199.117-382.157-444.621-382.157-245.555 0-444.57 171.06-444.57 382.157 0 133.427 79.514 250.88 200.039 319.18v107.982l102.041-65.127a510.157 510.157 0 0 0 142.49 20.122l19.405-.359c19.405-.716 38.758-2.508 57.958-5.427l3.584 13.415a230.607 230.607 0 0 0 22.323 50.688l-20.633 3.328a581.478 581.478 0 0 1-227.123-12.288L236.646 982.426c-19.66 15.001-35.635 7.168-35.635-17.664v-157.39C79.411 725.198 1.024 595.969 1.024 450.56 1.024 202.24 229.939.973 512.307.973zm318.464 617.011c97.485 0 176.794 80.435 176.794 179.2S928.256 976.23 830.77 976.23c-97.433 0-176.742-80.281-176.742-179.046 0-98.816 79.309-179.149 176.742-179.149zM727.757 719.002a131.174 131.174 0 0 0-25.754 78.182c0 71.885 57.805 130.406 128.768 130.406 28.877 0 55.552-9.625 77.056-26.01zm103.014-52.327c-19.712 0-39.117 4.557-56.678 13.312L946.33 854.58c8.499-17.305 13.158-36.864 13.158-57.395 0-71.987-57.805-130.509-128.717-130.509zM512.307 383.13l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072 67.072 67.072 0 0 1 66.662-67.43zm266.752 0l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072h-.051l.307-6.86a67.072 67.072 0 0 1 66.406-60.57zm-533.504 0l6.861.358a67.072 67.072 0 0 1 59.853 67.072l-.307 6.86a67.072 67.072 0 0 1-66.407 60.57l-6.81-.358a67.072 67.072 0 0 1-59.852-67.072 67.072 67.072 0 0 1 66.662-67.43z" />
			</svg>
			<span>当前页面的评论已关闭</span>
		</div>
	<?php
	} else {
	?>
		<div id="<?php $this->respondId(); ?>" class="joe_comment__respond">
			<div class="joe_comment__respond-type">
				<?php
				if ($this->options->JcommentDraw == 'on' && !$login_comment) {
				?>
					<button class="item" data-type="draw">画图模式</button>
					<button class="item active" data-type="text">文本模式</button>
				<?php
				}
				?>
			</div>
			<form method="post" class="joe_comment__respond-form" action="<?php $this->commentUrl() ?>" data-type="text">
				<div class="head">
					<?php
					if ($this->user->hasLogin() || $login_comment) {
					?>
						<style>
							.joe_comment__respond-form .head {
								border-bottom: none;
							}
						</style>
						<input type="hidden" name="author" value="<?= $this->user->screenName() ?>">
						<input type="hidden" name="mail" value="<?= $this->user->mail() ?>">
						<input type="hidden" name="url" value="<?= $this->user->url() ?>">
					<?php
					} else {
					?>
						<div class="list">
							<input type="text" value="<?php $this->remember('author') ?>" autocomplete="off" name="author" maxlength="16" placeholder="请输入昵称..." />
						</div>
						<div class="list">
							<input type="text" value="<?php $this->remember('mail') ?>" autocomplete="off" name="mail" placeholder="请输入真实邮箱，用于接收回信..." />
						</div>
						<div class="list">
							<input type="text" value="<?php $this->remember('url') ?>" autocomplete="off" name="url" placeholder="请输入网址（非必填）..." />
						</div>
					<?php
					}
					?>
				</div>
				<div class="body">
					<?php
					if ($this->options->JcommentDraw == 'on' && !$login_comment) {
					?>
						<textarea class="text joe_owo__target" name="text" value="" autocomplete="new-password" placeholder="聊点什么吧，回车可快速发送，点击左上方切换成画图试试？"></textarea>
						<div class="draw" style="display: none;">
							<ul class="line">
								<li data-line="3">细</li>
								<li data-line="5" class="active">中</li>
								<li data-line="8">粗</li>
							</ul>
							<ul class="color">
								<li data-color="#303133" class="active"></li>
								<li data-color="#67c23a"></li>
								<li data-color="#e6a23c"></li>
								<li data-color="#f56c6c"></li>
							</ul>
							<svg class="icon icon-undo" viewBox="0 0 1365 1024" xmlns="http://www.w3.org/2000/svg" width="24" height="24">
								<path d="M754.133 337.333c-16.4-2.266-32.933-3.6-49.6-3.6h-7.066V161.867c0-40.4-14.667-65.734-36.667-70.134-1.467-.4-3.067 0-4.667-.133-2.8-.267-5.466-.667-8.533-.133-10.133 1.466-21.2 6.533-33.067 16L192 447.467c-3.067 2.4-4.8 5.466-7.467 8-3.2 3.2-6.4 6.4-9.066 9.866-2.4 3.334-4.534 6.534-6.534 9.867-2.666 4.667-4.666 9.467-6.4 14.4-.8 2.267-1.866 4.4-2.4 6.667-.8 3.333-.933 6.666-1.333 9.866-.133 1.334-.4 2.534-.4 3.867-.267 3.067-.133 6 0 9.067.133 1.733.4 3.333.667 4.933.4 2.8.4 5.733 1.066 8.533.8 3.867 2.667 7.334 4.134 11.067 1.066 2.8 2.266 5.733 3.733 8.533 2.533 4.8 5.467 9.467 9.2 14l.133.134c4.4 5.466 8.667 11.066 14.667 15.866l419.467 336.534c45.466 36.4 85.466-.534 85.466-54.667V680.4h63.6c22 0 43.467 1.333 64.134 3.6 9.466 1.067 18.533 3.2 27.866 4.667 11.067 1.866 22.4 3.333 33.2 5.866 8.667 2 16.8 4.934 25.2 7.467 11.067 3.333 22.134 6.267 32.8 10.4 7.2 2.667 14 6.267 21.067 9.333 11.333 5.067 22.8 10 33.6 16 5.467 3.067 10.533 6.8 15.867 10 11.866 7.334 23.6 14.667 34.533 23.067 3.6 2.8 6.933 6.133 10.533 9.067 12.134 10 24.134 20.266 35.334 31.733 1.2 1.2 2.266 2.667 3.466 4 26.667 28.133 50.667 60.4 71.334 97.2 8.533 14 17.2 19.333 23.733 18.4 6.667-.533 11.333-7.333 11.333-18.4-3.333-255.733-206.4-540.933-450.4-575.467zm6.4 276.267h-130.4v249.067c-6-2.4-12.266-5.734-18.533-10.8L232.8 548c-10-21.333-10.133-44.933-.4-66.267l382.133-307.466c5.2-4.267 10.4-7.467 15.467-10v236.8l66.933-.534h7.6c99.867 0 194.4 43.867 274.134 112.534 62.8 73.733 123.2 161.466 149.066 254.133-85.733-102-217.866-153.6-367.2-153.6zm0 0" />
							</svg>
							<svg class="icon icon-animate" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="24" height="24">
								<path d="M954.9 228.4H619.1c-4.5 0-8.1 3.6-8.1 8s53.8 56 58.2 56H955c4.4 0 8.1-3.6 8.1-8v-48c-.1-4.4-3.7-8-8.2-8zm5.3 352H837.9c-1.5 0-2.8 3.6-2.8 8v48c0 4.4 1.3 8 2.8 8h122.4c1.5 0 2.8-3.6 2.8-8v-48c-.1-4.4-1.3-8-2.9-8zm-1.6 128H807.4c-2.4 0-4.4 3.6-4.4 8v48c0 4.4 2 8 4.4 8h151.2c2.4 0 4.4-3.6 4.4-8v-48c0-4.4-2-8-4.4-8z" />
								<path d="M457.4 798.5l-171.7 90.3c-31.3 16.4-70 4.4-86.4-26.9-6.5-12.5-8.8-26.7-6.4-40.6l32.8-191.2-139-135.4c-25.3-24.7-25.8-65.2-1.2-90.5 9.8-10.1 22.7-16.6 36.6-18.7l192-27.9 85.9-174c15.6-31.7 54-44.7 85.7-29.1 12.6 6.2 22.8 16.4 29.1 29.1l85.9 174 192 27.9c35 5.1 59.2 37.6 54.1 72.5-2 13.9-8.6 26.8-18.7 36.6L689.2 630.1 722 821.4c6 34.8-17.4 67.9-52.3 73.9-13.9 2.4-28.1.1-40.6-6.4l-171.7-90.4zM656 837.8c1.2.7 2.7.9 4.1.6 3.5-.6 5.8-3.9 5.2-7.4l-37.9-221L788 453.4c1-1 1.7-2.3 1.9-3.7.5-3.5-1.9-6.7-5.4-7.3l-222-32.3-99.3-201.2c-.6-1.3-1.6-2.3-2.9-2.9-3.2-1.6-7-.3-8.6 2.9l-99.3 201.2-222 32.3c-1.4.2-2.7.9-3.7 1.9-2.5 2.5-2.4 6.6.1 9.1L287.5 610l-37.9 221.1c-.2 1.4 0 2.8.6 4.1 1.6 3.1 5.5 4.3 8.6 2.7l198.6-104.4L656 837.8z" />
							</svg>
							<canvas id="joe_comment_draw" height="300"></canvas>
						</div>
					<?php
					} else {
					?>
						<textarea class="text joe_owo__target" name="text" value="<?php $this->remember('text') ?>" autocomplete="new-password" placeholder="<?= $login_comment ? '请登录后再进行评论' : '来都来啦，聊点什么吧，回车可快速发送' ?>" <?= $login_comment ? 'disabled="true"' : null ?>><?php $this->remember('text') ?></textarea>
					<?php
					}
					?>
				</div>
				<div class="foot">
					<div class="owo joe_owo__contain" data-url="<?= empty($this->options->JOwOAssetsUrl) ? '' : rtrim($this->options->JOwOAssetsUrl, ' /') . '/' ?>"></div>
					<div class="submit">
						<span class="cancle joe_comment__cancle">取消</span>
						<?php
						if ($login_comment) {
							echo '<a href="' . joe\user_url('login') . '" rel="nofollow">登录评论</a>';
						} else {
							echo '<button type="submit">发送评论</button>';
						}
						?>

					</div>
				</div>
			</form>
		</div>
	<?php
		if ($comments->have()) {
			$comments->listComments();
		} else {
			echo '<ol class="comment-list" style="display: none;"></ol>';
		}
		$comments->pageNav(
			'<i class="fa fa-angle-left em12"></i><span class="hide-sm ml6">上一页</span>',
			'<span class="hide-sm mr6">下一页</span><i class="fa fa-angle-right em12"></i>',
			1,
			'...',
			array(
				'wrapTag' => 'ul',
				'wrapClass' => 'joe_pagination',
				'itemTag' => 'li',
				'textTag' => 'a',
				'currentClass' => 'active',
				'prevClass' => 'prev',
				'nextClass' => 'next'
			)
		);
	}
	?>
</div>

<?php
function threadedComments($comments, $options)
{
	$login_comment = Helper::options()->JcommentLogin == 'on' && !is_numeric(USER_ID) ? true : false;
?>
	<li class="comment-list__item" <?= $comments->status == 'waiting' ? 'style="opacity: 0.8;"' : null ?>>
		<div class="comment-list__item-contain" id="<?php $comments->theId(); ?>">
			<div class="term">
				<?php
				$mobile_handle = joe\isMobile() ? 'data-placement="right" data-trigger="hover" data-content="' . ($comments->authorId == $comments->ownerId ? '作者&nbsp;·&nbsp;' : '') . $comments->author . '&nbsp;·&nbsp;' . joe\getAgentOS($comments->agent) . '&nbsp;·&nbsp;' . joe\getAgentBrowser($comments->agent) . '&nbsp;·&nbsp;' . joe\dateWord($comments->dateWord) . '" data-toggle="popover"' : '';
				if ($comments->authorId == $comments->ownerId && joe\isMobile()) $mobile_handle .= ' style="border-color: var(--theme);"';
				if ($comments->request->getHeader('x-requested-with')) {
				?>
					<img <?= $mobile_handle ?> width="48" height="48" class="avatar" src="<?php joe\getAvatarByMail($comments->mail); ?>" alt="头像" />
				<?php
				} else {
				?>
					<img <?= $mobile_handle ?> width="48" height="48" class="avatar lazyload" src="<?php joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($comments->mail); ?>" alt="头像" />
				<?php
				}
				?>
				<div class="content">
					<?php
					if (joe\isPc()) {
					?>
						<div class="user">
							<div class="nickname">
								<span class="author"><?php $comments->author(); ?></span>
								<?= $comments->authorId == $comments->ownerId ? '<i class="owner">作者</i>' : null ?>
							</div>
							<span>&nbsp;·&nbsp;</span>
							<div class="agent">
								<?php
								$os_svg = joe\getAgentOSIcon($comments->agent) . '.svg';
								$os_svg_url =  joe\theme_url('assets/images/agent/' . $os_svg, false);

								$AgentBrowser = joe\getAgentBrowser($comments->agent);
								$browser_svg = str_replace(' ', '-', $AgentBrowser);
								if (file_exists(JOE_ROOT . 'assets/images/agent/' . $browser_svg . '.svg')) {
									$browser_url =  joe\theme_url('assets/images/agent/' . $browser_svg . '.svg', false);
								} else {
									$browser_url =  joe\theme_url('assets/images/agent/' . $browser_svg . '.png', false);
								}
								?>
								<img src="<?= $os_svg_url ?>" title="<?= joe\getAgentOS($comments->agent) ?>" data-toggle="tooltip">
								<img src="<?= $browser_url ?>" title="<?= $AgentBrowser ?>" data-toggle="tooltip">
							</div>
							<?= $comments->status == "waiting" ? '<em class="waiting">（评论审核中...）</em>' : null ?>
							<div class="handle">
								<time class="date" data-toggle="tooltip" title="<?php $comments->date('Y-m-d H:i:s'); ?>" datetime="<?php $comments->date('Y-m-d H:i:s'); ?>"><?= joe\dateWord($comments->dateWord); ?></time>
								<?= !$login_comment ? '<span class="reply joe_comment__reply" data-id="' . $comments->theId . '" data-coid="' . $comments->coid . '"><i class="icon fa fa-pencil" aria-hidden="true"></i>回复</span>' : null ?>
							</div>
						</div>
					<?php
					}
					?>
					<div class="substance">
						<?= ($comments->status == "waiting" && joe\isMobile()) ? '<em class="waiting">（评论审核中...）</em>' : null ?>
						<?php joe\getParentReply($comments->parent) ?><?= _parseCommentReply($comments->content); ?>
						<?php
						if (joe\isMobile() && !$login_comment) echo '<p class="handle mobile-handle"><span class="reply joe_comment__reply" data-id="' . $comments->theId . '" data-coid="' . $comments->coid . '"><i class="icon fa fa-pencil" aria-hidden="true"></i></span></p>';
						?>
					</div>
				</div>
			</div>
		</div>
		<?php if ($comments->children) : ?>
			<div class="comment-list__item-children">
				<?php $comments->threadedComments($options); ?>
			</div>
		<?php endif; ?>
	</li>
<?php
}
?>