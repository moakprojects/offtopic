<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/insertion.php";
include "../../database/modification.php";
include "../classes/User.php";
$userObj = new User();

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
        $userDataByUsername = $userObj->checkUserEmail($regUsername);

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
                $verifyEmail = "http://offtopic.dev/verify/" . $emailHash;

                $emailTemplate = str_replace("{{username}}", $regUsername, $emailTemplate);
                $emailTemplate = str_replace("{{verify_url}}", $verifyEmail, $emailTemplate);

                $headers = "FROM: noreply@offtopic.dev" . "\r\n";
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

                /* if user select 'remember me' then we set a cookie into the browser, so the user will be logged for 3 month */
                if($_POST["rememberMe"] === "true") {
                    setcookie("usr", md5($logID), time() + 7890000, '/');
                }

                $result["data_type"] = 1;
                $result["data_value"] = "Valid";    
            
                echo json_encode($result);
                exit;
            }
        } //intval
    } //not exist else if
}

if(isset($_FILES["file"])) {
    
    if(!($_FILES["file"]["error"] > 0)) 
    {
        uploadProfileImage($_FILES["file"]["name"], $_FILES["file"]["tmp_name"]);
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";
        echo json_encode($result);
        exit;
        // TODO_pro: Security log (Here we get a server error what we would like to log)
    }

} else if(isset($_POST["avatarName"])) {
    
    $currentLocation = "../../public/images/defaultAvatars/" . $_POST["avatarName"];

    uploadProfileImage($_POST["avatarName"], $currentLocation);

}

function uploadProfileImage($filename, $tmpLocation) {

    global $userObj;

    $imgName = time() . '_' . $filename;
    $newLocation = "../../public/images/upload/" . $imgName;

    $loggedUser = $userObj->loggedUser($_SESSION["user"]["userID"]);

    if($loggedUser["profileImage"] != "defaultAvatar.png") {
        $previousLocation = "../../public/images/upload/" . $loggedUser["profileImage"];
    }

    if($userObj->uploadProfileImage($imgName, $_SESSION["user"]["userID"])) {
        copy($tmpLocation, $newLocation);

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

if(isset($_POST["requestOwnPosts"])) {
    $createdPostsInTopicsData = $userObj->getCreatedPosts($_SESSION["selectedUsername"]);
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

?>