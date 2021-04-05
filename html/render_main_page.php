<?php

$config = parse_ini_file('../config.ini');

$conn = mysqli_connect(
    $config['host'],
    $config['user'],
    $config['password'],
    $config['database'],
);
mysqli_set_charset($conn, 'utf8');

include_once 'get_stmt_result.php';

$posts = mysqli_query($conn, 'SELECT * FROM posts WHERE parent IS NULL ORDER BY id DESC');

while ($post = mysqli_fetch_array($posts)) {

	include_once 'render_secondary_post_thread.php';

	render_secondary_post_thread($conn, $post, false);
}
