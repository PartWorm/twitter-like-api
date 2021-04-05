<?php

function execute_stmt($conn, $query, $types, ...$values) {
	$stmt = $conn->prepare($query);
	$stmt->bind_param($types, ...$values);
	$stmt->execute();
}

?>
