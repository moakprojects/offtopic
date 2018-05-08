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
            global $increaseNumberOfVisitors;

            if(isset($increaseNumberOfVisitors)) {
                $increaseNumberOfVisitors->bindParam(':username', $username);
                $increaseNumberOfVisitors->execute();
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

    }
?>