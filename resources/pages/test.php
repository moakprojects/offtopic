<?php
$now = new DateTime('2018-05-10');
$now += 1;

$regDate = new DateTime('2018-05-11 10:00:00');


$diff = $regDate->diff($now);

$jj = "";