<?php

include_once 'get_post_branch_class_by_connection.php';
include_once 'time_to_str.php';
include_once 'linkify.php';

function render_secondary_post($post, $n_children, $parent_author, $icu, $icd) {
	echo
		'<a href="post.html?id=' . $post['id'] . '#post">' .
			'<div class="post ' . ($icd ? '' : 'post-unconnected-down') . '" style="display: flex;">' .
				'<div class="post-branch ' . (get_post_branch_class_by_connection($icu, $icd)) . '">' .
				'</div>' .
				'<div class="post-body">' .
					'<div class="post-head">' .
						'<span class="post-author">' .
							htmlspecialchars($post['author']) . (false && isset($parent_author) ? '→' . htmlspecialchars($parent_author) : '') .
						'</span>' .
						'<span class="post-date">' .
							time_to_str(strtotime($post['timestamp'])) .
						'</span>' .
					'</div>' .
					'<div class="post-content">' .
						linkify_deco_only(nl2br(htmlspecialchars($post['content']))) .
					'</div>' .
					'<div class="post-menu">' .
						($n_children > 0 ? '답글 ' . $post['n_descendants'] : '') .
					'</div>' .
				'</div>' .
			'</div>' .
		'</a>';

}

?>
