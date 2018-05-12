<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/deletion.php";
include "../../database/modification.php";
include "../classes/Topic.php";
$topicObj = new Topic();

//depending on the action (add or remove) call the right function from persistence layer (what is add or remove the topic from favourites)
if(isset($_POST["favouriteSelectedTopic"])) {

    if($_POST["action"] === "add") {
        if($topicObj->likeTopic($_POST["userId"], $_POST["favouriteSelectedTopic"])) {
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

if(isset($_POST["createNewTopic"])) {
    $attachedFilesCode = "Att" . time();

    $uploadInfo = $topicObj->uploadNewTopic($_POST["newTopicName"], $_POST["newTopicDescription"], $_SESSION["user"]["userID"], $_POST["newTopicPeriod"], $_POST["newTopicCategory"], $attachedFilesCode);

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

?>