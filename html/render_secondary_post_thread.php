<?php

function render_secondary_post_thread($conn, $post, $icu) {
	while ($post !== NULL) {
		$id = $post['id'];
		$parent = $post['parent'];

		include_once 'get_stmt_result.php';

		$parent_author = mysqli_fetch_array(
			get_stmt_result($conn, 'SELECT author FROM posts WHERE id = ?', 'i', $parent),
		)['author'];

		$n_children = mysqli_num_rows(mysqli_query($conn, 'SELECT 1 FROM posts WHERE parent = ' . $id));

		include_once 'render_secondary_post.php';

		render_secondary_post($post, $n_children, $parent_author, $icu, $n_children !== 0);

		include_once 'get_important_child.php';

		$post = get_important_child($conn, $id);
		$icu = true;
	}
}

?>
