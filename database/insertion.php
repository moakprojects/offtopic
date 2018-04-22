<?php
    /* put like/dislike information into postLike table */
    $likePostQuery = $db->prepare("INSERT INTO `postLike` VALUES (:userID, :postID, :isLike, :isDislike)");

    /* create post */
    $createPostQuery = $db->prepare("INSERT INTO `post` VALUES (NULL, :text, :postedOn, :replyID, :userID, :topicID, :attachedFilesCode)");

    /* upload file to database */
    $fileUploadQuery = $db->prepare("INSERT INTO `attachment` VALUES (NULL, :attachmentName, :displayName, :attachedFileCode)");

    /* create user into user table */
    $createUserQuery = $db->prepare("INSERT INTO user VALUES (NULL, :email, :username, :passwordHash, NULL, 'defaultAvatar.png', 1, 0, :regDate, 0)");

    /* put like information favouriteTopic table */
    $likeTopicQuery = $db->prepare("INSERT INTO `favouriteTopic` VALUES (:userID, :topicID)");

    /* put like information favouriteCategory table */
    $likeCategoryQuery = $db->prepare("INSERT INTO `favouriteCategory` VALUES (:userID, :categoryID)");
?>