<?php

$regDate = new DateTime('2018-03-24 23:50:00');

$now = new DateTime('2018-03-30 19:35:15');

$most = new DateTime('now');

$diff = $regDate->diff($most);

$eltelt = $diff->h;

echo $diff -> format('%R%a days');

$hour = time("24:00:00");

if($regDate > time() - (24 * 60 * 60)) {
    echo "kisebb";
} else {
    echo "nagyobb";
}

?>