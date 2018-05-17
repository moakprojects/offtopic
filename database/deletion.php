<?php

/* remove the selected topic from favouriteTopic table */
$dislikeTopicQuery = $db->prepare("DELETE FROM favouritetopic WHERE userID = :userID AND topicID = :topicID");

/* remove the selected topic from favouriteTopic table */
$dislikeCategoryQuery = $db->prepare("DELETE FROM favouritecategory WHERE userID = :userID AND categoryID = :categoryID");

/* delete user from database */
$deleteUserQuery = $db->prepare("DELETE FROM user WHERE userID = :userID");

/* delete selected category from database by admin*/
$deleteCategoryQuery = $db->prepare("DELETE FROM category WHERE categoryID = :categoryID");
/* delete topics by category id from database by admin*/
$deleteTopicByCategoryQuery = $db->prepare("DELETE FROM topic WHERE categoryID = :categoryID");
/* delete posts by category id from database by admin*/
$deletePostByCategoryQuery = $db->prepare("DELETE FROM post WHERE topicID = (SELECT topicID FROM topic WHERE categoryID = :categoryID)");

/* delete selected topic from database by admin */
$deleteTopicQuery = $db->prepare("DELETE FROM topic WHERE topicID = :topicID");
/* delete posts by topic id from database by admin */
$deletePostByTopicQuery = $db->prepare("DELETE FROM post WHERE topicID = :topicID");

/* delete selected topic from database by admin*/
$deletePostQuery = $db->prepare("DELETE FROM post WHERE postID = :postID");

/* delete selected sticky from database by admin*/
$deleteStickyPostQuery = $db->prepare("DELETE FROM sidebarStickyPost WHERE stickyPostID = :stickyPostID");

/* delete user from earnedbadge after user delete his or her profile */
$deletedUserFromEarnedBadgeQuery = $db->prepare("DELETE FROM earnedbadge WHERE userID = :userID");