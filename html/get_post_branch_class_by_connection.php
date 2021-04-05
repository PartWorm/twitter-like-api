<?php

function get_post_branch_class_by_connection($is_connected_up, $is_connected_down) {
	return $is_connected_up ?
		($is_connected_down ? 'post-branch-all' : 'post-branch-up') :
		($is_connected_down ? 'post-branch-down' : '');
}

?>
