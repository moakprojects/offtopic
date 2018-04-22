<?php
    //if the user wants to logout we destroy the session and delete all set user cookies
    if(isset($_POST["logout"]) && $_POST["logout"] === "true") {
        session_start();
        
        $_SESSION = array();
        if(isset($_COOKIE["usr"])) {
            setcookie("usr", "", time() - 60, "/");
        }

        session_destroy();
    }
?>