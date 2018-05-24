<?php
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/modification.php";
include "../classes/General.php";
$generalObj = new General();
    /* save new description data */
    if(isset($_POST["changeDescription"])) {
        $newDescription = htmlspecialchars(trim($_POST["newDescription"]));
        if($generalObj->saveDescriptionChanges($newDescription)) {
            $result["data_type"] = 1;
            $result["data_value"] = "Changes were saved";

            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
        }
    }

    /* save new contact data */
    if(isset($_POST["changeContact"])) {
        $newGeneralContactText = htmlspecialchars(trim($_POST["newGeneralContactText"]));
        $newPhoneNumber = htmlspecialchars(trim($_POST["newPhoneNumber"]));
        $newLocation = htmlspecialchars(trim($_POST["newLocation"]));

        if($generalObj->saveContactChanges($newGeneralContactText, $newPhoneNumber, $newLocation)) {
            $result["data_type"] = 1;
            $result["data_value"] = "Changes were saved";

            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
        }
    }

    /* save new contact data */
    if(isset($_POST["changeRules"])) {
        $newGeneralRules = htmlspecialchars(trim($_POST["newGeneralRules"]));
        $newAcceptanceOfTerms = htmlspecialchars(trim($_POST["newAcceptanceOfTerms"]));
        $newModificationOfTerms = htmlspecialchars(trim($_POST["newModificationOfTerms"]));
        $newRulesAndConduct = htmlspecialchars(trim($_POST["newRulesAndConduct"]));
        $newTermination = htmlspecialchars(trim($_POST["newTermination"]));
        $newIntegration = htmlspecialchars(trim($_POST["newIntegration"]));

        if($generalObj->saveRulesChanges($newGeneralRules, $newAcceptanceOfTerms, $newModificationOfTerms, $newRulesAndConduct, $newTermination, $newIntegration)) {
            $result["data_type"] = 1;
            $result["data_value"] = "Changes were saved";

            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
        }
    }

    /* send contact form information */
    if(isset($_POST["contactFormData"])) {
        $senderName = htmlspecialchars(trim($_POST["senderName"]));
        $senderEmail = htmlspecialchars(trim($_POST["senderEmail"]));
        $subject = htmlspecialchars(trim($_POST["subject"]));
        $problemDescription = htmlspecialchars(trim($_POST["problemDescription"]));

        $_SESSON["contactUser"]["IP"] = $_SERVER['REMOTE_ADDR'];
        $_SESSON["contactUser"]["lastSent"] = now();

        /* send email to admin with the data from the contact form*/
        $emailTemplate = file_get_contents("../templates/contactResponseEmailTemplate.html");

        $emailTemplate = str_replace("{{sender_name}}", $senderName, $emailTemplate);

        /* headers for visszaigazoló email */
        $headers = "FROM: contact@off-topic.tk" . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        /* headers for the admin */
        $adminHeaders = "FROM: contact@off-topic.tk" . "\r\n";
        $adminHeaders .= "REPLY-TO: $senderEmail" . "\r\n";
        $adminHeaders .= "MIME-Version: 1.0" . "\r\n";
        $adminHeaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        try {
            if($subject === "") {
                mail($senderEmail, "Thank you for getting in touch", $emailTemplate, $headers);
                mail('erik117b@easv365.dk', "You got a mail through the contact form", $problemDescription, $adminHeaders);
            }

            $result["data_type"] = 1;
            $result["data_value"] = "The message was sent";

            echo json_encode($result);
            exit;
        } catch (PDOException $e) {
            $result["data_type"] = 0;
            $result["data_value"] = $e;

            echo json_encode($result);
            exit;
        }
        
    }
    
?>