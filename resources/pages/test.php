<?php

$stmt = $db -> prepare("SELECT * FROM user");
$stmt->execute();

$result = $stmt->fetchall(PDO::FETCH_ASSOC);

var_dump($result);

$valami = strlen("Comment #01 Comment");
$v2 = strlen("Random string Random string Random string Random string Random string Random string Random string Random string Random string Random string Random string Random string Random string Random string Random string Random str");
echo $valami;
?>