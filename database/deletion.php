<?php

/* remove the selected topic from favouriteTopic table */
$dislikeTopicQuery = $db->prepare("DELETE FROM favouriteTopic WHERE userID = :userID AND topicID = :topicID");

/* remove the selected topic from favouriteTopic table */
$dislikeCategoryQuery = $db->prepare("DELETE FROM favouriteCategory WHERE userID = :userID AND categoryID = :categoryID");