<?php
/* get default avatar data from defaultAvatar table */
$defaultAvatarsQuery = $db->prepare("SELECT * FROM defaultavatar");

/* get logged userdata from user table */
$loggedUserQuery = $db->prepare("SELECT * FROM user WHERE userID = :userID");

/* get userdata from user table based on username */
$userQuery = $db->prepare("SELECT user.*, numberOfFollowers, numberOfTopics, numberOfPosts, numberOfPostLikes FROM user LEFT JOIN (SELECT topic.createdBy, sum(numberOfFavourites) as numberOfFollowers, count(topic.topicID) as numberOfTopics FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfFavourites FROM favouritetopic GROUP BY topicID) as favourites ON favourites.topicID = topic.topicID GROUP BY topic.createdBy) as topicInformation ON topicInformation.createdBy = user.userID LEFT JOIN (SELECT post.userID, sum(numberOfLikes) as numberOfPostLikes, count(post.postID) as numberOfPosts FROM post LEFT JOIN (SELECT postID, sum(isLike) as numberOfLikes FROM postlike GROUP BY postID) as likes ON likes.postID = post.postID GROUP BY post.userID) as postInformation ON postInformation.userID = user.userID WHERE username = :username");

/* get all user from user table */
$allUserQuery = $db->prepare("SELECT user.userID, user.email, user.username, user.profileImage, user.rankID, user.regDate, numberOfFollowers, numberOfTopics, numberOfPosts, numberOfPostLikes FROM user LEFT JOIN (SELECT topic.createdBy, sum(numberOfFavourites) as numberOfFollowers, count(topic.topicID) as numberOfTopics FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfFavourites FROM favouritetopic GROUP BY topicID) as favourites ON favourites.topicID = topic.topicID GROUP BY topic.createdBy) as topicInformation ON topicInformation.createdBy = user.userID LEFT JOIN (SELECT post.userID, sum(numberOfLikes) as numberOfPostLikes, count(post.postID) as numberOfPosts FROM post LEFT JOIN (SELECT postID, sum(isLike) as numberOfLikes FROM postlike GROUP BY postID) as likes ON likes.postID = post.postID GROUP BY post.userID) as postInformation ON postInformation.userID = user.userID");

/* get post data from post table based on topicID*/
$postQuery = $db->prepare("SELECT post.*, user.username, user.profileImage, numberOfLikes, numberOfDislikes
FROM post 
INNER JOIN user ON post.userID = user.userID 
LEFT JOIN (SELECT postlike.postID, sum(isLike) as numberOfLikes, sum(isDislike) as numberOfDislikes FROM `postlike` GROUP BY postlike.postID) as likes ON likes.postID = post.postID
WHERE topicID = :topicID");

/* get all of the post data from post table*/
$allPostQuery = $db->prepare("SELECT post.*, topic.topicName, user.username, user.profileImage, numberOfLikes, numberOfDislikes FROM post INNER JOIN user ON post.userID = user.userID INNER JOIN topic ON topic.topicID = post.topicID LEFT JOIN (SELECT postlike.postID, sum(isLike) as numberOfLikes, sum(isDislike) as numberOfDislikes FROM `postlike` GROUP BY postlike.postID) as likes ON likes.postID = post.postID");

/* get attached files from attechment table with postID */
$attachedFilesQuery = $db->prepare("SELECT * FROM attachment WHERE attachedFileCode = :attachedFileCode");

/* select number of likes and dislikes of the posts depends on the topic */
$numberOfLikesQuery = $db->prepare("SELECT count(isLike) as count FROM `postlike` WHERE islike = 1 AND postID = :postID");
$numberOfDislikesQuery = $db->prepare("SELECT count(isDislike) as count FROM `postlike` WHERE isDislike = 1 AND postID = :postID");

/* check if user already like or dislike the post */
$checkPostLikeQuery = $db->prepare("SELECT * FROM `postlike` WHERE userID = :userID AND postID = :postID");

/* check the value of like/dislike of a specific post after like/dislike */
$checkSpecificPostLikeValueQuery = $db->prepare("SELECT sum(isLike) as numberOfLikes, sum(isdisLike) as numberOfDislikes FROM postlike WHERE postID = :postID GROUP BY postID");

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
$allCategoryQuery = $db -> prepare("SELECT category.*, numberOfTopics, numberOfPosts, numberOfLikes 
    FROM category 
    LEFT JOIN (
        SELECT categoryID, count(*) as numberOfTopics, topicID 
        FROM topic 
        GROUP BY categoryID) as topics ON topics.categoryID = category.categoryID 
    LEFT JOIN (
        SELECT topic.categoryID, topic.topicID, sum(postQuantity) as numberOfPosts 
        FROM `topic` 
        LEFT JOIN (
            SELECT topicID, count(*) as postQuantity 
            FROM post 
            GROUP BY topicID) as countPost ON countPost.topicID = topic.topicID 
        GROUP BY topic.categoryID ) as posts ON posts.topicID = topics.topicID 
    LEFT JOIN (
        SELECT categoryID, count(*) as numberOfLikes 
        FROM favouritecategory 
        GROUP BY categoryID) as likes ON likes.categoryID = category.categoryID");

/* get categories for sidebar category block */
$sideBarCategoriesQuery = $db->prepare("SELECT category.categoryName, category.categoryID, numberOfTopics FROM category LEFT JOIN (SELECT categoryID, count(*) as numberOfTopics, topicID FROM topic GROUP BY categoryID) as topics ON topics.categoryID = category.categoryID WHERE category.categoryID NOT IN (SELECT categoryID FROM favouritecategory WHERE userID = :userID) LIMIT 5");

/* select specific category by categoryID */
$checkCategoryQuery = $db -> prepare("SELECT * FROM category WHERE categoryID = :categoryID");

/* select favourite categories */
$sideBarFavouriteCategoriesQuery = $db->prepare("SELECT category.categoryName, category.categoryID, category.thumbnail, count(topic.topicID) as numberOfTopics FROM category LEFT JOIN topic ON category.categoryID = topic.categoryID INNER JOIN favouritecategory ON favouritecategory.categoryID = category.categoryID WHERE favouritecategory.userID = :userID GROUP BY category.categoryID ORDER BY count(topic.topicID) DESC");

/* select general topic information based on categoryID */
$topicQuery = $db -> prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM favouritetopic GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester WHERE topic.categoryID = :categoryID ORDER BY topic.createdAt DESC");

/* get general topic information */
$allTopicQuery = $db -> prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName, categoryName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM favouritetopic GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester INNER JOIN  (SELECT categoryID, categoryName FROM category) as category ON category.categoryID = topic.categoryID ORDER BY topic.createdAt DESC");

/* check if user added the topic to favourite or not */
$checkFavouriteTopicQuery = $db->prepare("SELECT * FROM favouritetopic WHERE userID = :userID AND topicID = :topicID");

/* check if user added the category to favourite or not */
$checkFavouriteCategoryQuery = $db->prepare("SELECT * FROM favouritecategory WHERE userID = :userID AND categoryID = :categoryID");

/* select choosen topic information based on topicId */
$selectedTopicQuery = $db->prepare("SELECT topic.*, numberOfLikes, numberOfPosts, latestPost, username, profileImage, periodName FROM topic LEFT JOIN (SELECT topicID, count(*) as numberOfLikes FROM favouritetopic GROUP BY topicID) as likes ON likes.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy INNER JOIN (SELECT periodID, periodName FROM period) as periods ON periods.periodID = topic.semester WHERE topic.topicID = :topicID ORDER BY topic.createdAt DESC");

/* get latest topics for what's new */
$latestTopicsQuery = $db->prepare("SELECT topic.*, categoryName, numberOfPosts, username, profileImage FROM topic INNER JOIN (SELECT categoryID, categoryName FROM category) as categories ON categories.categoryID = topic.categoryID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT userID, username, profileImage FROM user) as users ON users.userID = topic.createdBy ORDER BY createdAt DESC LIMIT 5");

/* get Latest posts for Latest posts in the siderbar */
 $latestPostsQuery = $db->prepare("SELECT post.text, post.postedOn, username, topics.topicID, topicName FROM post INNER JOIN (SELECT userID, username FROM user) as user ON user.userID = post.userID INNER JOIN (SELECT topicID, topicName FROM topic) as topics ON topics.topicID = post.topicID ORDER BY post.postedOn DESC LIMIT 3");

 /* get hot topics */
 $hotTopicsQuery = $db->prepare("SELECT topic.*, username, profileImage, categoryName, numberOfPosts, latestPost FROM topic INNER JOIN (SELECT userID, username, profileImage FROM user) as creator ON creator.userID = topic.createdBy INNER JOIN (SELECT categoryID, categoryName FROM category) as category ON category.categoryID = topic.categoryID INNER JOIN (SELECT topicID, count(*) as numberOfLatestPosts, MAX(postedOn) as latestPost FROM post WHERE postedOn > :selectionDate GROUP BY topicID ORDER BY numberOfLatestPosts DESC) as posts ON posts.topicID = topic.topicID INNER JOIN (SELECT topicID, count(*) as numberOfPosts FROM post GROUP BY topicID) as numberOfPosts ON numberOfPosts.topicID = topic.topicID LIMIT 5");

 /* get favourite topics */
 $favouriteTopicsQuery = $db->prepare("SELECT topic.*, username, profileImage, categoryName, numberOfPosts, latestPost FROM topic INNER JOIN (SELECT userID, username, profileImage FROM user) as creator ON creator.userID = topic.createdBy INNER JOIN (SELECT categoryID, categoryName FROM category) as category ON category.categoryID = topic.categoryID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID) as numberOfPosts ON numberOfPosts.topicID = topic.topicID INNER JOIN favouritetopic ON favouritetopic.topicID = topic.topicID WHERE favouritetopic.userID = :userID ORDER BY latestPost DESC");

 /* get own topics */
 $ownTopicsQuery = $db->prepare("SELECT topic.*, username, profileImage, categoryName, numberOfPosts, latestPost, numberOfFollowers FROM topic INNER JOIN ( SELECT userID, username, profileImage FROM user ) as creator ON creator.userID = topic.createdBy INNER JOIN ( SELECT categoryID, categoryName FROM category ) as category ON category.categoryID = topic.categoryID LEFT JOIN ( SELECT topicID, count(*) as numberOfPosts, MAX(postedOn) as latestPost FROM post GROUP BY topicID ) as numberOfPosts ON numberOfPosts.topicID = topic.topicID LEFT JOIN ( SELECT topicID, count(*) as numberOfFollowers FROM favouritetopic GROUP BY topicID ) as favourites ON favourites.topicID = topic.topicID WHERE creator.userID = :userID ORDER BY latestPost DESC");

 /* get posts activity for Distribution of posts by category chart */
 $numberOfPostsInCategoriesQuery = $db->prepare("SELECT category.categoryName, sum(posts) as numberOfPosts FROM topic INNER JOIN ( SELECT post.userID, user.username, topicID, count(*) as posts FROM post INNER JOIN user ON user.userID = post.userID WHERE user.username = :username GROUP BY post.userID, topicID) as numberOfPostsByTopic ON numberOfPostsByTopic.topicID = topic.topicID RIGHT JOIN category ON topic.categoryID = category.categoryID GROUP BY category.categoryName");

 /* get posts history for Written post history chart */
 $numberOfPostsByMonthsQuery = $db->prepare("SELECT count(*) as numberOfPosts, MONTH(postedOn) as month FROM post INNER JOIN user ON user.userID = post.userID WHERE postedOn > :startDate AND user.username = :username GROUP BY month");

 /* get like information for Distribution of likes on posts chart */
 $numberOfLikesOfPostsQuery = $db->prepare("SELECT count(post.postID) as numberOfPosts, sum(likes) as numberOfLikes, sum(dislikes) as numberOfDislikes FROM post LEFT JOIN ( SELECT postID, sum(isLike) as likes, sum(isDislike) as dislikes FROM postlike GROUP BY postID) as likes ON likes.postID = post.postID INNER JOIN user ON user.userID = post.userID WHERE user.username = :username GROUP BY user.username");

 /* get post like information for profile page favourite section */
 $likedPostQuery = $db->prepare("SELECT topic.topicName, post.* FROM postlike INNER JOIN post ON post.postID = postlike.postID INNER JOIN user ON user.userID = postlike.userID INNER JOIN topic ON topic.topicID = post.topicID WHERE user.username = :username AND postlike.isLike = 1 ORDER BY post.postedOn DESC");

 /* get topics what the user created */
 $createdTopicsQuery = $db->prepare("SELECT user.username, topic.topicID, topic.topicName, topic.topicText, topic.createdAt, category.categoryID, category.categoryName, numberOfPosts, numberOfFollowers FROM topic INNER JOIN user ON user.userID = topic.createdBy INNER JOIN category ON category.categoryID = topic.categoryID LEFT JOIN (SELECT topicID, count(*) as numberOfPosts FROM post GROUP BY topicID) as posts ON posts.topicID = topic.topicID LEFT JOIN (SELECT topicID, count(*) as numberOfFollowers FROM favouritetopic GROUP BY topicID) as followers ON followers.topicID = topic.topicID WHERE user.username = :username");

 /* get created posts */
 $createdPostsQuery = $db->prepare("SELECT user.username, post.postID, post.text, post.postedOn, post.topicID, numberOfLikes, numberOfDislikes, topic.topicName, topic.createdAt, category.categoryID, category.categoryName FROM post INNER JOIN user ON user.userID = post.userID LEFT JOIN (SELECT postID, sum(isLike) as numberOfLikes, sum(isDislike) as numberOfDislikes FROM postlike GROUP BY postID) as likes ON likes.postID = post.postID INNER JOIN topic ON topic.topicID = post.topicID INNER JOIN category ON category.categoryID = topic.categoryID WHERE user.username = :username");

 /* get badge system */
 $badgeSystemQuery = $db->prepare("SELECT badgeName, badgeDescription, earned FROM `badge` LEFT JOIN (SELECT badgeID as earned FROM earnedbadge INNER JOIN user ON earnedbadge.userID = user.userID WHERE user.username = :username) AS earnedbadges ON earnedbadges.earned = badge.badgeID");

 /* get badges belongs to the selected user */
 $earnedBadgeQuery = $db->prepare("SELECT badgeName FROM `badge` INNER JOIN earnedbadge ON earnedbadge.badgeID = badge.badgeID INNER JOIN user ON earnedbadge.userID = user.userID WHERE user.username = :username");

 /* get period info */
 $periodQuery = $db->prepare("SELECT * FROM `period`");

 /* get selected post data for comment page */
 $selectedPostsQuery = $db->prepare("SELECT post.postID, post.text, post.postedOn, post.replyID, post.attachedFilesCode as postAttachedFilesCode, topic.topicID, topic.topicName, topic.topicText, topic.createdAt, topic.attachedFilesCode as topicAttachedFilesCode, topicCreatorID, topicCreatorName, topicCreatorImage, post.attachedFilesCode, postWriterID, postWriterName, postWriterImage, originalPostID, originalUserID, originalUsername, postIdInTopic
 FROM post
 INNER JOIN (
     SELECT userID as postWriterID, username as postWriterName, profileImage as postWriterImage
     FROM user) as postWriter ON postWriter.postWriterID = post.userID
 INNER JOIN topic ON topic.topicID = post.topicID
 INNER JOIN (
     SELECT userID as topicCreatorID, username as topicCreatorName, profileImage as topicCreatorImage
     FROM user) as topicCreator ON topicCreator.topicCreatorID = topic.createdBy
 LEFT JOIN (
     SELECT post.postID as originalPostID, post.text as originalPostText, originalUserID, originalUsername
     FROM post
     INNER JOIN (
         SELECT userID as originalUserID, username as originalUserName
         FROM user) 
         as originalUser ON originalUser.originalUserID = post.userID
     ) as originalPost ON originalPost.originalPostID = post.replyID
INNER JOIN (
    SELECT topicID, count(*) as postIdInTopic
    FROM post 
    WHERE postID <= :postID 
    GROUP BY topicID
    ) as postIdInTopic ON postIdInTopic.topicID = topic.topicID
 WHERE postID = :postID");


/* get the ID of the created new topic */
$getIdOfCreatedTopicQuery = $db->prepare("SELECT topicID FROM topic WHERE createdBy = :createdBy && createdAt = :createdAt");

/* in function where user can earn badge we check if he or she already earned or not */
$getBadgeInformationQuery = $db->prepare("SELECT * FROM earnedBadge WHERE userID = :userID && badgeID = :badgeID");

/* get number of posts for badge */
$getNumberOfPostsQuery = $db->prepare("SELECT * FROM post WHERE userID = :userID");
?>