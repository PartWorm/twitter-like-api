<?php

include_once 'get_post_branch_class_by_connection.php';
include_once 'to_relative_time.php';
include_once 'linkify.php';

function render_secondary_post($post, $n_children, $depth, $connect_up, $connect_down) {
	echo
		'<a href="post.html?id=' . $post['id'] . '#post">' .
			'<div class="post" style="display: flex;">' .
				str_repeat('<span style="width: 18px;"></span>', $depth) .
				'<div class="post-branch ' . get_post_branch_class_by_connection($connect_up, $connect_down) . '">' .
				'</div>' .
				'<div class="post-body">' .
					'<div class="post-head">' .
						'<span class="post-author">' .
							htmlspecialchars($post['author']) .
						'</span>' .
						'<span class="post-date">' .
							to_relative_time(strtotime($post['timestamp'])) .
						'</span>' .
					'</div>' .
					'<div class="post-content">' .
						linkify_deco_only(nl2br(htmlspecialchars($post['content']))) .
					'</div>' .
					'<div class="post-menu">' .
						($n_children > 0 ? '답글 ' . $n_children . ' · 대화 ' . $post['n_descendants'] : '') .
					'</div>' .
				'</div>' .
			'</div>' .
		'</a>';
}

?>
