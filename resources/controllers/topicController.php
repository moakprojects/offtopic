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

?>