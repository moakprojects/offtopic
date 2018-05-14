<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/deletion.php";
include "../../database/modification.php";
include "../classes/Category.php";
$categoryObj = new Category();

//depending on the action (add or remove) call the right function from persistence layer (what is add or remove the category from favourites)
if(isset($_POST["favouriteSelectedCategory"])) {

    if($_POST["action"] === "add") {
        if($categoryObj->likeCategory($_POST["userId"], $_POST["favouriteSelectedCategory"])) {
            $result["data_type"] = 1;
            $result["data_value"] = "added";
    
            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";
    
            echo json_encode($result);
        }
    } else {
        if($categoryObj->dislikeCategory($_POST["userId"], $_POST["favouriteSelectedCategory"])) {
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

/* upload new category */
if(isset($_POST["createNewCategory"])) {
    $fileName = time() . '_' . $_FILES["file"]["name"];
    $location = "../../public/images/content/categoryThumbnail/" . $fileName;

    if($categoryObj->uploadNewCategory($_POST["newCategoryName"], $_POST["newCategoryDescription"], $fileName)) {
        copy($_FILES["file"]["tmp_name"], $location);
        $result["data_type"] = 1;
        $result["data_value"] = "New category was created";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

?>