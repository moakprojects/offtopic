<?php

session_start();

if(isset($_POST["regEmail"])) {
    require "../config/connection.php";
    require "selection.php";

    if($checkUserEmailQuery) {

        $checkUserEmailQuery->bindParam(':email', $_POST["regEmail"]);
        $checkUserEmailQuery->execute();
        
        /* We check if the email already exist in our database */
        if($checkUserEmailQuery->rowCount() > 0) {

            $checkUserEmailResult = $checkUserEmailQuery->fetch(PDO::FETCH_ASSOC);

            $result["data_type"] = 0;

            /* We display error messages depending on the email is already verified or not */
            if(intval($checkUserEmailResult["accessLevel"]) === 0) {
                $result["data_value"] = "This email is waiting for verification";
            } else {
                $result["data_value"] = "The email is already exist";
            }    
            
            echo json_encode($result);
        } else {

            if($checkUsernameQuery) {
                $username = htmlspecialchars(trim($_POST["regUsername"]));
                $checkUsernameQuery->bindParam(':username', $username);
                $checkUsernameQuery->execute();

                if($checkUsernameQuery->rowCount() > 0) {
                    $result["data_type"] = 0;
                    $result["data_value"] = "The username is already exist";    
                
                    echo json_encode($result);
                } else {

                    /* set a cost (we don't set the salt because password_has will generate it automatecialy) */
                    $cost = ["cost" => 8];
                    $passwordHash = password_hash(htmlspecialchars(trim($_POST["regPassword"])), PASSWORD_BCRYPT, $cost);

                    require "insert.php";

                    if($createUserQuery) {
                        $createUserQuery->bindParam(':email', $_POST["regEmail"]);
                        $createUserQuery->bindParam(':username', $username);
                        $createUserQuery->bindParam(':passwordHash', $passwordHash);
                        $now = new DateTime('now');
                        $now = $now->format('Y-m-d H:i:s');
                        $createUserQuery->bindParam(':regDate', $now);
                        $createUserQuery->execute();
                    
                        /* send a confirmation email */

                        $emailTemplate = file_get_contents("../resources/templates/verifyEmailTemplate.html");

                        $emailHash = md5($_POST["regEmail"]);
                        $verifyEmail = "http://offtopic.dev/verify/" . $emailHash;

                        $emailTemplate = str_replace("{{username}}", $username, $emailTemplate);
                        $emailTemplate = str_replace("{{verify_url}}", $verifyEmail, $emailTemplate);

                        $headers = "FROM: noreply@offtopic.dev" . "\r\n";
						$headers .= "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

                        //mail($_POST["regEmail"], "Verify email address for OffTopic", $emailTemplate, $headers);

            
                        $result["data_type"] = 1;
                        $result["data_value"] = "Registration finished";

                        echo json_encode($result);
                    } else {
                        $result["data_type"] = 0;
                        $result["data_value"] = "An error occured";

                        echo json_encode($result);
                    }
                }
            } else {
                $result["data_type"] = 0;
                $result["data_value"] = "An error occured";

                echo json_encode($result);
            }
        }
    } else {
        $result["data_type"] = 0;
        $result["data_value"] = "An error occured";

        echo json_encode($result);
    }
}

if(isset($_POST["logID"])) {
    require "../config/connection.php";
    require "selection.php";

    if($loginQuery) {

        $logID = htmlspecialchars(trim($_POST["logID"]));
        $loginQuery->bindParam(':logID', $logID);
        $loginQuery->execute();
        
        if($loginQuery->rowCount() == 0) {
            $result["data_type"] = 0;
            $result["data_value"] = "Wrong email or username or password";    
            
            echo json_encode($result);
        } else {

            $loginResult = $loginQuery->fetch(PDO::FETCH_ASSOC);

            if(intval($loginResult["accessLevel"]) === 0) {
                $result["data_type"] = 0;
                $result["data_value"] = "You have to verify your email address before you login";    
            
                echo json_encode($result);
            } else {
                $password = htmlspecialchars(trim($_POST["logPassword"]));

                if(!password_verify($password, $loginResult["password"])) {
                    $result["data_type"] = 0;
                    $result["data_value"] = "Wrong email or username or password";    
                
                    echo json_encode($result);
                } else {
                    $_SESSION["user"]["loggedIn"] = true;
                    $_SESSION["user"]["username"] = $loginResult["username"];

                    /* if user select 'remember me' then we set a cookie into the browser, so the user will be logged for 3 month */
                    if($_POST["rememberMe"] === "true") {
                        setcookie("usr", md5($logID), time() + 7890000, '/');
                    }

                    $result["data_type"] = 1;
                    $result["data_value"] = "Valid";    
                
                    echo json_encode($result);
                }
            }
        }
    } else {
        header("Location: /error");
        exit;
    }
}