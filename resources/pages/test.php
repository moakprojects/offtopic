<?php

$stmt = $db -> prepare("SELECT * FROM user");
$stmt->execute();

$result = $stmt->fetchall(PDO::FETCH_ASSOC);

var_dump($result);

$valami = strlen("Backend assignment problem Backend assignment problem");
echo $valami;
?>