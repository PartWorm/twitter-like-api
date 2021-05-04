<?php

$config = parse_ini_file('../../config.ini');

$conn = mysqli_connect(
	$config['host'],
	$config['user'],
	$config['password'],
	$config['database'],
);
mysqli_set_charset($conn, 'utf8');

$id = $_GET['id'];

include_once '../get_stmt_result.php';

$post = mysqli_fetch_array(get_stmt_result(
	$conn,
	<<<SQL
	SELECT id, parent, author, content, timestamp
	FROM posts
	WHERE id = ?
	SQL,
	'i',
	$id,
));

if (is_null($post)) {
	http_response_code(404);
	die();
}

include_once '../time_to_str.php';
include_once '../get_ancestors_recur.php';
include_once '../get_children_recur.php';

$post['ancestors'] = get_ancestors_recur($conn, $post['parent']);
$post['timestamp'] = time_to_str(strtotime($post['timestamp']));
$post['children'] = get_children_recur($conn, $post['id']);

echo json_encode($post);

?>
