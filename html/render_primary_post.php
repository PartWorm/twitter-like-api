<?php

include_once 'get_post_branch_class_by_connection.php';
include_once 'linkify.php';

function render_primary_post($post, $n_children, $parent_author, $icu, $icd) {
	echo
		'<div id="post" class="post primary-post ' . ($icd ? '' : 'post-unconnected-down') . '" style="display: flex; font-size: 1.3em;">' .
			'<div class="post-branch ' . (get_post_branch_class_by_connection($icu, $icd)) . '">' .
			'</div>' .
			'<div class="post-body">' .
				'<div class="post-head">' .
					'<span class="post-author">' .
						htmlspecialchars($post['author']) .
					'</span>' .
					'<span class="post-date">' .
						$post['timestamp'] .
					'</span>' .
				'</div>' .
				'<div class="post-content">' .
					linkify(nl2br(htmlspecialchars($post['content']))) .
				'</div>' .
				'<div class="post-menu">' .
					($n_children > 0 ? '답글 ' . $post['n_descendants'] : '') .
					// ⤷⋔
				'</div>' .
			'</div>' .
		'</div>';
}
