<?php
require ("constants.php");
try {
    $db = new PDO(DSN, DB_USER, DB_PASS, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
}
catch(PDOException $err) {
    echo "Connection error: " . $err -> getMessage();
}