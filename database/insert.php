<?php
    $likeQuery = $db->prepare("INSERT INTO `like` VALUES (:userID, :postID, :isLike, :isDislike)");

    $replyQuery = $db->prepare("INSERT INTO `post` VALUES (NULL, :text, :postedOn, :replyID, :userID, :topicID)");

    $fileUploadQuery = $db->prepare("INSERT INTO `attachment` VALUES (NULL, :postID, :attachmentName)");
?>