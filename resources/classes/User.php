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
    }
?>