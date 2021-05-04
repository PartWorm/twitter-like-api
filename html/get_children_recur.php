<?php

function get_children_recur($conn, $id, $parent_depth = 0) {
	if ($parent_depth >= 2) {
		// Can't reach
		return;
	}
	$my_depth = $parent_depth + 1;
	$children_result = get_stmt_result($conn, <<<SQL
		SELECT id, author, content, timestamp
		FROM posts
		WHERE parent = ?
		SQL,
		'i',
		$id,
	);
	$children = array();
	while ($child = mysqli_fetch_assoc($children_result)) {
		$child['timestamp'] = time_to_str(strtotime($child['timestamp']));
		if ($my_depth < 2) {
			$child['children'] = get_children_recur($conn, $child['id'], $my_depth);
		}
		else {
			$child['children'] = array();
		}
		$children[] = $child;
	}
	return $children;
}

?>
