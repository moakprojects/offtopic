<?php
    /* put like/dislike information into postLike table */
    $likePostQuery = $db->prepare("INSERT INTO `postlike` VALUES (:userID, :postID, :isLike, :isDislike)");

    /* create post */
    $createPostQuery = $db->prepare("INSERT INTO `post` VALUES (NULL, :text, :postedOn, :replyID, :userID, :topicID, :attachedFilesCode, 0, 0)");

    /* upload file to database */
    $fileUploadQuery = $db->prepare("INSERT INTO `attachment` VALUES (NULL, :attachmentName, :displayName, :attachedFileCode)");

    /* create user into user table */
    $createUserQuery = $db->prepare("INSERT INTO user VALUES (NULL, :email, :username, :passwordHash, NULL, 'defaultAvatar.png', 1, 0, :regDate, 0, NULL, NULL, NULL, 0)");

    /* put like information favouriteTopic table */
    $likeTopicQuery = $db->prepare("INSERT INTO `favouritetopic` VALUES (:userID, :topicID)");

    /* put like information favouriteCategory table */
    $likeCategoryQuery = $db->prepare("INSERT INTO `favouritecategory` VALUES (:userID, :categoryID)");

    /* upload new topic into database */
    $newTopicQuery = $db->prepare("INSERT INTO topic VALUES (NULL, :topicName, :topicText, :createdAt, :createdBy, :period, :category, :attachedFilesCode, 0)");

    /* if the user reach the required condition for a badge we store this information in database */
    $newBadgeQuery = $db->prepare("INSERT INTO earnedBadge VALUES (:userID, :badgeID)");

    /* upload new sidebar sticky post into database */
    $newSidebarStickyQuery = $db->prepare("INSERT INTO sidebarStickyPost VALUES (NULL, :postTitle, :postDescription)");

    /* upload new category into database */
    $newCategoryQuery = $db->prepare("INSERT INTO category VALUES (NULL, :categoryName, :categoryDescription, :thumbnail)");
?>