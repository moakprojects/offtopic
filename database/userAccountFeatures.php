<?php
if(isset($_POST["email"])) {
    require "../config/connection.php";
    require "selection.php";

    if($checkUserEmailQuery) {

        $checkUserEmailQuery->bindParam(':email', $_POST["email"]);
        $checkUserEmailQuery->execute();

        $rsult = $checkUserEmailQuery->rowCount();
        
        if($checkUserEmailQuery->rowCount() > 0) {
            $result["data_type"] = 0;
            $result["data_value"] = "The email is already exist";    
            
            echo json_encode($result);
        } else {

            if($checkUsernameQuery) {
                $username = htmlspecialchars(trim($_POST["username"]));
                $checkUsernameQuery->bindParam(':username', $username);
                $checkUsernameQuery->execute();

                if($checkUsernameQuery->rowCount() > 0) {
                    $result["data_type"] = 0;
                    $result["data_value"] = "The username is already exist";    
                
                    echo json_encode($result);
                } else {

                    /* set a cost (we don't set the salt because password_has will generate it automatecialy) */
                    $cost = ["cost" => 8];
                    $passwordHash = password_hash(htmlspecialchars(trim($_POST["password"])), PASSWORD_BCRYPT, $cost);

                    require "insert.php";

                    if($createUserQuery) {
                        $createUserQuery->bindParam(':email', $_POST["email"]);
                        $createUserQuery->bindParam(':username', $username);
                        $createUserQuery->bindParam(':passwordHash', $passwordHash);
                        $createUserQuery->execute();
                    
                        $result["data_type"] = 1;
                        $result["data_value"] = "Registration finished";
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