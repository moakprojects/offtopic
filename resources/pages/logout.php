<?php

    $_SESSION = array();
    if(isset($_COOKIE["usr"])) {
        setcookie("usr", "", time() - 60, "/");
    }

    session_destroy();

    header("Location: /home");
    exit;
?>