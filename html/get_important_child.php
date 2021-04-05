<?php

include_once 'get_stmt_result.php';

function get_important_child($conn, $id) {
	return mysqli_fetch_array(get_stmt_result(
		$conn,
		'SELECT * FROM posts WHERE parent = ? ORDER BY n_descendants DESC LIMIT 1',
		'i', $id,
	));
}

?>
