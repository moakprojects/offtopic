<?php

$email = "ezamail@gmail.com";
$saltText = "ActivationHashString";
$concat = $email . $saltText;
$activationCode = md5($concat, false);

if(isset($_SESSION["verify_token"])) {

    if($_SESSION["verify_token"] == md5($concat, false)) {
        echo "Juhééééé";
    } else {
        echo "Nem juhé hanem a bárányé";
    }

    unset($_SESSION["verify_token"]);
}

?>