<?php

function get_ancestors_recur($conn, $id) {
	$ancestors = array();
	while (!is_null($id)) {
		$parent = mysqli_fetch_array(get_stmt_result($conn, <<<SQL
			SELECT id, parent, author, content, timestamp
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
		$parent['timestamp'] = time_to_str(strtotime($parent['timestamp']));
		$ancestors[] = $parent;
		$id = $parent['parent'];
	}
	return $ancestors;
}

?>