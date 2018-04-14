<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/modification.php";
include "../classes/Post.php";
$postObj = new Post();

if(isset($_POST["replyContent"])) {

    $attachedFileCode = "Att" . time();
    
    $replyID = null;
    if($_POST["replyID"] > 0) {
        $replyID = $_POST["replyID"];
    }
    
    if($postObj->createPost($_POST["replyContent"], $replyID, $_SESSION["user"]["userID"], $_SESSION["selectedTopicID"], $attachedFileCode)) {
        
        $_SESSION["attachedFileCode"] = $attachedFileCode;
        
        $result["data_type"] = 1;
        $result["data_value"] = $_SESSION["selectedTopicID"];

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";
        
        echo json_encode($result);
        exit;
    }
}

if(count($_FILES) > 0) {

    foreach($_FILES as $file) {
        
        $fileName = time() . '_' . $file["name"];
        $fileExtension = explode(".", $file["name"]);
        $location = "../../public/files/upload/" . $fileName;

        $displayedFileName = $file["name"];
        if(strlen($file["name"]) > 38 - strlen($fileExtension[0])) {
            $cuttedFileName = substr($file["name"], 0, 39);
            $displayedFileName = $cuttedFileName . '....' . $fileExtension[1];
        }
        
        if($postObj->uploadFiles($fileName, $displayedFileName, $_SESSION["attachedFileCode"])) {
            copy($file["tmp_name"], $location);

        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
            exit;
        }
          
    }

    unset($_SESSION["attachedFileCode"]);
    $result["data_type"] = 1;
    $result["data_value"] = "Success";

    echo json_encode($result);
    exit;
}

/* if the user like one of the posts we send postid and the "mood" if the gesture is like or dislike*/
if(isset($_POST["postId"])) {
    if($_POST["mood"] == "like") {
        $like = 1;
        $dislike = 0;
    } else {
        $like = 0;
        $dislike = 1;
    }

    if($postObj->likePost($_SESSION["user"]["userID"], $_POST["postId"], $like, $dislike)) {
        $result["data_type"] = 1;
        $result["data_value"] = "Success";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

if(isset($_POST["reCountID"])) {

    $postLikeValue = $postObj->refreshPostLikeValues($_POST["reCountID"]);
    if($postLikeValue) {
        $result["data_type"] = 1;
        $result["data_value"] = $postLikeValue;

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

?>