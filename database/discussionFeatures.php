<?php

    if(isset($_POST["postId"])) {
        require "../config/connection.php";
        require "insert.php";
        if($likeQuery) {

            if($_POST["mood"] == "like") {
                $like = 1;
                $dislike = 0;
            } else {
                $like = 0;
                $dislike = 1;
            }

            $likeQuery->bindParam(':userID', $_POST["userId"]);
            $likeQuery->bindParam(':postID', $_POST["postId"]);
            $likeQuery->bindParam(':isLike', $like);
            $likeQuery->bindParam(':isDislike', $dislike);

            $likeQuery->execute();

            echo "Continue";
        } else {
            $result = "A like query szar";
            echo $result;    
        }
    }

    if(isset($_POST["reCount"])) {
        require "../config/connection.php";
        require "selection.php";
        echo countLikes($_POST["reCount"], $_POST["mood"]);
    }

    function countLikes($postID, $mood) {
        global $numberOfLikesQuery;
        global $numberOfDislikesQuery;

        if($mood == "like") {
            $numberOfLikesQuery->bindParam(':postID', $postID);
            $numberOfLikesQuery->execute();
            $numberOfLikesResult = $numberOfLikesQuery->fetch(PDO::FETCH_ASSOC);
            
            echo $numberOfLikesResult["count"];
        } else {
            $numberOfDislikesQuery->bindParam(':postID', $postID);
            $numberOfDislikesQuery->execute();
            $numberOfDislikesResult = $numberOfDislikesQuery->fetch(PDO::FETCH_ASSOC);
            
            echo $numberOfDislikesResult["count"];
        }
    }

    function checkPostStatus($userID, $postID) {
        global $inLikeTableQuery;

        $inLikeTableQuery->bindParam(':userID', $userID);
        $inLikeTableQuery->bindParam(':postID', $postID);
        $inLikeTableQuery->execute();
        $inLikeTableResult = $inLikeTableQuery->rowCount();

        return $inLikeTableResult;
    }

    if(isset($_POST["replyContent"])) {
        require "../config/connection.php";
        require "insert.php";
        //$text = htmlspecialchars(trim($_POST["replyContent"]));
        $replyQuery->bindParam(':text', $_POST["replyContent"]);
        $postedOn = date("Y-m-d H:i:s");
        $replyQuery->bindParam(':postedOn', $postedOn);
        $replyID = NULL;
        $replyQuery->bindParam(':replyID', $replyID);
        $userID = 1;
        $replyQuery->bindParam(':userID', $userID);
        $topicID = 1;
        $replyQuery->bindParam(':topicID', $topicID);

        $replyQuery->execute();

        header("Location: /discussion");
    }

    if(count($_FILES) > 0) {
        require "../config/connection.php";
        require "insert.php";

        if(!$fileUploadQuery) {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";
        } else {
            foreach($_FILES as $file) {
                $fileName = time() . '_' . $file["name"];
                $fileExtension = explode(".", $file["name"]);
                $location = "../public/files/upload/" . $fileName;
    
                $displayedFileName = $file["name"];
                if(strlen($file["name"]) > 38 - strlen($fileExtension[0])) {
                  $cuttedFileName = substr($file["name"], 0, 39);
                  $displayedFileName = $cuttedFileName . '....' . $fileExtension[1];
                }
                $postID = 49;
                $fileUploadQuery->bindParam(':postID', $postID);
                $fileUploadQuery->bindParam(':attachmentName', $fileName);
                $fileUploadQuery->bindParam(':displayName', $displayedFileName);

                $fileUploadQuery->execute();
        
                copy($file["tmp_name"], $location);   
            }
            $result["data_type"] = 1;
            $result["data_value"] = "Continue";
        }

        echo json_encode($result);
    }

    function getAttachFiles($postID) {
        
        global $attachFilesQuery;
        global $attachFilesResult;

        if($attachFilesQuery) {
            $attachFilesQuery->bindParam(':postID', $postID);
            $attachFilesQuery->execute();

            $attachFilesResult = array();
            while($row = $attachFilesQuery->fetch(PDO::FETCH_ASSOC)) {
                array_push($attachFilesResult, $row);
            }
        }
    }
?>