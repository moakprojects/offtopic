<?php
    /* upload profile image for the logged user */
    $profileImageUploadQuery = $db->prepare("UPDATE user SET profileImage = :profileImage WHERE userID = :userID");

    /* modify user's accesLevel after email verification */
    $modifyAccessLevelQuery = $db->prepare("UPDATE user SET accessLevel = 1 WHERE email = :email");

    /* increase the number of visitors */
    $increaseNumberOfVisitors = $db->prepare("UPDATE user SET visitors = visitors + 1 WHERE username = :username");

    /* change settings */
    $saveAccountDataQuery = $db->prepare("UPDATE user SET username = :username, email = :email, aboutMe = :aboutMe, birthdate = :birthdate, `location` = :location WHERE userID = :userID");

    /* change user ID to anonymous id after delete user */
    $modifyDeletedUserInTopic = $db->prepare("UPDATE topic SET createdBy = 39 WHERE createdBy = :userID");
    $modifyDeletedUserInPostLike = $db->prepare("UPDATE postlike SET userID = 39 WHERE userID = :userID");
    $modifyDeletedUserInPost = $db->prepare("UPDATE post SET userID = 39 WHERE userID = :userID");
    $modifyDeletedUserInFavouriteTopic = $db->prepare("UPDATE favouritetopic SET userID = 39 WHERE userID = :userID");
    $modifyDeletedUserInFavouriteCategory = $db->prepare("UPDATE favouritecategory SET userID = 39 WHERE userID = :userID");
?>