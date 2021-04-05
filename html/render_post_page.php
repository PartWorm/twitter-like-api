<?php

function trace_ancestors($conn, $id) {
	if ($id !== NULL) {
		$post = mysqli_fetch_array(mysqli_query($conn, 'SELECT * FROM posts WHERE id = ' . $id));

		$parent = $post['parent'];

		trace_ancestors($conn, $parent);

		$parent_author = mysqli_fetch_array(
			get_stmt_result($conn, 'SELECT author FROM posts WHERE id = ?', 'i', $parent),
		)['author'];

		$n_children = mysqli_num_rows(mysqli_query($conn, 'SELECT 1 FROM posts WHERE parent = ' . $id));

		render_secondary_post($post, $n_children, $parent_author, $parent !== NULL, true);
	}
}

function render_ancestor_list($conn, $id) {
	include_once 'get_stmt_result.php';

	$post = mysqli_fetch_array(get_stmt_result($conn, 'SELECT * FROM posts WHERE id = ?', 'i', $id));

	include_once 'render_secondary_post.php';

	trace_ancestors($conn, $post['parent']);
}

function render_post($conn, $id) {
	include_once 'get_stmt_result.php';

	$post = mysqli_fetch_array(get_stmt_result($conn, 'SELECT * FROM posts WHERE id = ?', 'i', $id));

	$parent = $post['parent'];

	$parent_author = mysqli_fetch_array(
		get_stmt_result($conn, 'SELECT author FROM posts WHERE id = ?', 'i', $parent),
	)['author'];

	$n_children = mysqli_num_rows(
		get_stmt_result($conn, 'SELECT 1 FROM posts WHERE parent = ?', 'i', $id),
	);

	include_once 'render_primary_post.php';

	render_primary_post($post, $n_children, $parent_author, $parent !== NULL, false);
}

function render_reply_list($conn, $id, $depth = 0) {
	include_once 'get_important_child.php';

	$important_child = get_important_child($conn, $id);

	if (false && $important_child !== NULL) {
		echo
			'<div>' .
				'<div class="post" onclick="const parent = this.parentElement; parent.classList.toggle(\'collapsed\');" style="display: flex">' .
					'<div class="post_branch post_branch_collapse_expand"></div>' .
					'<div class="post_body">' .
						'<div></div>' .
					'</div>' .
				'</div>' .
				'<div>';
	}

	include_once 'get_stmt_result.php';

	include_once 'render_secondary_post_thread.php';

	$replies = get_stmt_result(
		$conn,
		'SELECT * FROM posts WHERE parent = ? ORDER BY id = ? DESC, id ASC',
		'ii',
		$id,
		$important_child['id'],
	);

	while ($reply = mysqli_fetch_array($replies)) {
		render_secondary_post_thread($conn, $reply, false);
	}
}

function render_post_page($conn, $id) {
	if ($id <= 0) {
		return;
	}
	render_ancestor_list($conn, $id);
	render_post($conn, $id);
	render_reply_list($conn, $id);
}

$config = parse_ini_file('../config.ini');

$conn = mysqli_connect(
	$config['host'],
	$config['user'],
	$config['password'],
	$config['database'],
);
mysqli_set_charset($conn, 'utf8');

render_post_page($conn, $_GET['id']);
