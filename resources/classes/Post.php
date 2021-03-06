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
            
            return $postQuery->fetchall(PDO::FETCH_ASSOC);
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
            return false;
        }
    }

    // request data from database based on postID
    function getSelectedPostBasicData($postID) {
        global $db;
        global $selectedPostBasicDataQuery;

        try {
            $selectedPostBasicDataQuery->bindParam(':postID', $postID);
            $selectedPostBasicDataQuery->execute();
            $selectedPostBasicData = $selectedPostBasicDataQuery->fetch(PDO::FETCH_ASSOC);
            
            if($selectedPostBasicData) {
                return $selectedPostBasicData;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            header("Location: /error");
            exit;
        }
    }

    /* modify the data of a selected post */
    function modifyPostData($postID, $text) {
        global $db;
        global $modifyPostQuery;

        if($modifyPostQuery) {

            $modifyPostQuery->bindParam(":postID", $postID);
            $modifyPostQuery->bindParam(":text", $text);
            $modifyPostQuery ->execute();

            return true;
        } else {
            return false;
        }
    }

    // request the sidebar sticky posts data from database
    function getAllSidebarSticky() {
        global $db;
        global $allSidebarStickyPostQuery;

        try {
            $allSidebarStickyPostQuery->execute();

            $allSidebarStickyPostData = $allSidebarStickyPostQuery->fetchall(PDO::FETCH_ASSOC);
            
            return $allSidebarStickyPostData;
        } catch(PDOException $e) {
            return false;
        }
    }

     // request the selected sidebar sticky posts data by its id from database
     function getSidebarSticky($stickyPostID) {
        global $db;
        global $sidebarStickyPostQuery;

        if($sidebarStickyPostQuery) {
            $sidebarStickyPostQuery->bindParam(':stickyPostID', $stickyPostID);
            $sidebarStickyPostQuery->execute();

            $sidebarStickyPostData = $sidebarStickyPostQuery->fetch(PDO::FETCH_ASSOC);
            
            return $sidebarStickyPostData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    /* modify the data of the selected sidebar sticky*/
    function modifySidebarSticky($stickyPostID, $stickyPostTitle, $stickyPostText) {
        global $db;
        global $modifySidebarStickyQuery;

        if($modifySidebarStickyQuery) {

            $modifySidebarStickyQuery->bindParam(":stickyPostID", $stickyPostID);
            $modifySidebarStickyQuery->bindParam(":stickyPostTitle", $stickyPostTitle);
            $modifySidebarStickyQuery->bindParam(":stickyPostText", $stickyPostText);
            $modifySidebarStickyQuery->execute();

            return true;
        } else {
            return false;
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

    /* remove attached file */
    function removeFiles($attachmentID) {

        global $db;
        global $deletePostAttachmentFileQuery;

        if(isset($deletePostAttachmentFileQuery)) {
            $deletePostAttachmentFileQuery->bindParam(':attachmentID', $attachmentID);
            $deletePostAttachmentFileQuery->execute();

            return true;
        } else {
            return false;
        }
    }
    
    // get the latest 5 posts from database
    function getLatestPosts() {
        global $db;
        global $latestPostsQuery;

        try {
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
        } catch(PDOException $e) {
            $valami = $e;
            return $e;
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
        global $postFileUploadQuery;

        if(isset($postFileUploadQuery)) {
            $postFileUploadQuery->bindParam(':attachmentName', $filename);
            $postFileUploadQuery->bindParam(':displayName', $displayname);
            $postFileUploadQuery->bindParam(':attachedFileCode', $attachedFileCode);

            $postFileUploadQuery->execute();

            return true;
        } else {
            return false;
        }
    }

    //request attached files information
    function getAttachedFiles($attachedFileCode) {
        
        global $db;
        global $getPostAttachedFilesQuery;
    
        if(isset($getPostAttachedFilesQuery)) {
            $getPostAttachedFilesQuery->bindParam(':attachedFileCode', $attachedFileCode);
            $getPostAttachedFilesQuery->execute();
    
            if($getPostAttachedFilesQuery->rowCount() > 0) {
                $attachedFiles = $getPostAttachedFilesQuery->fetchall(PDO::FETCH_ASSOC);
                return $attachedFiles;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // modify post table by isreported column
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

    // modify post table by isreported column
    function setSticky($postID, $value) {
        global $db;
        global $stickyPostQuery;

        try {
            $stickyPostQuery->bindParam(':value', $value);
            $stickyPostQuery->bindParam(':postID', $postID);
            $stickyPostQuery->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // add new sidebar sticky post
    function uploadnewStickyPost($postTitle, $postDescription) {
        global $db;
        global $newSidebarStickyQuery;

        try {
            $newSidebarStickyQuery->bindParam(':postTitle', $postTitle);
            $newSidebarStickyQuery->bindParam(':postDescription', $postDescription);
            $newSidebarStickyQuery->execute();

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
                $checkSpecificPostLikeValueResult = $checkSpecificPostLikeValueQuery->fetch(PDO::FETCH_ASSOC);
                return  $checkSpecificPostLikeValueResult;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // when the user click on the id of a post need to get the information of the selected post
    function getSelectedPost($selectedPost) {
        global $db;
        global $selectedPostQuery;

        try {
            $selectedPostQuery->bindParam(':postID', $selectedPost);
            $selectedPostQuery->execute();

            if($selectedPostQuery->rowCount() > 0) {

                $postData = $selectedPostQuery->fetch(PDO::FETCH_ASSOC);

                $postData["shortTopicName"] = $this->textTrimmer($postData["topicName"], 18);
                // it is important to close connection after stored procedures call
                $selectedPostQuery->closeCursor();
                return $postData; 
            } else {
                header("Location: /error");
                exit;
            }
        } catch (PDOException $e) {
            header("Location: /error");
            exit;
        }
    }

    /* get replyID for the displaying of the selected post */
    function getOriginalPost($topicID, $offset) {
        global $db;
        global $getOriginalPostQuery;

        try {
            $getOriginalPostQuery->bindParam(':topicID', $topicID);
            $getOriginalPostQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
            $getOriginalPostQuery->execute();

            $getOriginalPost = $getOriginalPostQuery->fetch(PDO::FETCH_ASSOC);
            $getOriginalPostQuery->closeCursor();
            return $getOriginalPost;
        } catch (PDOException $e) {
            return false;
        }
    }

    // delete selected post
    function deletePost($postID) {
        global $db;
        global $getOrderNumberQuery;
        global $modifyEqualReplyIDQuery;
        global $modifyLargerReplyIDQuery;
        global $deletePostQuery;
        global $deleteAttachmentByPostQuery;

        try {
            $db->beginTransaction();

            $getOrderNumberQuery->bindParam(':postID', $postID);
            $getOrderNumberQuery->execute();
            $orderNumber = $getOrderNumberQuery->fetch(PDO::FETCH_ASSOC);

            $modifyEqualReplyIDQuery->bindParam(':replyID', $orderNumber['orderNumberOfPost'], PDO::PARAM_INT);
            $modifyEqualReplyIDQuery->bindParam(':topicID', $orderNumber['topicID'], PDO::PARAM_INT);
            $modifyEqualReplyIDQuery->execute();

            $modifyLargerReplyIDQuery->bindParam(':replyID', $orderNumber['orderNumberOfPost'], PDO::PARAM_INT);
            $modifyLargerReplyIDQuery->bindParam(':topicID', $orderNumber['topicID'], PDO::PARAM_INT);
            $modifyLargerReplyIDQuery->execute();

            $deleteAttachmentByPostQuery->bindParam(':postID', $postID, PDO::PARAM_INT);
            $deleteAttachmentByPostQuery->execute();

            $deletePostQuery->bindParam(':postID', $postID);
            $deletePostQuery->execute();

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            return false;
        }
    }

    // delete selected sticky post
    function deleteSticky($postID) {
        global $db;
        global $deleteStickyPostQuery;

        try {
            $deleteStickyPostQuery->bindParam(':stickyPostID', $postID);
            $deleteStickyPostQuery->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    function getAllAttachedFiles() {
        global $db;
        global $getAllPostAttachedFilesQuery;

        try {
            $getAllPostAttachedFilesQuery->execute();

            if($getAllPostAttachedFilesQuery->rowCount() > 0) {
                return $getAllPostAttachedFilesQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    function getAttachmentImage($selectedID) {
        global $db;
        global $getPostAttachmentImage;

        try {
            $getPostAttachmentImage->bindParam(":selectedID", $selectedID);
            $getPostAttachmentImage->execute();

            return $getPostAttachmentImage->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    function deleteAttachmentImage($selectedID) {
        global $db;
        global $deletePostAttachmentQuery;

        try {
            $deletePostAttachmentQuery->bindParam(":selectedID", $selectedID);
            $deletePostAttachmentQuery->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    function searchInPosts($target) {
        global $db;
        global $searchInPostsQuery;

        try {
            $target = '%' . $target . '%'; 
            $searchInPostsQuery->bindParam(":target", $target, PDO::PARAM_STR);
            $searchInPostsQuery->execute();

            if($searchInPostsQuery->rowCount() > 0) {
                $resultPosts = $searchInPostsQuery->fetchall(PDO::FETCH_ASSOC);

                for($i = 0; $i < count($resultPosts); $i++) {
                    $resultPosts[$i]["text"] = $this->textTrimmer($resultPosts[$i]["text"], 400);
                }
                
                return $resultPosts;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            return $e;
        }
    }
}