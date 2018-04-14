<?php

class Post {

    private $isExistInPostLikeTable;

    function __construct() {
        
    }

    function __get($var) {
        return $this->$var;
    }

    function getPostData($selectedTopicID) {
        global $db;
        global $postQuery;

        if($postQuery) {
            $selectedTopicID = htmlspecialchars(trim($selectedTopicID));
            $postQuery -> bindParam(":topicID", $selectedTopicID);
            $postQuery->execute();

            $postData = $postQuery->fetchall(PDO::FETCH_ASSOC);
            
            return $postData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    function textTrimmer($longText, $length) {
        if(strlen($longText) > $length) {
                        
            return $cuttedTopicText = substr($longText, 0, $length) . "...";  
        } else {
            return $longText; 
        }
    }
    
    function getLatestPosts() {
        global $db;
        global $latestPostsQuery;

        if($latestPostsQuery) {
            $latestPostsQuery->execute();

            $latestPostsData = $latestPostsQuery->fetchall(PDO::FETCH_ASSOC);
            
            for($i = 0; $i < count($latestPostsData); $i++) {

                $latestPostsData[$i]["shortTopicName"] = $this->textTrimmer($latestPostsData[$i]["topicName"], 18); 
                $latestPostsData[$i]["shortPostText"] = $this->textTrimmer($latestPostsData[$i]["text"], 218); 

                $postedOn = new DateTime($latestPostsData[$i]["postedOn"]);
                $latestPostsData[$i]["monthDay"] = $postedOn -> format('M, j') . "<sup>" . $postedOn -> format('S') . "</sup>";
                $latestPostsData[$i]["time"] = $postedOn -> format('h:ia');
                $valami = "";
            }

            return $latestPostsData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    function createPost($content, $replyID, $userID, $topicID, $attachedFilesCode) {

        global $db;
        global $createPostQuery;

        if(isset($createPostQuery)) {

            /* here we don't have to sanitize the data because Quill js already did that */ 
            $createPostQuery->bindParam(':text', $content);
            $postedOn = date("Y-m-d H:i:s");
            $createPostQuery->bindParam(':postedOn', $postedOn);
            $createPostQuery->bindParam(':replyID', $replyID);
            $createPostQuery->bindParam(':userID', $userID);
            $createPostQuery->bindParam(':topicID', $topicID);
            $createPostQuery->bindParam(':attachedFilesCode', $attachedFilesCode);

            $createPostQuery->execute();

            return true;
        } else {
            return false;
        }
    }

    function uploadFiles($filename, $displayname, $attachedFileCode) {

        global $db;
        global $fileUploadQuery;

        if(isset($fileUploadQuery)) {
            $fileUploadQuery->bindParam(':attachmentName', $filename);
            $fileUploadQuery->bindParam(':displayName', $displayname);
            $fileUploadQuery->bindParam(':attachedFileCode', $attachedFileCode);

            $fileUploadQuery->execute();

            return true;
        } else {
            return false;
        }
    }

    function getAttachedFiles($attachedFileCode) {
        
        global $db;
        global $attachedFilesQuery;
    
        if(isset($attachedFilesQuery)) {
            $attachedFilesQuery->bindParam(':attachedFileCode', $attachedFileCode);
            $attachedFilesQuery->execute();
    
            return $attachedFilesResult = $attachedFilesQuery->fetchall(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    function likePost($userID, $postID, $like, $dislike) {
            
        global $db;
        global $likePostQuery;

        if(isset($likePostQuery)) {
            $likePostQuery->bindParam(':userID', $userID);
            $likePostQuery->bindParam(':postID', $postID);
            $likePostQuery->bindParam(':isLike', $like);
            $likePostQuery->bindParam(':isDislike', $dislike);

            $likePostQuery->execute();

            return true;
        } else {
            return false;
        }
    }

    function checkPostLikeStatus($userID, $postID) {
        
        global $db;
        global $checkPostLikeQuery;

        $checkPostLikeQuery->bindParam(':userID', $userID);
        $checkPostLikeQuery->bindParam(':postID', $postID);
        $checkPostLikeQuery->execute();
        
        if ($checkPostLikeQuery->rowCount() > 0) {
            $this->isExistInPostLikeTable = true; 
        } else {
            $this->isExistInPostLikeTable = false;
        }
    }

    function refreshPostLikeValues($postID) {

        global $db;
        global $checkSpecificPostLikeValueQuery;

        if(isset($checkSpecificPostLikeValueQuery)) {

            $checkSpecificPostLikeValueQuery->bindParam(':postID', $postID);
            $checkSpecificPostLikeValueQuery->execute();

            if($checkSpecificPostLikeValueQuery->rowCount() > 0) {
                return $checkSpecificPostLikeValueResult = $checkSpecificPostLikeValueQuery->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}