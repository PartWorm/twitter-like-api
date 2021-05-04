<?php

$config = parse_ini_file('../config.ini');

$conn = mysqli_connect(
	$config['host'],
	$config['user'],
	$config['password'],
	$config['database'],
);
mysqli_set_charset($conn, 'utf8');

$tag = $_GET['tag'];

include_once 'get_stmt_result.php';

if (is_null($tag)) {
	$posts = mysqli_query($conn, 'SELECT * FROM posts WHERE parent IS NULL ORDER BY id DESC');
}
else {
	$posts = get_stmt_result(
		$conn,
		'SELECT posts.* FROM posts ' .
		'JOIN post_tag ON posts.id = post_tag.post_id ' .
		'JOIN tags ON post_tag.tag_id = tags.id ' .
		'WHERE tags.name = ? AND parent IS NULL ' .
		'ORDER BY id DESC',
		's',
		$tag,
	);
}

while ($post = mysqli_fetch_array($posts)) {

	include_once 'render_secondary_post_thread.php';

	render_secondary_post_tree($conn, $post, false);
}
