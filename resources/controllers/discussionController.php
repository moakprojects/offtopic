<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/deletion.php";
include "../../database/modification.php";
include "../classes/Post.php";
include "../classes/User.php";
include "../classes/Image.php";
$postObj = new Post();
$userObj = new User();
$imageObj = new Image();

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

        //Commentator badge: id=2
        if(!$userObj->checkBadgeStatus($_SESSION["user"]["userID"], 2)) {
            $numberOfPosts = $userObj->getNumberOfPosts($_SESSION["user"]["userID"]);
            if($numberOfPosts >= 10) {
                if($userObj->uploadBadge($_SESSION["user"]["userID"], 2)) {
                    $earnedBadges = $userObj->getAllBadges($_SESSION["user"]["userID"]);

                    if($loggedUserData["rankID"] == 3) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("3", $earnedBadge) &&
                            in_array("4", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    } else if($loggedUserData["rankID"] == 2) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    } else if($loggedUserData["rankID"] == 1) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    }
                }
            }
        }

        //Refiner badge: id=3
        if(!$userObj->checkBadgeStatus($_SESSION["user"]["userID"], 3)) {
            $numberOfPosts = $userObj->getNumberOfPosts($_SESSION["user"]["userID"]);
            if($numberOfPosts >= 50) {
                if($userObj->uploadBadge($_SESSION["user"]["userID"], 3)) {
                    $earnedBadges = $userObj->getAllBadges($_SESSION["user"]["userID"]);

                    if($loggedUserData["rankID"] == 3) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("3", $earnedBadge) &&
                            in_array("4", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    }
                }
            }
        }

        //Curious badge: id=5
        $receivedTopics = $userObj->getReceivedQuestions($_SESSION["selectedTopicID"]);
        if(!$userObj->checkBadgeStatus($receivedTopics["createdBy"], 5)) {
            if($receivedTopics["receivedQuestions"] >= 5) {
                if($userObj->uploadBadge($receivedTopics["createdBy"], 5)) {
                    $earnedBadges = $userObj->getAllBadges($receivedTopics["createdBy"]);

                    if($loggedUserData["rankID"] == 3) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("3", $earnedBadge) &&
                            in_array("4", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($receivedTopics["createdBy"]);
                        }
                    } else if($loggedUserData["rankID"] == 2) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($receivedTopics["createdBy"]);
                        }
                    } else if($loggedUserData["rankID"] == 1) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($receivedTopics["createdBy"]);
                        }
                    }
                }
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

    $newStickyName = htmlspecialchars(trim($_POST["newStickyName"]));
    $newStickyDescription = htmlspecialchars(trim($_POST["newStickyDescription"]));

    if($postObj->uploadnewStickyPost($newStickyName, $newStickyDescription)) {
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

        if($fileExtension[1] == 'jpg' || $fileExtension[1] == 'png' || $fileExtension[1] == 'jpeg') {
            $fileIsImage = true;
            
            $imageObj->load($file["tmp_name"]);
            $width = $imageObj->getWidth();
            $height = $imageObj->getHeight();

            if($width < $height) {
                $imageObj->resizeToHeight(400);
            } else {
                $imageObj->resizeToWidth(400);
            }
        } else {
            $fileIsImage = false;
        }

        $displayedFileName = $file["name"];
        if(strlen($file["name"]) > 38 - strlen($fileExtension[0])) {
            $cuttedFileName = substr($file["name"], 0, 39);
            $displayedFileName = $cuttedFileName . '....' . $fileExtension[1];
        }
        
        if($postObj->uploadFiles($fileName, $displayedFileName, $_SESSION["attachedFileCode"])) {
            if($fileIsImage) {
                $imageObj->save($location, 100);
            } else {
                copy($file["tmp_name"], $location);
            }
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

        //Supporter badge: id=7
        if(!$userObj->checkBadgeStatus($_SESSION["user"]["userID"], 7)) {
            if($like == 1) {
                if($userObj->uploadBadge($_SESSION["user"]["userID"], 7)) {
                    $earnedBadges = $userObj->getAllBadges($_SESSION["user"]["userID"]);

                    if($loggedUserData["rankID"] == 3) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("3", $earnedBadge) &&
                            in_array("4", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    } else if($loggedUserData["rankID"] == 2) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    } else if($loggedUserData["rankID"] == 1) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    }
                }
            }
        }

        //Critic badge: id=8
        if(!$userObj->checkBadgeStatus($_SESSION["user"]["userID"], 8)) {
            if($dislike == 1) {
                if($userObj->uploadBadge($_SESSION["user"]["userID"], 8)) {
                    $earnedBadges = $userObj->getAllBadges($_SESSION["user"]["userID"]);

                    if($loggedUserData["rankID"] == 3) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("3", $earnedBadge) &&
                            in_array("4", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    } else if($loggedUserData["rankID"] == 2) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    } else if($loggedUserData["rankID"] == 1) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($_SESSION["user"]["userID"]);
                        }
                    }
                }
            }
        }

        //reliable badge: id=10
        $wellLikedUser = $userObj->getWellLikedUser($_POST["postId"]);
        if(!$userObj->checkBadgeStatus($wellLikedUser["userID"], 10)) {
            if($wellLikedUser && $like == 1) {
                if($userObj->uploadBadge($wellLikedUser["userID"], 10)) {
                    $earnedBadges = $userObj->getAllBadges($wellLikedUser["userID"]);

                    if($loggedUserData["rankID"] == 3) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("3", $earnedBadge) &&
                            in_array("4", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($wellLikedUser["userID"]);
                        }
                    } else if($loggedUserData["rankID"] == 2) {
                        if(
                            in_array("1", $earnedBadge) &&
                            in_array("2", $earnedBadge) &&
                            in_array("5", $earnedBadge) &&
                            in_array("6", $earnedBadge) &&
                            in_array("7", $earnedBadge) &&
                            in_array("8", $earnedBadge) &&
                            in_array("9", $earnedBadge) &&
                            in_array("10", $earnedBadge)
                        ) {
                            $userObject->increaseRankId($wellLikedUser["userID"]);
                        }
                    }
                }
            }
        }

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

/* delete selected post */
if(isset($_POST["deletePost"])) {
    if($_POST["type"] === "post") {
        if($postObj->deletePost($_POST["postID"])) {
            
            $result["data_type"] = 1;
            $result["data_value"] = "The selected post was deleted";
    
            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";
    
            echo json_encode($result);
        }
    } else if ($_POST["type"] === "sticky") {
        if($postObj->deleteSticky($_POST["postID"])) {
        
            $result["data_type"] = 1;
            $result["data_value"] = "The selected sticky post was deleted";
    
            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";
    
            echo json_encode($result);
        }
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";
    
        echo json_encode($result);
    }
}

/* add the selected post id - which the admin want to modify - to the session */
if(isset($_POST["modifyPost"])) {
    if($_POST["type"] == "sticky") {
        $_SESSION["modifySidebarStickyID"] = $_POST["postID"];
    } else if($_POST["type"] == "post") {
        $_SESSION["modifyPostID"] = $_POST["postID"];
    }
    
    $result["data_type"] = 1;
    $result["data_value"] = "Success";
    echo json_encode($result);
}

/* modify selected stickyPost */
if(isset($_POST["modifiedStickyData"])) {

    $modifiedStickyName = htmlspecialchars(trim($_POST["modifiedStickyName"]));
    $modifiedStickyDescription = htmlspecialchars(trim($_POST["modifiedStickyDescription"]));

    if($postObj->modifySidebarSticky($_POST["modifiedStickyID"], $modifiedStickyName, $modifiedStickyDescription)) {
        $result["data_type"] = 1;
        $result["data_value"] = "Success";

        unset($_SESSION["modifySidebarStickyID"]);

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* modify selected post */
if(isset($_POST["modifiedPostData"])) {
    if($postObj->modifyPostData($_POST["modifiedPostID"], $_POST["modifiedPostText"])) {
        
        $removeAttachedFiles = json_decode( $_POST['removeAttachedFiles'] );
        if(count($removeAttachedFiles) > 0) {
            foreach($removeAttachedFiles as $file) {
                $postObj->removeFiles($file->postAttachmentID);
                $location = "../../public/files/upload/" . $file->postAttachmentName;
                unlink($location);
            }
        }
        
        unset($_SESSION["modifyPostID"]);

        $result["data_type"] = 1;
        $result["data_value"] = "Success";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* get selected post data from js */
if(isset($_POST["getSelectedPostData"])) {
    if($selectedPost = $postObj->getSelectedPostBasicData($_SESSION["modifyPostID"])) {
        if($attachedFiles = $postObj->getAttachedFiles($selectedPost["attachedFilesCode"])) {
            array_push($selectedPost, $attachedFiles);
        }
        $result["data_type"] = 1;
        $result["data_value"] = $selectedPost;

        echo json_encode($selectedPost);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

?>