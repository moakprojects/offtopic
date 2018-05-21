<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/modification.php";
include "../../database/deletion.php";
include "../../database/event.php";
include "../classes/User.php";
include "../classes/Image.php";
$userObj = new User();
$imageObj = new Image();

//if a user wants to registrate we check the email and the username if it is already exist or not, if not we call create user function from persistence layer and we send a verification email
if(isset($_POST["regEmail"])) {

    $regEmail = htmlspecialchars(trim($_POST["regEmail"]));

    /* We check if the email already exist in our database */
    $userDataByEmail = $userObj->checkUserEmail($regEmail);
    
    if($userDataByEmail === "error") {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    } else if ($userDataByEmail == "not exist") {

        $regUsername = htmlspecialchars(trim($_POST["regUsername"]));
        $userDataByUsername = $userObj->checkUsername($regUsername);

        if($userDataByUsername === "error") {

            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
            exit;

        } else if ($userDataByUsername === "not exist") {

            /* set a cost (we don't set the salt because password_has will generate it automatecialy) */
            $cost = ["cost" => 8];
            $passwordHash = password_hash(htmlspecialchars(trim($_POST["regPassword"])), PASSWORD_BCRYPT, $cost);

            if($userObj->createUser($regEmail, $regUsername, $passwordHash)) {
                /* send a confirmation email */
                $emailTemplate = file_get_contents("../templates/verifyEmailTemplate.html");

                $emailHash = md5($regEmail);
                $hostname = $_SERVER['HTTP_HOST'];
                $verifyEmail = $hostname . "/verify/" . $emailHash;

                $emailTemplate = str_replace("{{username}}", $regUsername, $emailTemplate);
                $emailTemplate = str_replace("{{verify_url}}", $verifyEmail, $emailTemplate);

                $headers = "FROM: noreply@off-topic.tk" . "\r\n";
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

                //mail($regEmail, "Verify email address for OffTopic", $emailTemplate, $headers);

                $result["data_type"] = 1;
                $result["data_value"] = "Registration finished";

                echo json_encode($result);
                exit;
            } else {
                $result["data_type"] = 0;
                $result["data_value"] = "An error occured";

                echo json_encode($result);
                exit;
            }
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "The username is already exist";    
        
            echo json_encode($result);
            exit;
        }
    } else {
        $result["data_type"] = 0;

        /* We display error messages depending on the email is already verified or not */
        if(intval($userDataByEmail["accessLevel"]) === 0) {
            $result["data_value"] = "This email is waiting for verification";
        } else {
            $result["data_value"] = "The email is already exist";
        }    
        
        echo json_encode($result);
        exit;
    }
}

//if a user wants to log in we check the given email and password
if(isset($_POST["logID"])) {

    $logID = htmlspecialchars(trim($_POST["logID"]));
    $loggedUserData = $userObj->loginUser($logID);

    if($loggedUserData === "error") {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";    
        
        echo json_encode($result);
        exit;
    } else if ($loggedUserData === "not exist") {
        $result["data_type"] = 0;
        $result["data_value"] = "Wrong email or username or password";    
        
        echo json_encode($result);
        exit;
    } else {

        if(intval($loggedUserData["accessLevel"]) === 0) {
            $result["data_type"] = 0;
            $result["data_value"] = "You have to verify your email address before you login";    
        
            echo json_encode($result);
            exit;
        } else if(intval($loggedUserData["accessLevel"]) === 2) {
            $result["data_type"] = 0;
            $result["data_value"] = "Your profile has been suspended for 3 days";    
        
            echo json_encode($result);
            exit;
        } else if(intval($loggedUserData["accessLevel"]) === 3) {
            $result["data_type"] = 0;
            $result["data_value"] = "Your profile are suspended";    
        
            echo json_encode($result);
            exit;
        } else {
            $password = htmlspecialchars(trim($_POST["logPassword"]));

            if(!password_verify($password, $loggedUserData["password"])) {
                $result["data_type"] = 0;
                $result["data_value"] = "Wrong email or username or password";    
            
                echo json_encode($result);
                exit;
            } else {
                $_SESSION["user"]["loggedIn"] = true;
                $_SESSION["user"]["userID"] = $loggedUserData["userID"];

                /* if user is admin */
                if(intval($loggedUserData["accessLevel"]) === 18) {
                    $_SESSION["user"]["isAdmin"] = true;
                }

                /* if user select 'remember me' then we set a cookie into the browser, so the user will be logged for 3 month */
                if($_POST["rememberMe"] === "true") {
                    setcookie("usr", md5($logID), time() + 7890000, '/');
                }

                // check and add fanatic or enthusiast badge
                $hasFanaticBadge = $userObj->checkBadgeStatus($loggedUserData["userID"], 4);
                $hasEnthusiastBadge = $userObj->checkBadgeStatus($loggedUserData["userID"], 6);
                if(!$hasFanaticBadge || !$hasEnthusiastBadge) {
                    if(!is_null($loggedUserData["lastLoginDate"])) {
                        $latestLogin = new DateTime($loggedUserData["lastLoginDate"]);
                        $latestLogin = $latestLogin->format("Y-m-d");
    
                        $newDate = strtotime("+1 day", strtotime($latestLogin));
                        $newDate = date("Y-m-d", $newDate);
                        
                        $now = new DateTime('now');
                        $now = $now->format("Y-m-d");
    
                        if($latestLogin != $now) {
                            if($newDate == $now) {
                                $userObj->increaseConsecutiveVisit($loggedUserData["userID"]);

                                if(!$hasFanaticBadge) {
                                    if($loggedUserData["consecutiveVisit"] >= 29) {
                                        if($userObj->uploadBadge($loggedUserData["userID"], 4)) {
                                            $earnedBadges = $userObj->getAllBadges($loggedUserData["userID"]);

                                            if($loggedUserData["rankID"] == 3) {
                                                if(
                                                    in_array("1", $earnedBadge) &&
                                                    in_array("2", $earnedBadge) &&
                                                    in_array("3", $earnedBadge) &&
                                                    in_array("4", $earnedBadge) &&
                                                    in_array("5", $earnedBadge) &&
                                                    in_array("6", $earnedBadge) &&
                                                    in_array("7", $earnedBadge) &&
                                                    in_array("8", $earnedBadge) &&
                                                    in_array("9", $earnedBadge) &&
                                                    in_array("10", $earnedBadge)
                                                ) {
                                                    $userObject->increaseRankId($loggedUserData["userID"]);
                                                }
                                            }
                                        }
                                    }
                                }

                                if(!$hasEnthusiastBadge) {
                                    if($loggedUserData["consecutiveVisit"] >= 99) {
                                       if($userObj->uploadBadge($loggedUserData["userID"], 6)) {
                                            $earnedBadges = $userObj->getAllBadges($loggedUserData["userID"]);

                                            if($loggedUserData["rankID"] == 3) {
                                                if(
                                                    in_array("1", $earnedBadge) &&
                                                    in_array("2", $earnedBadge) &&
                                                    in_array("3", $earnedBadge) &&
                                                    in_array("4", $earnedBadge) &&
                                                    in_array("5", $earnedBadge) &&
                                                    in_array("6", $earnedBadge) &&
                                                    in_array("7", $earnedBadge) &&
                                                    in_array("8", $earnedBadge) &&
                                                    in_array("9", $earnedBadge) &&
                                                    in_array("10", $earnedBadge)
                                                ) {
                                                    $userObject->increaseRankId($loggedUserData["userID"]);
                                                }
                                            } else if($loggedUserData["rankID"] == 2) {
                                                if(
                                                    in_array("1", $earnedBadge) &&
                                                    in_array("2", $earnedBadge) &&
                                                    in_array("5", $earnedBadge) &&
                                                    in_array("6", $earnedBadge) &&
                                                    in_array("7", $earnedBadge) &&
                                                    in_array("8", $earnedBadge) &&
                                                    in_array("9", $earnedBadge) &&
                                                    in_array("10", $earnedBadge)
                                                ) {
                                                    $userObject->increaseRankId($loggedUserData["userID"]);
                                                }
                                            }
                                       }
                                    }
                                }
                            } else {
                                $userObj->deleteConsecutiveVisit($loggedUserData["userID"]);
                            }
                        }
                    }
                }

                //save lastLoginDate
                $userObj->saveLoginDate($loggedUserData["userID"]);

                $result["data_type"] = 1;
                $result["data_value"] = "Valid";    
            
                echo json_encode($result);
                exit;
            }
        }
    }
}

//if the user wants to change his or her avatar, depending on this is an uploaded image or default we call uploadProfileImage function
if(isset($_FILES["file"])) {
    
    if(!($_FILES["file"]["error"] > 0)) 
    {
        uploadProfileImage($_FILES["file"]["name"], $_FILES["file"]["tmp_name"], 'own');
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";
        echo json_encode($result);
        exit;
        // TODO_pro: Security log (Here we get a server error what we would like to log)
    }

} else if(isset($_POST["avatarName"])) {
    
    $currentLocation = "../../public/images/defaultAvatars/" . $_POST["avatarName"];

    uploadProfileImage($_POST["avatarName"], $currentLocation, 'default');

}

//add a uniqui id for the image and upload it to database and copy to the storage
function uploadProfileImage($filename, $tmpLocation, $type) {

    global $userObj;
    global $imageObj;

    if($type === 'own') {
        $imageObj->load($tmpLocation);
        $width = $imageObj->getWidth();
        $height = $imageObj->getHeight();

        if($width < $height) {
            $imageObj->resizeToWidth(400);
        } else {
            $imageObj->resizeToHeight(400);
        }

        $imageObj->crop(400);
    }

    $imgName = time() . '_' . $filename;
    $newLocation = "../../public/images/upload/" . $imgName;

    $loggedUser = $userObj->loggedUser($_SESSION["user"]["userID"]);

    //if the user had own profile image we delete it from storage
    if($loggedUser["profileImage"] != "defaultAvatar.png") {
        $previousLocation = "../../public/images/upload/" . $loggedUser["profileImage"];
    }

    if($userObj->uploadProfileImage($imgName, $_SESSION["user"]["userID"])) {
        
        if($type === 'own') {
            $imageObj->save($newLocation, 100);
        } else {
            copy($tmpLocation, $newLocation);
        }

        if(isset($previousLocation)) {
            unlink($previousLocation);
        }

        $result["data_type"] = 1;
        $result["data_value"] = $imgName;

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    }

}

//if the user check the profile page we request data for user statistics
if(isset($_POST["requestPostDistributionChartData"])) {

    $activiyData = $userObj->getInformationForPostDistributionChart($_SESSION["selectedUsername"]);
    if($activiyData) {

        $numberOfPosts = 0;
        foreach($activiyData as $data) {
            $numberOfPosts += $data["numberOfPosts"];
        }

        for($i = 0; $i<count($activiyData); $i++) {
            if($numberOfPosts !== 0) {
                $numberOfPostsPercent = round($activiyData[$i]["numberOfPosts"] / $numberOfPosts * 100, 1);
            } else {
                $numberOfPostsPercent = 0;
            }
            
            $activiyData[$i]["numberOfPostsPercent"] = $numberOfPostsPercent;
        }

        $result["data_type"] = 1;
        $result["data_value"] = $activiyData;

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    }
}

//if the user check the profile page we request data for user statistics
if(isset($_POST["requestPostHistoryChartData"])) {

    $startDate = date('Y-m-d', strtotime('first day of ', strtotime('-6 months')));
    $historyData = $userObj->getInformationForPostHistoryChart($startDate, $_SESSION["selectedUsername"]);
    

        $monthsData = [];

        $start    = date('Y-m', strtotime('first day of ', strtotime('-6 months')));
        $end      = date('Y-m', strtotime('first day of this month'));
        
        while($start <= $end){
            $monthName = date('F', strtotime($start));
            $monthID = date('n', strtotime($start));
            array_push($monthsData, array( "monthID" => $monthID, "monthName" => $monthName));

            if(substr($start, 5, 2) == "12") {
                $start = (date("Y", strtotime($start)) + 1)."-01";
            } else {
                $start++;
            }
        }

        for($i = 0; $i < count($monthsData); $i++) {
            for($j = 0; $j < count($historyData); $j++) {
                if($monthsData[$i]["monthID"] === $historyData[$j]["month"]) {
                    $monthsData[$i]["numberOfPosts"] = $historyData[$j]["numberOfPosts"];
                }
            }
        }

        $result["data_type"] = 1;
        $result["data_value"] = $monthsData;

        echo json_encode($result);
        exit;
}

//if the user check the profile page we request data for user statistics
if(isset($_POST["requestPostLikesChartData"])) {

    $postLikesData = $userObj->getInformationForPostLikesChart($_SESSION["selectedUsername"]);
    if($postLikesData) {

        $result["data_type"] = 1;
        $result["data_value"] = $postLikesData;

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    }
}

//if the user check the profile page we request data for own posts
if(isset($_POST["requestOwnPosts"])) {
    $selectedUsername = htmlspecialchars(trim($_SESSION["selectedUsername"]));
    $createdPostsInTopicsData = $userObj->getCreatedPosts($selectedUsername);
    if($createdPostsInTopicsData) {

        $createdPostsInTopics = array();
        foreach($createdPostsInTopicsData as $createdPostInTopic) {
            $topicID = $createdPostInTopic['topicID'];
            if(!isset($createdPostsInTopics[$topicID])) {
                $createdPostsInTopics[$topicID] = array();
                $createdPostsInTopics[$topicID]["topicID"] = $createdPostInTopic["topicID"];
                $createdPostsInTopics[$topicID]["topicName"] = $createdPostInTopic["topicName"];
                $createdPostsInTopics[$topicID]["categoryID"] = $createdPostInTopic["categoryID"];
                $createdPostsInTopics[$topicID]["categoryName"] = $createdPostInTopic["categoryName"];
                $createdPostsInTopics[$topicID]["createdAt"] = $createdPostInTopic["createdAt"];
                $createdPostsInTopics[$topicID]["posts"] = array(); 
            }
            array_push($createdPostsInTopics[$topicID]["posts"], array("postID" => $createdPostInTopic["postID"], "text" => $createdPostInTopic["text"], "postedOn" => $createdPostInTopic["postedOn"], "numberOfLikes" => $createdPostInTopic["numberOfLikes"], "numberOfDislikes" => $createdPostInTopic["numberOfDislikes"]));
        }

        

        $result["data_type"] = 1;
        $result["data_value"] = $createdPostsInTopics;

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    }
}

//if user wants to change user settings, we check the input data and then call
if(isset($_POST["changeUserSettings"])) {

    $userData = $userObj->getUserData($_SESSION["selectedUsername"]);

    $errorMsgs = array();
    $error = false;
    $newUsername = false;

    $username = htmlspecialchars(trim($_POST["username"]));

    //if the user write his or her username into the input field we don't get back error message like "Username already used"
    if($username !== $userData["username"]) {
        $userDataByUsername = $userObj->checkUsername($username);

        if($userDataByUsername === "error") {

            $error = true;

        } else if ($userDataByUsername === "not exist") {

            $newUsername = true;

        } else {
            array_push($errorMsgs, "The username is already exist");
        }
    }

    $email = htmlspecialchars(trim($_POST["email"]));

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //if the user write his or her email into the input field we don't get back error message like "Email already used"
        if($email !== $userData["email"]) {
            $userDataByEmail = $userObj->checkUserEmail($email);

            if($userDataByEmail === "error") {

                $error = true;

            } else if ($userDataByEmail === "not exist") {

                
            } else {
                array_push($errorMsgs, "The email is already exist");
            }
        }
    } else {
        array_push($errorMsgs, "Please enter a valid email");
    }

    if($error) {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    } else {
        if(!empty($errorMsgs)) {
            $result["data_type"] = 0;
            $result["data_value"] = $errorMsgs;

            echo json_encode($result);
            exit;
        } else {
            
            $aboutMe = htmlspecialchars(trim($_POST["aboutMe"]));
            if ($aboutMe === "") {
                $aboutMe = null;
            }

            $birthdateMonth = htmlspecialchars(trim($_POST["birthdateMonth"]));
            $birthdateDay = htmlspecialchars(trim($_POST["birthdateDay"]));
            if($birthdateMonth !== "" && $birthdateDay !== "") {
                $birthdate = date('F', mktime(0, 0, 0, $birthdateMonth, 10)) . " " . date('d', mktime(0, 0, 0, 10, $birthdateDay));
            } else {
                $birthdate = null;
            }

            $location = htmlspecialchars(trim($_POST["location"]));
            if($location === "") {
                $location = null;
            }

            if(
                $username == $userData["username"] &&
                $email == $userData["email"] &&
                $aboutMe == $userData["aboutMe"] &&
                $birthdate == $userData["birthdate"] &&
                $location == $userData["location"]
            ) {
                $result["data_type"] = 3;
                $result["data_value"] = "Nothing changed";

                echo json_encode($result);
                exit;
            } else {
                //if every new data validated then we set these information into database by help of persistence layer
                if($userObj->saveAccountData($userData["userID"], $username, $email, $aboutMe, $birthdate, $location)) {

                    $data_value = array();

                    if($aboutMe != $userData["aboutMe"]) {
                        if(!$userObj->checkBadgeStatus($userData["userID"], 1)) {
                            if($userObj->uploadBadge($userData["userID"], 1)) {
                                $earnedBadges = $userObj->getAllBadges($userData["userID"]);

                                if($loggedUserData["rankID"] == 3) {
                                    if(
                                        in_array("1", $earnedBadge) &&
                                        in_array("2", $earnedBadge) &&
                                        in_array("3", $earnedBadge) &&
                                        in_array("4", $earnedBadge) &&
                                        in_array("5", $earnedBadge) &&
                                        in_array("6", $earnedBadge) &&
                                        in_array("7", $earnedBadge) &&
                                        in_array("8", $earnedBadge) &&
                                        in_array("9", $earnedBadge) &&
                                        in_array("10", $earnedBadge)
                                    ) {
                                        $userObject->increaseRankId($userData["userID"]);
                                    }
                                } else if($loggedUserData["rankID"] == 2) {
                                    if(
                                        in_array("1", $earnedBadge) &&
                                        in_array("2", $earnedBadge) &&
                                        in_array("5", $earnedBadge) &&
                                        in_array("6", $earnedBadge) &&
                                        in_array("7", $earnedBadge) &&
                                        in_array("8", $earnedBadge) &&
                                        in_array("9", $earnedBadge) &&
                                        in_array("10", $earnedBadge)
                                    ) {
                                        $userObject->increaseRankId($userData["userID"]);
                                    }
                                } else if($loggedUserData["rankID"] == 1) {
                                    if(
                                        in_array("1", $earnedBadge) &&
                                        in_array("2", $earnedBadge) &&
                                        in_array("5", $earnedBadge) &&
                                        in_array("7", $earnedBadge) &&
                                        in_array("8", $earnedBadge)
                                    ) {
                                        $userObject->increaseRankId($userData["userID"]);
                                    }
                                }
                            }
                            $data_value["refreshBadges"] = true;
                        } else {
                            $data_value["refreshBadges"] = false;
                        }
                    } else {
                        $data_value["refreshBadges"] = false;
                    }
                    $data_value["username"] = str_replace(' ', '%20', $username);

                    if(!$newUsername) {
                        $result["data_type"] = 1;
                        $result["data_value"] = $data_value;
        
                        echo json_encode($result);
                        exit;
                    } else {
                        $result["data_type"] = 2;
                        $result["data_value"] = $data_value;

                        echo json_encode($result);
                        exit;
                    }
                } else {
                    $result["data_type"] = 0;
                    $result["data_value"] = "An error occured";

                    echo json_encode($result);
                    exit;
                }
            }
        }
        
    }
}

//displaying settings data
if(isset($_POST["requestSettingsData"])) {
    $selectedUsername = htmlspecialchars(trim($_SESSION["selectedUsername"]));
    $userData = $userObj->getUserData($selectedUsername);

    if($userData) {

        $settingsData["username"] = $userData["username"];
        $settingsData["email"] = $userData["email"];

        if(!is_null($userData["aboutMe"])) {
            $settingsData["aboutMe"] = $userData["aboutMe"];
        } else {
            $settingsData["aboutMe"] = "";
        }

        if(!is_null($userData["birthdate"])) {
            $birthdate = explode(" ", $userData["birthdate"]);
            $settingsData["birthdateMonth"] = date('n', strtotime($birthdate[0]));
            $settingsData["birthdateDay"] = $birthdate[1];
        } else {
            $settingsData["birthdateMonth"] = "";
            $settingsData["birthdateDay"] = "";
        }
        
        if(!is_null($userData["location"])) {
            $settingsData["location"] = $userData["location"];
        } else {
            $settingsData["location"] = "";
        }

        $result["data_type"] = 1;
        $result["data_value"] = $settingsData;

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    }
}

if(isset($_POST["deleteUserProfile"])) {
    //we need userID because in topic, postlike, favouritetopic tables we just store the userID
    if(isset($_POST["selectedUserID"])) {
        $userID = $_POST["selectedUserID"];
    } else {
        $userData = $userObj->getUserData($_SESSION["selectedUsername"]);
        $userID = $userData["userID"];
    }

    if($userObj->deleteUser($userID, 'delete')) {
        $result["data_type"] = 1;
        $result["data_value"] = "The user was deleted";

        echo json_encode($result);
        exit;
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
        exit;
    }
}

if(isset($_POST["suspendUser"])) {
    
    //check if user had a suspend time already
    $userData = $userObj->getUserInfo($_POST["userID"]);

    if($userData["hadSuspendPeriod"] == 0) {
        //if no we send to him/her an email and block for 3 days

        if($userObj->suspendUserFirstTime($userData["username"])) {
            $suspensionEmailTemplate = file_get_contents("../templates/suspensionEmailTemplate.html");

            $username = htmlspecialchars(trim($userData["username"]));
            $suspensionEmailTemplate = str_replace("{{username}}", $username, $suspensionEmailTemplate);

            $headers = "FROM: noreply@off-topic.tk" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

            //mail($userData["email"], "Suspension on OffTopic website", $suspensionEmailTemplate, $headers);

            $result["data_type"] = 1;
            $result["data_value"] = $userData["username"];

            echo json_encode($result);
            exit;
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
            exit;
        }
    } else {
        //if yes we block him/her profile

        if($userObj->deleteUser($userData["userID"], 'suspend')) {
            $result["data_type"] = 1;
            $result["data_value"] = "Suspend user second time";

            echo json_encode($result);
            exit;
        }
    }

}
?>