<?php

/* get profileImage name from user table */
$userSelectionQuery = "SELECT * FROM user WHERE userID = 1";
$userSelectionResult = $db->query($userSelectionQuery, PDO::FETCH_ASSOC);
foreach($userSelectionResult as $row) {
    $avatarFileName = $row["profileImage"];
}

/* get default avatar datas from defaultAvatar table */
$defaultAvatarSelectionQuery = "SELECT * FROM defaultAvatar";
$defaultAvatarSelectionResult = $db->query($defaultAvatarSelectionQuery, PDO::FETCH_ASSOC);

$defaultAvatarImages = array();
$auxArray = array("id" => 0, "fileName" => "");
foreach($defaultAvatarSelectionResult as $row) {
    $auxArray["id"] = $row['avatarID'];
    $auxArray["fileName"] = $row['fileName'];
    array_push($defaultAvatarImages, $auxArray);
}

/* get post datas from post table */
$postSelectionQuery = "SELECT * FROM post WHERE topicID = 1";

$post = array();
while($row = $postSelectionResult = $db->query($postSelectionQuery, PDO::FETCH_ASSOC)) {
    array_push($post, $row);
}


?>