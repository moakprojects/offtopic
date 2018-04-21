<?php

    class User {
        
        function __construct() {

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
    }
?>