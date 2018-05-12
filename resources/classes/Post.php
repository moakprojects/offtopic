<?php

class Post {

    private $isExistInPostLikeTable;

    function __get($var) {
        return $this->$var;
    }

    // request posts data from database
    function getPostsData($selectedTopicID) {
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

    // request all of the posts data from database
    function getAllPost() {
        global $db;
        global $allPostQuery;

        if($allPostQuery) {
            $allPostQuery->execute();

            $allPostData = $allPostQuery->fetchall(PDO::FETCH_ASSOC);
            
            return $allPostData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    // trim the long text depending on parameters
    function textTrimmer($longText, $length) {
        if(strlen($longText) > $length) {
                        
            return $cuttedTopicText = substr($longText, 0, $length) . "...";  
        } else {
            return $longText; 
        }
    }
    
    // get the latest 5 posts from database
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
            }

            return $latestPostsData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    // upload a new post into database
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

    // upload attached file information into database
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

    //request attached files information
    function getAttachedFiles($attachedFileCode) {
        
        global $db;
        global $attachedFilesQuery;
    
        if(isset($attachedFilesQuery)) {
            $attachedFilesQuery->bindParam(':attachedFileCode', $attachedFileCode);
            $attachedFilesQuery->execute();
    
            if($attachedFilesQuery->rowCount() > 0) {
                return $attachedFilesQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // modify topic table by isreported column
    function reportPost($postID) {
        global $db;
        global $reportPostQuery;

        try {
            $reportPostQuery->bindParam(':postID', $postID);
            $reportPostQuery->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // modify postlike table 
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

    // check that the user liked the post or not to change the appearance 
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

    // request the new value of numberOflikes/dislikes on changed post
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

    function getSelectedPost($selectedPost) {
        global $db;
        global $selectedPostsQuery;

        try {
            $selectedPostsQuery->bindParam(':postID', $selectedPost);
            $selectedPostsQuery->execute();

            if($selectedPostsQuery->rowCount() > 0) {
                return $selectedPostsQuery->fetch(PDO::FETCH_ASSOC);
            } else {
                header("Location: /error");
                exit;
            }
        } catch (PDOException $e) {
            header("Location: /error");
            exit;
        }
    }
}