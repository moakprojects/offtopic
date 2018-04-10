<?php
/* get default avatar datas from defaultAvatar table */
$defaultAvatarsQuery = $db->prepare("SELECT * FROM defaultAvatar");

/* get logged userdata from user table */
$loggedUserQuery = $db->prepare("SELECT * FROM user WHERE userID = :userID");

/* get post data from post table */
$postQuery = $db->prepare("SELECT post.*, user.username, user.profileImage FROM post INNER JOIN user ON post.userID = user.userID WHERE topicID = 1");

/* get attached file from attechment table with postID */
$attachFilesQuery = $db->prepare("SELECT * FROM attachment WHERE postID = :postID");

/* select number of likes and dislikes of the posts depends on the topic */
$numberOfLikesQuery = $db->prepare("SELECT count(isLike) as count FROM `like` WHERE islike = 1 AND postID = :postID");
$numberOfDislikesQuery = $db->prepare("SELECT count(isDislike) as count FROM `like` WHERE isDislike = 1 AND postID = :postID");

$inLikeTableQuery = $db->prepare("SELECT * FROM `like` WHERE userID = :userID AND postID = :postID");

/* check users for registration */
$checkUserEmailQuery = $db->prepare("SELECT * FROM user WHERE email = :email");
$checkUsernameQuery = $db->prepare("SELECT * FROM user WHERE username = :username");

/* select user for verify email address */
$verifyEmailQuery = $db->prepare("SELECT * FROM user WHERE md5(email) = :emailHash");

/* select user for login */
$loginQuery = $db -> prepare("SELECT * FROM user WHERE email = :logID OR username = :logID");

/* select user for login by cookie */
$cookieLoginQuery = $db -> prepare("SELECT * FROM user WHERE md5(email) = :logIDHash OR md5(username) = :logIDHash");

/* select information for the category page */
$categoryQuery = $db -> prepare("SELECT category.*, numberOfTopics, numberOfPosts, numberOfLikes FROM category LEFT JOIN (SELECT categoryID, count(*) as numberOfTopics, topicID FROM topic GROUP BY categoryID) as topics ON topics.categoryID = category.categoryID LEFT JOIN ( SELECT topic.categoryID, topic.topicID, sum(postQuantity) as numberOfPosts FROM `topic` LEFT JOIN (SELECT topicID, count(*) as postQuantity FROM post GROUP BY topicID) as countPost ON countPost.topicID = topic.topicID GROUP BY topic.categoryID ) as posts ON posts.topicID = topics.topicID LEFT JOIN (SELECT categoryID, count(*) as numberOfLikes FROM categoryLike GROUP BY categoryID) as likes ON likes.categoryID = category.categoryID");

/* check category if exist */
$checkCategoryQuery = $db -> prepare("SELECT * FROM category WHERE categoryID = :categoryID");

/* select category information for the category list sidebar block */
$sideBarCategoryQuery = $db ->prepare("SELECT category.categoryName, category.categoryID, count(topic.topicID) as numberOfTopics FROM category LEFT JOIN topic ON category.categoryID = topic.categoryID GROUP BY topic.categoryID ORDER BY count(topic.topicID) DESC LIMIT 5");

/* select general topic information based on categoryID */
$topicQuery = $db -> prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM topiclike GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester WHERE topic.categoryID = :categoryID ORDER BY topic.createdAt DESC");

/* select choosen topic information based on topicId */
$selectedTopicQuery = $db->prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM topiclike GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester WHERE topic.topicID = :topicID ORDER BY topic.createdAt DESC");

/* get latest topics for what's new */
$latestTopicsQuery = $db->prepare("SELECT topic.*, categoryName, numberOfPosts, username, profileImage FROM topic INNER JOIN (SELECT categoryID, categoryName FROM category) as categories ON categories.categoryID = topic.categoryID INNER JOIN (SELECT topicID, count(*) as numberOfPosts FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy ORDER BY createdAt DESC LIMIT 5");

/* get Latest post for Latest posts in the siderbar */
 $latestPostsQuery = $db->prepare("SELECT post.text, post.postedOn, username, topics.topicID, topicName FROM post INNER JOIN (SELECT userID, username FROM user) as user ON user.userID = post.userID INNER JOIN (SELECT topicID, topicName FROM topic) as topics ON topics.topicID = post.topicID ORDER BY post.postedOn DESC LIMIT 3");
?>