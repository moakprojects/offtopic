<?php
    $likeQuery = $db->prepare("INSERT INTO `like` VALUES (:userID, :postID, :isLike, :isDislike)");

    $replyQuery = $db->prepare("INSERT INTO `post` VALUES (NULL, :text, :postedOn, :replyID, :userID, :topicID)");

    $fileUploadQuery = $db->prepare("INSERT INTO `attachment` VALUES (NULL, :postID, :attachmentName, :displayName)");

    /* create user into registration table */
    $createUserQuery = $db->prepare("INSERT INTO user VALUES (NULL, :email, :username, :passwordHash, NULL, 'defaultAvatar.png', 1, 0, :regDate)");
?>