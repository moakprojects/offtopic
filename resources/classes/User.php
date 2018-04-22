<?php

    class User {
        
        private $userCreation = array();

        function __construct() {

        }

        function getOwn() {
            return $this->userCreation;
        }

        function setOwn($value) {
            $this->userCreation = $value;
        }

        public function __get($name) {
            return $this->$name;
        }

        public function __set($name, $value) {
            $this->name = $value;
        }

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

        function checkUserName($regUsername) {

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

        function getSelectedUser($username) {
            global $db;
            global $userQuery;

            if(isset($userQuery)) {

                $userQuery->bindParam(':username', $username);
                $userQuery->execute();

                if($userQuery->rowCount() > 0) {
                    return $userData = $userQuery->fetch(PDO::FETCH_ASSOC);
                } else {
                    header("Location: /error");
                    exit;
                }
            } else {
                header("Location: /error");
                exit;
            }
        }

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

        function increaseNumberOfVisitors($username) {
            global $db;
            global $increaseNumberOfVisitors;

            if(isset($increaseNumberOfVisitors)) {
                $increaseNumberOfVisitors->bindParam(':username', $username);
                $increaseNumberOfVisitors->execute();
            }
        }

        function getCreatedPosts($username) {
            global $db;
            global $createdPostsQuery;
    
            if($createdPostsQuery) {
                $createdPostsQuery->bindParam(":username", $username);
                $createdPostsQuery->execute();
    
                return $createdPostsData = $createdPostsQuery->fetchall(PDO::FETCH_ASSOC);

                $createdPostsInTopics = array();
                foreach($createdPostsData as $createdPostInTopic) {
                    $topicID = $createdPostInTopic['topicID'];
                    if(!isset($createdPostsInTopicsData[$topicID])) {
                        $createdPostsInTopics[$topicID] = array();
                        $createdPostsInTopics[$topicID]["topicID"] = $createdPostInTopic["topicID"];
                        $createdPostsInTopics[$topicID]["topicName"] = $createdPostInTopic["topicName"];
                        $createdPostsInTopics[$topicID]["categoryID"] = $createdPostInTopic["categoryID"];
                        $createdPostsInTopics[$topicID]["categoryName"] = $createdPostInTopic["categoryName"];
                        $createdPostsInTopics[$topicID]["createdAt"] = $createdPostInTopic["createdAt"];
                        $createdPostsInTopics[$topicID]["posts"] = array(); 
                    }
        
                    $aux = array("postID" => $createdPostInTopic["postID"], "text" => $createdPostInTopic["text"], "postedOn" => $createdPostInTopic["postedOn"], "numberOfLikes" => $createdPostInTopic["numberOfLikes"], "numberOfDislikes" => $createdPostInTopic["numberOfDislikes"]);
                    array_push($createdPostsInTopics[$topicID]["posts"], $aux);
                }
            } else {
                return false;
            }
        }

        function textTrimmer($longText, $length) {
            if(strlen($longText) > $length) {
                            
                return $cuttedTopicText = substr($longText, 0, $length) . "...";  
            } else {
                return $longText; 
            }
        }

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

    }
?>