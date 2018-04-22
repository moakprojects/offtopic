<?php

/* remove the selected topic from favouriteTopic table */
$dislikeTopicQuery = $db->prepare("DELETE FROM favouritetopic WHERE userID = :userID AND topicID = :topicID");

/* remove the selected topic from favouriteTopic table */
$dislikeCategoryQuery = $db->prepare("DELETE FROM favouritecategory WHERE userID = :userID AND categoryID = :categoryID");