<?php
require "../config/connection.php";
require "selection.php";
if(!($_FILES["file"]["error"] > 0)) 
{
    $imgName = time() . '_' . $_FILES["file"]["name"];
    $location = "../public/images/upload/" . $imgName;

    if($avatarFileName != "defaultAvatar.png") {
        $previousLocation = "../public/images/upload/" . $avatarFileName;
    }

    if(!($stmt = $db->prepare("UPDATE user SET profileImage = ? WHERE userID = 1"))) {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";
    } else {
        if(!$stmt->bindParam(1, $imgName)) {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";
        } else {
            $stmt->execute();

            copy($_FILES["file"]["tmp_name"], $location);
            $result["data_type"] = 1;
            $result["data_value"] = $imgName;
        }
    }

    if(isset($previousLocation)) {
        unlink($previousLocation);
    }

    echo json_encode($result);
} else {
    $result["data_type"] = 0;
    $result["data_value"] = "An error occured";
    echo json_encode($result);
    // TODO_pro: Security log (Here we get a server error what we would like to log)
}
?>