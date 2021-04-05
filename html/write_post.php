<head>
	<meta charset="utf-8">
</head>
<?php

$parent = $_POST['parent'];
if ($parent == 'null') {
	$parent = NULL;
}
$author = $_POST['author'];
$password = $_POST['password'];
$content = $_POST['content'];

$config = parse_ini_file('../config.ini');

$conn = mysqli_connect(
	$config['host'],
	$config['user'],
	$config['password'],
	$config['database'],
);
mysqli_set_charset($conn, 'utf8');

if (is_null($parent)) {
	$parent_exists = false;
}
else {
	include_once 'get_stmt_result.php';
	$parent_exists = mysqli_num_rows(
		get_stmt_result($conn, 'SELECT 1 FROM posts WHERE id = ?', 's', $parent),
	) > 0;
}

if (empty($author) or empty($password) or empty($content)) {
}
else if ($parent_exists && $parent > 0 || is_null($parent)) {
	setcookie('author', $author, time() + 365 * 24 * 60 * 60);
	setcookie('password', $password, time() + 365 * 24 * 60 * 60);

	include_once 'execute_stmt.php';

	execute_stmt(
		$conn,
		'INSERT INTO posts (parent, author, password, content) VALUES (?, ?, ?, ?)',
		'ssss', $parent, $author, $password, $content,
	);

	include_once 'update_n_descendants.php';
	update_n_descendants($conn, $parent);
}

$prev_page = $_SERVER['HTTP_REFERER'];

header('location:' . $prev_page . ($parent !== NULL ? '#post' : ''));

?>
