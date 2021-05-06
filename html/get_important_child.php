<?php

include_once 'get_stmt_result.php';

function get_important_child($conn, $id, $order_by = 'n_descendants DESC') {
	return mysqli_fetch_assoc(get_stmt_result(
		$conn,
		<<<SQL
		SELECT id, author, content, timestamp, n_children, n_descendants
		FROM posts
		WHERE parent = ?
		ORDER BY 
		SQL . $order_by . ' LIMIT 1',
		'i', $id,
	));
}

?>
