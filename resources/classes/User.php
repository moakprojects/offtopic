<?php

    class User {

        // check the email that is exist in usertable or not for registration
        function checkUserEmail($regEmail) {

            global $db;
            global $checkUserEmailQuery;

            if(isset($checkUserEmailQuery)) {

                $checkUserEmailQuery->bindParam(':email', $regEmail);
                $checkUserEmailQuery->execute();

                if($checkUserEmailQuery->rowCount() > 0) {
                   return $checkUserEmailResult = $checkUserEmailQuery->fetch(PDO::FETCH_ASSOC);
                } else {
                   return $checkUserEmailResult = "not exist";
                }

            } else {

                return $checkUserEmailResult = "error";
            }
        }

        // check the username that is exist in usertable or not for registration
        function checkUsername($regUsername) {

            global $db;
            global $checkUsernameQuery;

            if(isset($checkUsernameQuery)) {

                $checkUsernameQuery->bindParam(':username', $regUsername);
                $checkUsernameQuery->execute();

                if($checkUsernameQuery->rowCount() > 0) {
                    return $checkUsernameResult = $checkUsernameQuery->fetch(PDO::FETCH_ASSOC);
                } else {
                    return $checkUsernameResult = "not exist";
                }

            } else {
                return $checkUsernameResult = "error";
            }
        }

         // create a new user in the database
        function createUser($regEmail, $regUsername, $passwordHash) {

            global $db;
            global $createUserQuery;

            if(isset($createUserQuery)) {
                
                $createUserQuery->bindParam(':email', $regEmail);
                $createUserQuery->bindParam(':username', $regUsername);
                $createUserQuery->bindParam(':passwordHash', $passwordHash);
                $now = new DateTime('now');
                $now = $now->format('Y-m-d H:i:s');
                $createUserQuery->bindParam(':regDate', $now);
                $createUserQuery->execute();

                return true;
            } else {
                return false;
            }
        }

        // check userID for login
        function loginUser($logID) {

            global $db;
            global $loginQuery;

            if(isset($loginQuery)) {
                
                $loginQuery->bindParam(':logID', $logID);
                $loginQuery->execute();

                if($loginQuery->rowCount() > 0) {
                    return $loginResult = $loginQuery->fetch(PDO::FETCH_ASSOC);
                } else {
                    return $loginResult = "not exist";
                }
            } else {
                return $loginResult = "error";
            }
        }

        // check that the user is already logged in or not
        function loggedUser($userID) {

            global $db;
            global $loggedUserQuery;

            if(isset($loggedUserQuery)) {
                
                $loggedUserQuery->bindParam(':userID', $userID);
                $loggedUserQuery->execute();

                return $loggedUserQuery->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }

        }

        // upload new profli image into database
        function uploadProfileImage($imagename, $userID) {
            global $db;
            global $profileImageUploadQuery;

            if(isset($profileImageUploadQuery)) {

                $profileImageUploadQuery->bindParam(':profileImage', $imagename);
                $profileImageUploadQuery->bindParam(':userID', $userID);
                $profileImageUploadQuery->execute();

                return true;
            } else {
                return false;
            }

        }

        // request default avatars for carousel
        function getDefaultAvatars() {
            global $db;
            global $defaultAvatarsQuery;

            if(isset($defaultAvatarsQuery)) {
                $defaultAvatarsQuery->execute();

                return $defaultAvatars = $defaultAvatarsQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        // request badge system for modal
        function getBadgeSystem($username) {
            global $db;
            global $badgeSystemQuery;

            if(isset($badgeSystemQuery)) {
                $badgeSystemQuery->bindParam(':username', $username);
                $badgeSystemQuery->execute();

                return $badgeSystemQuery = $badgeSystemQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        // request the badges belongs to the selected user
        function getEarnedBadges($username) {
            global $db;
            global $earnedBadgeQuery;

            if(isset($earnedBadgeQuery)) {
                $earnedBadgeQuery->bindParam(':username', $username);
                $earnedBadgeQuery->execute();

                return $earnedBadgeQuery = $earnedBadgeQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        // get selected user information from database
        function getSelectedUser($username) {
            global $db;
            global $userQuery;

            if(isset($userQuery)) {

                $userQuery->bindParam(':username', $username);
                $userQuery->execute();

                if($userQuery->rowCount() > 0) {

                    $userData = $userQuery->fetch(PDO::FETCH_ASSOC);
                    $userData["memberFor"] = $this->calculateTimeDifferences($userData["regDate"]);
                    if(!is_null($userData["lastLoginDate"])) {
                        $userData["lastSeen"] = $this->calculateTimeDifferences($userData["lastLoginDate"]);
                    } else {
                        $userData["lastSeen"] = false;
                    }

                    return $userData;
                } else {
                    header("Location: /error");
                    exit;
                }
            } else {
                header("Location: /error");
                exit;
            }
        }

        // get selected user information from database
        function getAllUser() {
            global $db;
            global $allUserQuery;

            if(isset($allUserQuery)) {

                $allUserQuery->execute();

                $allUserData = $allUserQuery->fetchall(PDO::FETCH_ASSOC);
        
                for($i = 0; $i < count($allUserData); $i++) {
                    $allUserData[$i]["memberFor"] = $this->calculateTimeDifferences($allUserData[$i]["regDate"]);
                }

                return $allUserData;
            } else {
                header("Location: /error");
                exit;
            }
        }

        // get information for post distribution chart
        function getInformationForPostDistributionChart($username) {
            global $db;
            global $numberOfPostsInCategoriesQuery;

            if(isset($numberOfPostsInCategoriesQuery)) {

                $numberOfPostsInCategoriesQuery->bindParam(':username', $username);
                $numberOfPostsInCategoriesQuery->execute();

                return $numberOfPostsInCategoriesResult = $numberOfPostsInCategoriesQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        //get information for post history chart
        function getInformationForPostHistoryChart($startDate, $username) {
            global $db;
            global $numberOfPostsByMonthsQuery;

            if(isset($numberOfPostsByMonthsQuery)) {

                $numberOfPostsByMonthsQuery->bindParam(':startDate', $startDate);
                $numberOfPostsByMonthsQuery->bindParam(':username', $username);
                $numberOfPostsByMonthsQuery->execute();

                return $numberOfPostsByMonthsResult = $numberOfPostsByMonthsQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        // get information for post likes chart
        function getInformationForPostLikesChart($username) {
            global $db;
            global $numberOfLikesOfPostsQuery;

            if(isset($numberOfLikesOfPostsQuery)) {

                $numberOfLikesOfPostsQuery->bindParam(':username', $username);
                $numberOfLikesOfPostsQuery->execute();

                if($numberOfLikesOfPostsQuery->rowCount() > 0) {
                    $numberOfLikesOfPostsResult = $numberOfLikesOfPostsQuery->fetch(PDO::FETCH_ASSOC);

                    if(is_null($numberOfLikesOfPostsResult["numberOfLikes"])) {
                        $numberOfLikesOfPostsResult["numberOfLikes"] = 0;
                    }

                    if(is_null($numberOfLikesOfPostsResult["numberOfDislikes"])) {
                        $numberOfLikesOfPostsResult["numberOfDislikes"] = 0;
                    }

                    return $numberOfLikesOfPostsResult;
                } else {
                    return $numberOfLikesOfPostsResult = array("numberOfPosts"=>0,"numberOfLikes"=>0,"numberOfDislikes"=>0);
                }

            } else {
                return false;
            }
        }

        // increase number of visitors in database
        function increaseNumberOfVisitors($username) {
            global $db;
            global $increaseNumberOfVisitorsQuery;

            if(isset($increaseNumberOfVisitorsQuery)) {
                $increaseNumberOfVisitorsQuery->bindParam(':username', $username);
                $increaseNumberOfVisitorsQuery->execute();
            }
        }

        // request created posts from database for profile page
        function getCreatedPosts($username) {
            global $db;
            global $createdPostsQuery;
    
            if($createdPostsQuery) {
                $createdPostsQuery->bindParam(":username", $username);
                $createdPostsQuery->execute();
    
                return $createdPostsData = $createdPostsQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        // trim the long texts
        function textTrimmer($longText, $length) {
            if(strlen($longText) > $length) {
                            
                return $cuttedTopicText = substr($longText, 0, $length) . "...";  
            } else {
                return $longText; 
            }
        }

        // request created topics from database for profile page
        function getCreatedTopics($username) {
            global $db;
            global $createdTopicsQuery;
    
            if(isset($createdTopicsQuery)) {
    
                $createdTopicsQuery->bindParam(':username', $username);
                $createdTopicsQuery->execute();
    
                if($createdTopicsQuery->rowCount() > 0) {
                    $createdTopicsData = $createdTopicsQuery->fetchall(PDO::FETCH_ASSOC);
                
                    for($i = 0; $i < count($createdTopicsData); $i++) {
                    
                        $createdTopicsData[$i]["topicDescription"] = $this->textTrimmer($createdTopicsData[$i]["topicText"], 200);
                        $createdTopicsData[$i]["shortTopicName"] = $this->textTrimmer($createdTopicsData[$i]["topicName"], 53);
        
                    }
    
                    return $createdTopicsData;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        //request own topics from database for home page
        function getOwnTopics($userID) {
            global $db;
            global $ownTopicsQuery;
    
            if(isset($ownTopicsQuery)) {
                
                $ownTopicsQuery->bindParam(":userID", $userID);
                $ownTopicsQuery->execute();
    
                if($ownTopicsQuery->rowCount() > 0) {
                    $ownTopicsData = $ownTopicsQuery->fetchall(PDO::FETCH_ASSOC);
                
                    for($i = 0; $i < count($ownTopicsData); $i++) {
                    
                        $ownTopicsData[$i]["topicDescription"] = $this->textTrimmer($ownTopicsData[$i]["topicText"], 162);
                        $ownTopicsData[$i]["shortTopicName"] = $this->textTrimmer($ownTopicsData[$i]["topicName"], 53);
        
                        $ownTopicsData[$i]["latestPostElapsedTime"] = $this->calculateTimeDifferences($ownTopicsData[$i]["latestPost"]);
                    }
    
                    return $ownTopicsData;
                } else {
                    return false;
                }
            } else {
                header("Location: /error");
                exit;
            }
        }

        // request liked posts from database for profile page
        function getLikedPosts($username) {
            global $db;
            global $likedPostQuery;
    
            if($likedPostQuery) {
                $likedPostQuery -> bindParam(":username", $username);
                $likedPostQuery->execute();
    
                $likedPostData = $likedPostQuery->fetchall(PDO::FETCH_ASSOC);
    
                for($i = 0; $i < count($likedPostData); $i++) {
    
                    $likedPostData[$i]["shortTopicName"] = $this->textTrimmer($likedPostData[$i]["topicName"], 18); 
                    $likedPostData[$i]["shortPostText"] = $this->textTrimmer($likedPostData[$i]["text"], 218); 
    
                    $postedOn = new DateTime($likedPostData[$i]["postedOn"]);
                    $likedPostData[$i]["monthDay"] = $postedOn -> format('M, j') . "<sup>" . $postedOn -> format('S') . "</sup>";
                    $likedPostData[$i]["time"] = $postedOn -> format('h:ia');
                }
    
                return $likedPostData;
            }
        }

        // calculate time difference between a specific time and now 
        function calculateTimeDifferences($latest) {
            $latestTime = new DateTime($latest);
            $now = new DateTime('now');
            $diff = $latestTime -> diff($now);

            if($diff -> y > 1 ) {
                return $difference = $diff -> y . " years";
            } else if ($diff -> y == 1 ){
                return $difference =  $diff -> y . " year";
            } else if ($diff -> m > 1 ){
                return $difference =  $diff -> m . " months";
            } else if ($diff -> m == 1 ){
                return $difference =  $diff -> m . " month";
            } else if ($diff -> d > 1 ){
                return $difference =  $diff -> d . " days";
            } else if ($diff -> d == 1 ){
                return $difference =  $diff -> d . " day";
            } else if ($diff -> h > 1 ){
                return $difference =  $diff -> h . " hours";
            } else if ($diff -> h == 1 ){
                return $difference =  $diff -> h . " hour";
            } else if ($diff -> i > 1 ){
                return $difference =  $diff -> i . " mins";
            } else {
                return $difference =  "1 min";
            }
        }

        // request favourite topics information from database
        function getFavouriteTopics($userID) {
            global $db;
            global $favouriteTopicsQuery;

            if(isset($favouriteTopicsQuery)) {
                
                $favouriteTopicsQuery->bindParam(":userID", $userID);
                $favouriteTopicsQuery->execute();

                if($favouriteTopicsQuery->rowCount() > 0) {
                    $favouriteTopicsData = $favouriteTopicsQuery->fetchall(PDO::FETCH_ASSOC);
                
                    for($i = 0; $i < count($favouriteTopicsData); $i++) {
                    
                        $favouriteTopicsData[$i]["topicDescription"] = $this->textTrimmer($favouriteTopicsData[$i]["topicText"], 162);
                        $favouriteTopicsData[$i]["shortTopicName"] = $this->textTrimmer($favouriteTopicsData[$i]["topicName"], 53);
        
                        $favouriteTopicsData[$i]["latestPostElapsedTime"] = $this->calculateTimeDifferences($favouriteTopicsData[$i]["latestPost"]);
                    }

                    return $favouriteTopicsData;
                } else {
                    return false;
                }
            } else {
                header("Location: /error");
                exit;
            }
        }

        //set changed settings information
        function saveAccountData($userID, $username, $email, $aboutMe, $birthdate, $location) {
            global $db;
            global $saveAccountDataQuery;
    
            if(isset($saveAccountDataQuery)) {
    
                $saveAccountDataQuery->bindParam(':userID', $userID);
                $saveAccountDataQuery->bindParam(':username', $username);
                $saveAccountDataQuery->bindParam(':email', $email);
                $saveAccountDataQuery->bindParam(':aboutMe', $aboutMe);
                $saveAccountDataQuery->bindParam(':birthdate', $birthdate);
                $saveAccountDataQuery->bindParam(':location', $location);
                $saveAccountDataQuery->execute();
   
                return true;

            } else {
                return false;
            }
        }

        //get user data from database (for ajax call)
        function getUserData($username) {
            global $db;
            global $userQuery;
    
            if(isset($userQuery)) {
    
                $userQuery->bindParam(':username', $username);
                $userQuery->execute();

                $userData = $userQuery->fetch(PDO::FETCH_ASSOC);

                return $userData;

            } else {
                return false;
            }
        }

        //get userData with userID
        function getUserInfo($userID) {
            global $db;
            global $userInfoQuery;
    
            try {
                $userInfoQuery->bindParam(':userID', $userID);
                $userInfoQuery->execute();

                return $userInforQuery->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                return false;
            }
        }

        function deleteUser($userID, $type) {
            global $db;

            global $modifyDeletedUserInTopicQuery;
            global $modifyDeletedUserInPostLikeQuery;
            global $modifyDeletedUserInPostQuery;
            global $modifyDeletedUserInFavouriteTopicQuery;
            global $modifyDeletedUserInFavouriteCategoryQuery;
            global $deletedUserFromEarnedBadgeQuery;

            global $suspendUserSecondTimeQuery;
            global $deleteUserQuery;

            $userID = htmlspecialchars(trim($userID));

            try {
                $db->beginTransaction();

                $modifyDeletedUserInTopicQuery->bindParam(':userID', $userID);
                $modifyDeletedUserInTopicQuery->execute();

                $modifyDeletedUserInPostLikeQuery->bindParam(':userID', $userID);
                $modifyDeletedUserInPostLikeQuery->execute();

                $modifyDeletedUserInPostQuery->bindParam(':userID', $userID);
                $modifyDeletedUserInPostQuery->execute();
                
                $modifyDeletedUserInFavouriteTopicQuery->bindParam(':userID', $userID);
                $modifyDeletedUserInFavouriteTopicQuery->execute();
                
                $modifyDeletedUserInFavouriteCategoryQuery->bindParam(':userID', $userID);
                $modifyDeletedUserInFavouriteCategoryQuery->execute();
                
                $deletedUserFromEarnedBadgeQuery->bindParam(':userID', $userID);
                $deletedUserFromEarnedBadgeQuery->execute();

                if($type == "delete") {
                    $deleteUserQuery->bindParam(':userID', $userID);
                    $deleteUserQuery->execute();
                } else if($type == "suspend") {
                    $suspendUserSecondTimeQuery->bindParam(':userID', $userID);
                    $suspendUserSecondTimeQuery->execute();
                }

                $db->commit();

                return true;

            } catch(PDOException $e) {
                $db->rollBack();

                return false;
            }
        }

        function uploadBadge($userID, $badgeID) {
            global $db;
            global $newBadgeQuery;

            try {
                $newBadgeQuery->bindParam(':userID', $userID);
                $newBadgeQuery->bindParam(':badgeID', $badgeID);
                $newBadgeQuery->execute();

                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        function checkBadgeStatus($userID, $badgeID) {
            global $db;
            global $getBadgeInformationQuery;

            try {
                $getBadgeInformationQuery->bindParam(':userID', $userID);
                $getBadgeInformationQuery->bindParam(':badgeID', $badgeID);
                $getBadgeInformationQuery->execute();

                //if false then the user don't have badge yet
                if($getBadgeInformationQuery->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                return "error";
            }
        }

        function getNumberOfPosts($userID) {
            global $db;
            global $getNumberOfPostsQuery;

            try {
                $getNumberOfPostsQuery->bindParam(':userID', $userID);
                $getNumberOfPostsQuery->execute();

                return $getNumberOfPostsQuery->rowCount();
            } catch (PDOException $e) {
                return false;
            }
        }

        function saveLoginDate($userID) {
            global $db;
            global $saveLastLoginQuery;
            
            try {
                $saveLastLoginQuery->bindParam(':userID', $userID);
                $now = new DateTime('now');
                $now = $now->format('Y-m-d H:i:s');
                $saveLastLoginQuery->bindParam(':lastLoginDate', $now);
                $saveLastLoginQuery->execute();

                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        function increaseConsecutiveVisit($userID) {
            global $db;
            global $increaseNumberOfConsecutiveVisitQuery;

            try {
                $increaseNumberOfConsecutiveVisitQuery->bindParam(':userID', $userID);
                $increaseNumberOfConsecutiveVisitQuery->execute();

                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        function deleteConsecutiveVisit($userID) {
            global $db;
            global $clearNumberOfConsecutiveVisitQuery;

            $clearNumberOfConsecutiveVisitQuery->bindParam(':userID', $userID);
            $clearNumberOfConsecutiveVisitQuery->execute();

            try {
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        function getReceivedQuestions($topicID) {
            global $db;
            global $getReceivedQuestionsQuery;

            try {

                $getReceivedQuestionsQuery->bindParam(":topicID", $topicID);
                $getReceivedQuestionsQuery->execute();

                return $getReceivedQuestionsQuery->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return false;
            }
        }

        // we check for the celeb badge if the user has one or more topic with more than 25 followers
        function getWellFollowedTopic($topicID) {
            global $db;
            global $getWellFollowedTopicQuery;

            try {

                $getWellFollowedTopicQuery->bindParam(":topicID", $topicID);
                $getWellFollowedTopicQuery->execute();

                if($getWellFollowedTopicQuery->rowCount() > 0) {
                    return $getWellFollowedTopicQuery->fetch(PDO::FETCH_ASSOC);
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                return false;
            }
        }

        // we check for the celeb badge if the user has one or more topic with more than 25 followers
        function getWellLikedUser($postID) {
            global $db;
            global $getWellLikedUserQuery;

            try {

                $getWellLikedUserQuery->bindParam(":postID", $postID);
                $getWellLikedUserQuery->execute();

                if($getWellLikedUserQuery->rowCount() > 0) {
                    return $getWellLikedUserQuery->fetch(PDO::FETCH_ASSOC);
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                return false;
            }
        }

        //first time we block the user profile for 3 days
        function suspendUserFirstTime($username) {
            global $db;
            global $suspendUserFirstTimeQuery;
            global $dissolveSuspensionQuery;

            try {

                $db->beginTransaction();

                $suspendUserFirstTimeQuery->bindParam(":username", $username);
                $suspendUserFirstTimeQuery->execute();

                $dissolveSuspensionQuery->bindParam(":username", $username);
                $dissolveSuspensionQuery->execute();

                $db->commit();
                return true;
            } catch (PDOException $e) {
                $db->rollback();
                return false;
            }
        }
    }
?>