<?php

include_once 'get_important_child.php';

function read_children_or_thread($conn, &$post, $order_by, $depth = 1) {
	if ($depth >= 5) {
		return;
	}
	if ($depth < 4) {
		$children_result = get_stmt_result($conn, <<<SQL
			SELECT id, author, content, timestamp, n_children, n_descendants
			FROM posts
			WHERE parent = ?
			ORDER BY 
			SQL . $order_by,
			'i',
			$post['id'],
		);
		$children = array();
		while ($child = mysqli_fetch_assoc($children_result)) {
			$child['timestamp'] = to_relative_time(strtotime($child['timestamp']));
			read_children_or_thread($conn, $child, 'timestamp ASC', $depth + 1);
			$children[] = $child;
		}
		$post['children'] = $children;
	}
	else {
		$thread = array();
		$child = $post;
		while (true) {
			$child = get_important_child($conn, $child['id'], $order_by);
			if (is_null($child)) {
				break;
			}
			$child['timestamp'] = to_relative_time(strtotime($child['timestamp']));
			$thread[] = $child;
		}
		$post['thread'] = $thread;
	}
}

?>
