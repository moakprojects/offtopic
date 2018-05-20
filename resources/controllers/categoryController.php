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

    $newCategoryName = htmlspecialchars(trim($_POST["newCategoryName"]));
    $newCategoryDescription = htmlspecialchars(trim($_POST["newCategoryDescription"]));

    if($categoryObj->uploadNewCategory($newCategoryName, $newCategoryDescription, $fileName)) {
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

/* delete selected category */
if(isset($_POST["deleteCategory"])) {
    if($categoryObj->deleteCategory($_POST["categoryID"])) {
        
        $result["data_type"] = 1;
        $result["data_value"] = "The selected category was deleted";

        echo json_encode($result);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

/* add the selected category id - which the admin want to modify - to the session */
if(isset($_POST["modifyCategory"])) {
    $_SESSION["modifyCategoryID"] = $_POST["categoryID"];
    
    $result["data_type"] = 1;
    $result["data_value"] = "Success";
    echo json_encode($result);
}

/* modify selected category */
if(isset($_POST["modifiedCategoryData"])) {
    if(isset($_POST["file"])) {
        $fileName = $_POST["file"];
    } else if(isset($_FILES["file"]["name"])) {
        $fileName = time() . '_' . $_FILES["file"]["name"];
        $location = "../../public/images/content/categoryThumbnail/" . $fileName;
        $previousLocation = "../../public/images/content/categoryThumbnail/" . $_POST["oldImage"];
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }

    $modifiedCategoryName = htmlspecialchars(trim($_POST["modifiedCategoryName"]));
    $modifiedCategoryDescription = htmlspecialchars(trim($_POST["modifiedCategoryDescription"]));

    if($categoryObj->modifyCategoryData($_POST["modifiedCategoryID"], $modifiedCategoryName, $modifiedCategoryDescription, $fileName)) {
        if(isset($_FILES["file"])) {
            copy($_FILES["file"]["tmp_name"], $location);
            unlink($previousLocation);
        }

        unset($_SESSION["modifyCategoryID"]);

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