<?php
    /* upload profile image for the logged user */
    $profileImageUploadQuery = $db->prepare("UPDATE user SET profileImage = :profileImage WHERE userID = :userID");

    /* modify user's accesLevel after email verification */
    $modifyAccessLevelQuery = $db->prepare("UPDATE user SET accessLevel = 1 WHERE email = :email");

    /* increase the number of visitors */
    $increaseNumberOfVisitorsQuery = $db->prepare("UPDATE user SET visitors = visitors + 1 WHERE username = :username");

    /* change settings */
    $saveAccountDataQuery = $db->prepare("UPDATE user SET username = :username, email = :email, aboutMe = :aboutMe, birthdate = :birthdate, `location` = :location WHERE userID = :userID");

    /* change user ID to anonymous id after delete user */
    $modifyDeletedUserInTopicQuery = $db->prepare("UPDATE topic SET createdBy = 39 WHERE createdBy = :userID");
    $modifyDeletedUserInPostLikeQuery = $db->prepare("UPDATE postlike SET userID = 39 WHERE userID = :userID");
    $modifyDeletedUserInPostQuery = $db->prepare("UPDATE post SET userID = 39 WHERE userID = :userID");
    $modifyDeletedUserInFavouriteTopicQuery = $db->prepare("UPDATE favouritetopic SET userID = 39 WHERE userID = :userID");
    $modifyDeletedUserInFavouriteCategoryQuery = $db->prepare("UPDATE favouritecategory SET userID = 39 WHERE userID = :userID");

    /* save last login date into database */
    $saveLastLoginQuery = $db->prepare("UPDATE user SET lastLoginDate = :lastLoginDate WHERE userID = :userID");

    /* change isReported column of a topic*/
    $reportTopicQuery = $db->prepare("UPDATE topic SET isReported = 1 WHERE topicID = :topicID");

    /* change isReported column of a post*/
    $reportPostQuery = $db->prepare("UPDATE post SET isReported = 1 WHERE postID = :postID");

    /* change isSticky column of a post*/
    $stickyPostQuery = $db->prepare("UPDATE post SET isSticky = :value WHERE postID = :postID");
    /* increase number of consecutive logins */
    $increaseNumberOfConsecutiveVisitQuery = $db->prepare("UPDATE user SET consecutiveVisit = consecutiveVisit + 1 WHERE userID = :userID");

    /* delet consecutive logins */
    $clearNumberOfConsecutiveVisitQuery = $db->prepare("UPDATE user SET consecutiveVisit = 0 WHERE userID = :userID");
?>