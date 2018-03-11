<?php

$userSelectionQuery = "SELECT * FROM user WHERE userID = 1";
$userSelectionResult = $db->query($userSelectionQuery, PDO::FETCH_ASSOC);
foreach($userSelectionResult as $row) {
    $avatarFileName = $row["profileImage"];
}

$defaultAvatarSelectionQuery = "SELECT * FROM defaultAvatar";
$defaultAvatarSelectionResult = $db->query($defaultAvatarSelectionQuery, PDO::FETCH_ASSOC);

$defaultAvatarImages = array();
$auxArray = array("id" => 0, "fileName" => "");
foreach($defaultAvatarSelectionResult as $row) {
    $auxArray["id"] = $row['avatarID'];
    $auxArray["fileName"] = $row['fileName'];
    array_push($defaultAvatarImages, $auxArray);
}



?>