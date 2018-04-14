<?php
/* get default avatar datas from defaultAvatar table */
$defaultAvatarsQuery = $db->prepare("SELECT * FROM defaultAvatar");

/* get logged userdata from user table */
$loggedUserQuery = $db->prepare("SELECT * FROM user WHERE userID = :userID");

/* get post data from post table */
$postQuery = $db->prepare("SELECT post.*, user.username, user.profileImage, numberOfLikes, numberOfDislikes
FROM post 
INNER JOIN user ON post.userID = user.userID 
LEFT JOIN (SELECT postlike.postID, sum(isLike) as numberOfLikes, sum(isDislike) as numberOfDislikes FROM `postlike` GROUP BY postlike.postID) as likes ON likes.postID = post.postID
WHERE topicID = :topicID");

/* get attached file from attechment table with postID */
$attachedFilesQuery = $db->prepare("SELECT * FROM attachment WHERE attachedFileCode = :attachedFileCode");

/* select number of likes and dislikes of the posts depends on the topic */
$numberOfLikesQuery = $db->prepare("SELECT count(isLike) as count FROM `postLike` WHERE islike = 1 AND postID = :postID");
$numberOfDislikesQuery = $db->prepare("SELECT count(isDislike) as count FROM `postLike` WHERE isDislike = 1 AND postID = :postID");

/* check if user already like or dislike the post, if yes we disable the buttens for the user */
$checkPostLikeQuery = $db->prepare("SELECT * FROM `postLike` WHERE userID = :userID AND postID = :postID");

/* check a value of like/dislike of a specific post after like/dislike */
$checkSpecificPostLikeValueQuery = $db->prepare("SELECT sum(isLike) as numberOfLikes, sum(isdisLike) as numberOfDislikes FROM postLike WHERE postID = :postID GROUP BY postID");

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
$categoryQuery = $db -> prepare("SELECT category.*, numberOfTopics, numberOfPosts, numberOfLikes FROM category LEFT JOIN (SELECT categoryID, count(*) as numberOfTopics, topicID FROM topic GROUP BY categoryID) as topics ON topics.categoryID = category.categoryID LEFT JOIN ( SELECT topic.categoryID, topic.topicID, sum(postQuantity) as numberOfPosts FROM `topic` LEFT JOIN (SELECT topicID, count(*) as postQuantity FROM post GROUP BY topicID) as countPost ON countPost.topicID = topic.topicID GROUP BY topic.categoryID ) as posts ON posts.topicID = topics.topicID LEFT JOIN (SELECT categoryID, count(*) as numberOfLikes FROM favouriteCategory GROUP BY categoryID) as likes ON likes.categoryID = category.categoryID");

/* get categories for sidebar category block */
$sideBarCategoriesQuery = $db->prepare("SELECT category.categoryName, category.categoryID, numberOfTopics FROM category LEFT JOIN (SELECT categoryID, count(*) as numberOfTopics, topicID FROM topic GROUP BY categoryID) as topics ON topics.categoryID = category.categoryID WHERE category.categoryID NOT IN (SELECT categoryID FROM favouritecategory WHERE userID = :userID) LIMIT 5");

/* check category if exist */
$checkCategoryQuery = $db -> prepare("SELECT * FROM category WHERE categoryID = :categoryID");

/* select favourite categories */
$sideBarFavouriteCategoriesQuery = $db->prepare("SELECT category.categoryName, category.categoryID, count(topic.topicID) as numberOfTopics FROM category LEFT JOIN topic ON category.categoryID = topic.categoryID INNER JOIN favouritecategory ON favouritecategory.categoryID = category.categoryID WHERE favouritecategory.userID = :userID GROUP BY category.categoryID ORDER BY count(topic.topicID) DESC LIMIT 5");

/* select general topic information based on categoryID */
$topicQuery = $db -> prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM favouriteTopic GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester WHERE topic.categoryID = :categoryID ORDER BY topic.createdAt DESC");

/* check if user added the topic to favourite or not */
$checkFavouriteTopicQuery = $db->prepare("SELECT * FROM favouriteTopic WHERE userID = :userID AND topicID = :topicID");

/* check if user added the category to favourite or not */
$checkFavouriteCategoryQuery = $db->prepare("SELECT * FROM favouriteCategory WHERE userID = :userID AND categoryID = :categoryID");

/* select choosen topic information based on topicId */
$selectedTopicQuery = $db->prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM favouriteTopic GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester WHERE topic.topicID = :topicID ORDER BY topic.createdAt DESC");

/* get latest topics for what's new */
$latestTopicsQuery = $db->prepare("SELECT topic.*, categoryName, numberOfPosts, username, profileImage FROM topic INNER JOIN (SELECT categoryID, categoryName FROM category) as categories ON categories.categoryID = topic.categoryID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy ORDER BY createdAt DESC LIMIT 5");

/* get Latest post for Latest posts in the siderbar */
 $latestPostsQuery = $db->prepare("SELECT post.text, post.postedOn, username, topics.topicID, topicName FROM post INNER JOIN (SELECT userID, username FROM user) as user ON user.userID = post.userID INNER JOIN (SELECT topicID, topicName FROM topic) as topics ON topics.topicID = post.topicID ORDER BY post.postedOn DESC LIMIT 3");

 /* get hot topics */
 $hotTopicsQuery = $db->prepare("SELECT topic.*, username, profileImage, categoryName, numberOfPosts, latestPost FROM topic INNER JOIN (SELECT userID, username, profileImage FROM user) as creator ON creator.userID = topic.createdBy INNER JOIN (SELECT categoryID, categoryName FROM category) as category ON category.categoryID = topic.categoryID INNER JOIN (SELECT topicID, count(*) as numberOfLatestPosts, MAX(postedOn) as latestPost FROM post WHERE postedOn > :selectionDate GROUP BY topicID ORDER BY numberOfLatestPosts DESC) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT topicID, count(*) as numberOfPosts FROM post GROUP BY topicID) as numberOfPosts ON numberOfPosts.topicID = topic.topicID LIMIT 5");

 /* get favourite topics */
 $favouriteTopicsQuery = $db->prepare("SELECT topic.*, username, profileImage, categoryName, numberOfPosts, latestPost FROM topic INNER JOIN (SELECT userID, username, profileImage FROM user) as creator ON creator.userID = topic.createdBy INNER JOIN (SELECT categoryID, categoryName FROM category) as category ON category.categoryID = topic.categoryID INNER JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as numberOfPosts ON numberOfPosts.topicID = topic.topicID INNER JOIN favouritetopic ON favouritetopic.topicID = topic.topicID WHERE favouritetopic.userID = :userID ORDER BY latestPost DESC");

 /* get own topics */
 $ownTopicsQuery = $db->prepare("SELECT topic.*, username, profileImage, categoryName, numberOfPosts, latestPost FROM topic INNER JOIN (SELECT userID, username, profileImage FROM user) as creator ON creator.userID = topic.createdBy INNER JOIN (SELECT categoryID, categoryName FROM category) as category ON category.categoryID = topic.categoryID INNER JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as numberOfPosts ON numberOfPosts.topicID = topic.topicID WHERE creator.userID = :userID ORDER BY latestPost DESC");
?>