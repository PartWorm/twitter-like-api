<?php

function ip_to_name() {
	$ip = $_SERVER['REMOTE_ADDR'];
	return substr(md5($ip), 0, 8);
}

?>
