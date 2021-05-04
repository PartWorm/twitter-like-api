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

/*
echo json_encode(array(array(
	'author' => '',
	'timestamp' => '',
	'content' => $_SERVER['REQUEST_URI'],
	'children' => array(),
)));
*/

if (is_null($tag)) {
	$posts_result = mysqli_query(
		$conn,
		<<<SQL
		SELECT id, author, content, timestamp
		FROM posts
		WHERE parent IS NULL ORDER BY id DESC
		SQL,
	);
}
else {
	$posts_result = get_stmt_result(
		$conn,
		<<<SQL
		SELECT posts.id, posts.author, posts.content, posts.timestamp
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

include_once '../time_to_str.php';
include_once '../get_stmt_result.php';
include_once '../get_children_recur.php';

while ($post = mysqli_fetch_assoc($posts_result)) {
	$post['timestamp'] = time_to_str(strtotime($post['timestamp']));
	$post['children'] = get_children_recur($conn, $post['id']);
	$posts[] = $post;
}

echo json_encode($posts);

?>
