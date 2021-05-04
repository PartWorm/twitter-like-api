<?php

$config = parse_ini_file('../config.ini');

$conn = mysqli_connect(
    $config['host'],
    $config['user'],
    $config['password'],
    $config['database'],
);
mysqli_set_charset($conn, 'utf8');

$tags = mysqli_query($conn, 'SELECT * FROM tags');

while ($tag = mysqli_fetch_array($tags)) {
	echo
		'<a href="/?tag=' . $tag['name'] . '">' .
			'<div class="tag">' .
				$tag['name'] .
			'</div>' .
		'</a>';
}

?>