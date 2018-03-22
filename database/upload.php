<?php
require "../config/connection.php";
require "selection.php";
if(isset($_FILES["file"])) {
    
    if(!($_FILES["file"]["error"] > 0)) 
    {
        uploadImage($_FILES["file"]["name"], $_FILES["file"]["tmp_name"]);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";
        echo json_encode($result);
        // TODO_pro: Security log (Here we get a server error what we would like to log)
    }

} else if(isset($_POST["avatarName"])) {
    
    $currentLocation = "../public/images/defaultAvatars/" . $_POST["avatarName"];
    uploadImage($_POST["avatarName"], $currentLocation);

} else {
    $result["data_type"] = 0;
    $result["data_value"] = "An error occured";
    echo json_encode($result);
    // TODO_pro: Security log (Here we get a server error what we would like to log)
}

function uploadImage($fileName, $tmpLocation) {

    global $avatarFileName;
    global $db;

    $imgName = time() . '_' . $fileName;
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

            copy($tmpLocation, $location);
            $result["data_type"] = 1;
            $result["data_value"] = $imgName;
        }
    }

    if(isset($previousLocation)) {
        unlink($previousLocation);
    }

    echo json_encode($result);
}
?>