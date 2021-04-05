<?php

function update_n_descendants($conn, $id) {
	while (!is_null($id)) {
		include_once 'get_stmt_result.php';
		$n_desc = mysqli_fetch_array(get_stmt_result($conn, 'SELECT SUM(n_descendants + 1) FROM posts WHERE parent = ?', 'i', $id))[0];
		include_once 'execute_stmt.php';
		execute_stmt($conn, 'UPDATE posts SET n_descendants = ? WHERE id = ?', 'ii', $n_desc, $id);
		$parent = mysqli_fetch_array(get_stmt_result($conn, 'SELECT parent FROM posts WHERE id = ?', 'i', $id))['parent'];
		$id = $parent;
	}
}

?>
