<?php

$config = parse_ini_file('../../config.ini');

$conn = mysqli_connect(
	$config['host'],
	$config['user'],
	$config['password'],
	$config['database'],
);
mysqli_set_charset($conn, 'utf8');

include_once '../execute_stmt.php';

$tags = mysqli_query($conn, 'SELECT * FROM tags');

$result = array();

while ($tag = mysqli_fetch_array($tags)) {
	array_push($result, $tag['name']);
}

echo json_encode($result);

?>
