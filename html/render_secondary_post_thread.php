<?php

function render_secondary_post_thread($conn, $post, $depth) {
	$connect_up = false;
	while ($post !== NULL) {

		$id = $post['id'];
		$parent = $post['parent'];

		include_once 'get_stmt_result.php';

		$n_children = mysqli_num_rows(mysqli_query($conn, 'SELECT 1 FROM posts WHERE parent = ' . $id));

		include_once 'render_secondary_post.php';

		render_secondary_post($post, $n_children, $depth, $connect_up, $n_children > 0);

		include_once 'get_important_child.php';

		$post = get_important_child($conn, $id);
		$connect_up = true;

	}
}

function render_secondary_post_tree($conn, $post, $depth = 0) {
	$id = $post['id'];
	$parent = $post['parent'];

	include_once 'get_stmt_result.php';

	$n_children = mysqli_num_rows(mysqli_query($conn, 'SELECT 1 FROM posts WHERE parent = ' . $id));

	include_once 'render_secondary_post.php';

	if ($depth < 4) {
		render_secondary_post($post, $n_children, $depth, false, false);

		$replies = get_stmt_result(
			$conn,
			'SELECT * FROM posts WHERE parent = ? ORDER BY id = ? DESC, id ASC',
			'ii',
			$id,
			$important_child['id'],
		);

		while ($reply = mysqli_fetch_array($replies)) {
			render_secondary_post_tree($conn, $reply, $depth + 1);
		}
	}
	else {
		render_secondary_post_thread($conn, $post, $depth);
	}

}

?>
