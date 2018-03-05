<?php
if(!($_FILES["file"]["error"] > 0)) 
{
    $imgName = time() . '_' . $_FILES["file"]["name"];
    $location = "../public/images/upload/" . $imgName;
    copy($_FILES["file"]["tmp_name"], $location);
    echo json_encode($_FILES["file"]);
} else {
    echo "An error occurred";
    // TODO_pro: Security log (Here we get a server error what we would like to log)
}
?>