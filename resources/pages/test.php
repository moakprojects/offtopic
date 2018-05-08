<?php

$month = 2;
$day = 9;

$m = date("F", $month);
$d = date("j", $day);

$monthName = date('F', mktime(0, 0, 0, $month, 10));
$monthName = date('d', mktime(0, 0, 0, 0, $day));

$date = $m . "-" . $d;

$jj = "";