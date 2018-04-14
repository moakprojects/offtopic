<?php
    /* put like/dislike information into postLike table */
    $likePostQuery = $db->prepare("INSERT INTO `postLike` VALUES (:userID, :postID, :isLike, :isDislike)");

    $createPostQuery = $db->prepare("INSERT INTO `post` VALUES (NULL, :text, :postedOn, :replyID, :userID, :topicID, :attachedFilesCode)");

    $fileUploadQuery = $db->prepare("INSERT INTO `attachment` VALUES (NULL, :attachmentName, :displayName, :attachedFileCode)");

    /* create user into registration table */
    $createUserQuery = $db->prepare("INSERT INTO user VALUES (NULL, :email, :username, :passwordHash, NULL, 'defaultAvatar.png', 1, 0, :regDate)");

    /* put like information favouriteTopic table */
    $likeTopicQuery = $db->prepare("INSERT INTO `favouriteTopic` VALUES (:userID, :topicID)");

    /* put like information favouriteCategory table */
    $likeCategoryQuery = $db->prepare("INSERT INTO `favouriteCategory` VALUES (:userID, :categoryID)");
?>