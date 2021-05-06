<?php

$config = parse_ini_file('../../config.ini');

$conn = mysqli_connect(
	$config['host'],
	$config['user'],
	$config['password'],
	$config['database'],
);
mysqli_set_charset($conn, 'utf8');

$tag = $_GET['tag'];

include_once '../get_stmt_result.php';

if (is_null($tag)) {
	$posts_result = mysqli_query(
		$conn,
		<<<SQL
		SELECT id, author, content, timestamp, n_children, n_descendants
		FROM posts
		WHERE parent IS NULL ORDER BY id DESC
		SQL,
	);
}
else {
	$posts_result = get_stmt_result(
		$conn,
		<<<SQL
		SELECT posts.id, posts.author, posts.content, posts.timestamp, posts.n_children, posts.n_descendants
		FROM posts
		JOIN post_tag ON posts.id = post_tag.post_id
		JOIN tags ON post_tag.tag_id = tags.id
		WHERE tags.name = ? AND parent IS NULL
		ORDER BY id DESC
		SQL,
		's',
		$tag,
	);
}

$posts = array();

include_once '../to_relative_time.php';
include_once '../get_stmt_result.php';
include_once '../read_children_or_thread.php';

while ($post = mysqli_fetch_assoc($posts_result)) {
	$post['timestamp'] = to_relative_time(strtotime($post['timestamp']));
	read_children_or_thread($conn, $post, 'n_descendants DESC');
	$posts[] = $post;
}

echo json_encode($posts);

?>
