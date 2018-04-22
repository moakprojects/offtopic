<?php
    /* upload profile image for the logged user */
    $profileImageUploadQuery = $db->prepare("UPDATE user SET profileImage = :profileImage WHERE userID = :userID");

    /* modify user's accesLevel after email verification */
    $modifyAccessLevelQuery = $db->prepare("UPDATE user SET accessLevel = 1 WHERE email = :email");

    /* increase the number of visitors */
    $increaseNumberOfVisitors = $db->prepare("UPDATE user SET visitors = visitors + 1 WHERE username = :username")
?>