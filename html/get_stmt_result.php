<?php

function get_stmt_result($conn, $query, $types, ...$values) {
	$stmt = $conn->prepare($query);
	$stmt->bind_param($types, ...$values);
	$stmt->execute();
	return $stmt->get_result();
}

?>
