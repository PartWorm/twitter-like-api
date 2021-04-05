<?php

function time_to_str($time) {
	$now = time();
	$diff = $now - $time;

	if ($diff >= 604800) { // 일주일
		if (date('Y', $now) == date('Y', $time)) {
			return date('m월 d일', $time);
		}
		return date('Y년 m월 d일', $time);
	}
	
    $d[0] = array(1, "초 전");
    $d[1] = array(60, "분 전");
    $d[2] = array(3600, "시간 전");
    $d[3] = array(86400, "일 전");
    $d[4] = array(2592000, "달");
	$d[5] = array(31104000, "년");

	for ($i = 5; $i >= 0; --$i) {
		if ($diff >= $d[$i][0]) {
			return intval($diff / $d[$i][0]) . $d[$i][1];
			//return intval($diff / $d[$i][0]) . mb_substr($d[$i][1], 0, 1, 'utf-8');
		}
	}

	return '방금';
}

?>
