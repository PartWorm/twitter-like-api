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
$sort_by = $_GET['sort-by'];

if ($sort_by == 'hot') {
	$order_by = 'n_descendants DESC';
}
else if ($sort_by == 'old') {
	$order_by = 'timestamp ASC';
}
else {
	$order_by = 'timestamp DESC';
}

include_once '../get_stmt_result.php';

$post = mysqli_fetch_assoc(get_stmt_result(
	$conn,
	<<<SQL
	SELECT id, parent, author, content, timestamp, n_children, n_descendants
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

include_once '../to_relative_time.php';
include_once '../get_ancestors_recur.php';
include_once '../read_children_or_thread.php';

$post['ancestors'] = get_ancestors_recur($conn, $post['parent']);
$post['timestamp'] = to_relative_time(strtotime($post['timestamp']));
read_children_or_thread($conn, $post, $order_by);

echo json_encode($post);

?>
