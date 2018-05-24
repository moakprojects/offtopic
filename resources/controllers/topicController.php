<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/deletion.php";
include "../../database/modification.php";
include "../classes/Topic.php";
include "../classes/User.php";
$topicObj = new Topic();
$userObj = new User();

//depending on the action (add or remove) call the right function from persistence layer (what is add or remove the topic from favourites)
if(isset($_POST["favouriteSelectedTopic"])) {

    if($_POST["action"] === "add") {
        if($topicObj->likeTopic($_POST["userId"], $_POST["favouriteSelectedTopic"])) {

            //Celeb badge: id=9
            $hasCelebTopic = $userObj->getWellFollowedTopic($_POST["favouriteSelectedTopic"]);
            if(!$userObj->checkBadgeStatus($hasCelebTopic["createdBy"], 9)) {
                if($hasCelebTopic) {
                    if($userObj->uploadBadge($hasCelebTopic["createdBy"], 9)) {
                        $earnedBadges = $userObj->getAllBadges($hasCelebTopic["createdBy"]);

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
                                $userObject->increaseRankId($hasCelebTopic["createdBy"]);
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
                                $userObject->increaseRankId($hasCelebTopic["createdBy"]);
                            }
                        }
                    }
                }
            }

            $result["data_type"] = 1;
            $result["data_value"] = "added";
    
            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";
    
            echo json_encode($result);
        }
    } else {
        if($topicObj->dislikeTopic($_POST["userId"], $_POST["favouriteSelectedTopic"])) {
            $result["data_type"] = 1;
            $result["data_value"] = "removed";
    
            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";
    
            echo json_encode($result);
        }
    }
}

// when a user create a new topic we create unique attachment id and upload new topic
if(isset($_POST["createNewTopic"])) {
    $attachedFilesCode = "Att" . time();
    
    $newTopicName = htmlspecialchars(trim($_POST["newTopicName"]));

    $uploadInfo = $topicObj->uploadNewTopic($newTopicName, $_POST["newTopicDescription"], $_SESSION["user"]["userID"], $_POST["newTopicPeriod"], $_POST["newTopicCategory"], $attachedFilesCode);

    if($uploadInfo) {
        
        //create session variable for attachedfilecode to upload attached files information into database with this ID
        $_SESSION["newTopicAttachedFileCode"] = $attachedFilesCode;
        
        $result["data_type"] = 1;
        $result["data_value"] = $uploadInfo;

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured, please try again later";
        
        echo json_encode($result);
        exit;
    }
}

// if the user wants to upload attachment to the new topic we save this to the server, and the attachment informations into databse
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
        
        if($topicObj->uploadFiles($fileName, $displayedFileName, $_SESSION["newTopicAttachedFileCode"])) {
            copy($file["tmp_name"], $location);

        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
            exit;
        }
          
    }

    unset($_SESSION["newTopicAttachedFileCode"]);
    $result["data_type"] = 1;
    $result["data_value"] = "Success";

    echo json_encode($result);
    exit;
}

/* report topic controller */
if(isset($_POST["reportedTopic"])) {
    if($topicObj->reportTopic($_POST["reportedTopic"])) {
        $result["data_type"] = 1;
        $result["data_value"] = "reported";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* delete selected topic */
if(isset($_POST["deleteTopic"])) {
    if($topicObj->deleteTopic($_POST["topicID"])) {
        
        $result["data_type"] = 1;
        $result["data_value"] = "The selected topic was deleted";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* add the selected topic id - which the admin want to modify - to the session */
if(isset($_POST["modifyTopic"])) {
    $_SESSION["modifyTopicID"] = $_POST["topicID"];
    
    $result["data_type"] = 1;
    $result["data_value"] = "Success";
    echo json_encode($result);
}

/* get selected topic data from js */
if(isset($_POST["getSelectedTopicDataFromJs"])) {
    if($selectedTopic = $topicObj->getSelectedTopicBasicData($_SESSION["modifyTopicID"])) {
        if($attachedFiles = $topicObj->getAttachedFiles($selectedTopic["attachedFilesCode"])) {
            array_push($selectedTopic, $attachedFiles);
        }
        $result["data_type"] = 1;
        $result["data_value"] = $selectedTopic;

        echo json_encode($selectedTopic);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* modify selected data by admin */
if(isset($_POST["modifiedTopicData"])) {

    $modifiedTopicName = htmlspecialchars(trim($_POST["modifiedTopicName"]));

    if($topicObj->modifyTopicData($_POST["modifiedTopicID"], $modifiedTopicName, $_POST["modifiedTopicDescription"], $_POST["modifiedTopicCategory"], $_POST["modifiedTopicPeriod"])) {

        $removeAttachedFiles = json_decode( $_POST['removeAttachedFiles'] );
        if(count($removeAttachedFiles) > 0) {
            foreach($removeAttachedFiles as $file) {
                $topicObj->removeFiles($file->attachmentID);
                $location = "../../public/files/upload/" . $file->attachmentName;
                unlink($location);
            }
        }

        unset($_SESSION["modifyTopicID"]);

        $result["data_type"] = 1;
        $result["data_value"] = "Success";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}


?>