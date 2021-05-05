<?php

function get_ancestors_recur($conn, $id) {
	$ancestors = array();
	while (!is_null($id)) {
		$parent = mysqli_fetch_assoc(get_stmt_result($conn, <<<SQL
			SELECT id, parent, author, content, timestamp, n_children, n_descendants
			FROM posts
			WHERE id = ?
			SQL,
			'i',
			$id,
		));
		if (is_null($parent)) {
			http_response_code(404);
			die();
		}
		$parent['timestamp'] = to_relative_time(strtotime($parent['timestamp']));
		$ancestors[] = $parent;
		$id = $parent['parent'];
	}
	return $ancestors;
}

?>
