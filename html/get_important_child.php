<?php

include_once 'get_stmt_result.php';

function get_important_child($conn, $id) {
	return mysqli_fetch_assoc(get_stmt_result(
		$conn,
		<<<SQL
		SELECT id, author, content, timestamp, n_children, n_descendants
		FROM posts
		WHERE parent = ?
		ORDER BY n_descendants DESC LIMIT 1
		SQL,
		'i', $id,
	));
}

?>
