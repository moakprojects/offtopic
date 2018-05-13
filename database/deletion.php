<?php

/* remove the selected topic from favouriteTopic table */
$dislikeTopicQuery = $db->prepare("DELETE FROM favouritetopic WHERE userID = :userID AND topicID = :topicID");

/* remove the selected topic from favouriteTopic table */
$dislikeCategoryQuery = $db->prepare("DELETE FROM favouritecategory WHERE userID = :userID AND categoryID = :categoryID");

/* delete user from database */
$deleteUserQuery = $db->prepare("DELETE FROM user WHERE userID = :userID");

/* delete user from earnedbadge after user delete his or her profile */
$deletedUserFromEarnedBadgeQuery = $db->prepare("DELETE FROM earnedbadge WHERE userID = :userID");