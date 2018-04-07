<?php
/* require "config/connection.php"; */
/* get profileImage name from user table */
$userSelectionQuery = "SELECT * FROM user WHERE userID = 1";
$userSelectionResult = $db->query($userSelectionQuery, PDO::FETCH_ASSOC);
foreach($userSelectionResult as $row) {
    $username = $row["username"];
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
$postSelectionResult = $db->query($postSelectionQuery);

$post = array();
while($row = $postSelectionResult->fetch(PDO::FETCH_ASSOC)) {
    array_push($post, $row);
}

/* get attached file from attechment table with postID */
$attachFilesQuery = $db->prepare("SELECT * FROM attachment WHERE postID = :postID");

/* select number of likes and dislikes of the posts depends on the topic */
$numberOfLikesQuery = $db->prepare("SELECT count(isLike) as count FROM `like` WHERE islike = 1 AND postID = :postID");
$numberOfDislikesQuery = $db->prepare("SELECT count(isDislike) as count FROM `like` WHERE isDislike = 1 AND postID = :postID");

$inLikeTableQuery = $db->prepare("SELECT * FROM `like` WHERE userID = :userID AND postID = :postID");

/* select users for registration */
$checkUserEmailQuery = $db->prepare("SELECT * FROM user WHERE email = :email");
$checkUsernameQuery = $db->prepare("SELECT * FROM user WHERE username = :username");

/* select user for verify email address */
$verifyEmailQuery = $db->prepare("SELECT * FROM user WHERE md5(email) = :emailHash");

/* select user for login */
$loginQuery = $db -> prepare("SELECT * FROM user WHERE email = :logID OR username = :logID");

/* select user for login by cookie */
$cookieLoginQuery = $db -> prepare("SELECT * FROM user WHERE md5(email) = :logIDHash OR md5(username) = :logIDHash");

/* select category informations */
$categoryQuery = $db -> prepare("SELECT * FROM category");

/* select topic informations */
$topicQuery = $db -> prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM topiclike GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester ORDER BY topic.createdAt DESC");

/* select who likes topics */
$numberOfTopicLikesQuery = $db -> prepare("SELECT count(*) as numberOfLikes, topicID FROM topicLike GROUP BY topicID");

/* select how many posts are in each topic */
$numberOfPostsQuery = $db -> prepare("SELECT count(*) as numberOfPosts, topicID FROM post GROUP BY topicID");

/* select last post in every topic */
$lastCommentTimeDifferenceQuery = $db -> prepare("SELECT postedOn FROM post GROUP BY topicID ORDER BY postedOn");
?>