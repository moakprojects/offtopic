<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/modification.php";
include "../classes/Post.php";
include "../classes/User.php";
$postObj = new Post();
$userObj = new User();

// if the user send a post call createpost from persistence layer
if(isset($_POST["replyContent"])) {

    $attachedFileCode = "Att" . time();
    
    //if the user reply for a specific comment, then set the id of the original post as replyID, otherwise the replyID is null
    $replyID = null;
    if($_POST["replyID"] > 0) {
        $replyID = $_POST["replyID"];
    }
    
    if($postObj->createPost($_POST["replyContent"], $replyID, $_SESSION["user"]["userID"], $_SESSION["selectedTopicID"], $attachedFileCode)) {
        
        //create session variable for attachedfilecode to upload attached files information into database with this ID
        $_SESSION["attachedFileCode"] = $attachedFileCode;

        if(!$userObj->checkBadgeStatus($_SESSION["user"]["userID"], 2)) {
            $numberOfPosts = $userObj->getNumberOfPosts($_SESSION["user"]["userID"]);
            if($numberOfPosts >= 10) {
                $userObj->uploadBadge($_SESSION["user"]["userID"], 2);
            }
        }

        if(!$userObj->checkBadgeStatus($_SESSION["user"]["userID"], 3)) {
            $numberOfPosts = $userObj->getNumberOfPosts($_SESSION["user"]["userID"]);
            if($numberOfPosts >= 50) {
                $userObj->uploadBadge($_SESSION["user"]["userID"], 3);
            }
        }
        
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

/* report post controller */
if(isset($_POST["reportedPost"])) {
    if($postObj->reportPost($_POST["reportedPost"])) {
        $result["data_type"] = 1;
        $result["data_value"] = "reported";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* set the selected post sticky */
if(isset($_POST["stickyID"])) {
    $value = 0;

    if($_POST["type"] == "sticky") {
        $value = 1;
    } else if($_POST["type"] == "unsticky") {
        $value = 0;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }

    if($postObj->setSticky($_POST["stickyID"], $value)) {
        $result["data_type"] = 1;
        $result["data_value"] = "set to sticky";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* upload new sidebar sticky post */
if(isset($_POST["createNewSticky"])) {
    if($postObj->uploadnewStickyPost($_POST["newStickyName"], $_POST["newStickyDescription"])) {
        $result["data_type"] = 1;
        $result["data_value"] = "New sticky post was created";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

// if the user wants to upload files we upload the files information to the database with the attachedfilecode what the user got when the postupload function ran, then we copy the file to the storage
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

/* if the user like one of the posts we send postid and the "mood" to the persistence layer depends on the gesture is like or dislike*/
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

// if the user like or dislike a post we recount the numberOfLikes or dislikes and for it we call the refreshPostLikeValues from persistence layer
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