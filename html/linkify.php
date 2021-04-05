<?php

function linkify($string) {
	return preg_replace(
		"~https?://[^<>[:space:]]+~",
		"<a class=\"post-link\" href=\"\\0\">\\0</a>",
		$string,
	);
}

function linkify_deco_only($string) {
	return preg_replace(
		"~https?://[^<>[:space:]]+~",
		"<span class=\"post-link\">\\0</span>",
		$string,
	);
}

?>
