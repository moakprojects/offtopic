<?php
$tomb = [];


$start    = (new DateTime(date('Y-m-d H:i:s', strtotime('-6 months'))))->modify('first day of this month');
$end      = (new DateTime('now'))->modify('first day of this month');
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);

var_dump($start);

foreach ($period as $dt) {
    array_push($tomb, $dt->format("M"));
}

$valami2 = date('Y-m-d H:i:s', strtotime('first day of january ' . date('Y')));
?>