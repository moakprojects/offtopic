<?php
session_start();
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/modification.php";
include "../../database/deletion.php";
include "../classes/General.php";
include "../classes/Category.php";
include "../classes/Topic.php";
include "../classes/Post.php";
$generalObj = new General();
$categoryObj = new Category();
$topicObj = new Topic();
$postObj = new Post();
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

    if(isset($_POST["searchRequest"])) {
        $target = htmlspecialchars(trim($_POST["target"]));
        $searchResultInCategories = $categoryObj->searchInCategories($target);
        $searchResultInTopics = $topicObj->searchInTopics($target);
        $searchResultInPosts = $postObj->searchInPosts($target);

        if($searchResultInCategories) {
            for($i = 0; $i < count($searchResultInCategories); $i++) {
                $positionInName = stripos($searchResultInCategories[$i]["categoryName"], $target);
                if($positionInName !== false) {
                    $searchResultInCategories[$i]["categoryName"] = str_ireplace($target, '<span style="background: yellow">' . substr($searchResultInCategories[$i]["categoryName"], $positionInName, strlen($target)) . '</span>', $searchResultInCategories[$i]["categoryName"]);
                }

                $positionInDesc = stripos($searchResultInCategories[$i]["categoryDescription"], $target);
                if($positionInDesc !== false) {
                    $searchResultInCategories[$i]["categoryDescription"] = str_ireplace($target, '<span style="background: yellow">' . substr($searchResultInCategories[$i]["categoryDescription"], $positionInDesc, strlen($target)) . '</span>', $searchResultInCategories[$i]["categoryDescription"]);
                }
            }
            
            $_SESSION["searchResultInCategories"] = $searchResultInCategories;
        } else {
            unset($_SESSION["searchResultInCategories"]);
        }

        if($searchResultInTopics) {
            for($i = 0; $i < count($searchResultInTopics); $i++) {
                $positionInName = stripos($searchResultInTopics[$i]["topicName"], $target);
                if($positionInName !== false) {
                    $searchResultInTopics[$i]["topicName"] = str_ireplace($target, '<span style="background: yellow">' . substr($searchResultInTopics[$i]["topicName"], $positionInName, strlen($target)) . '</span>', $searchResultInTopics[$i]["topicName"]);
                }

                $positionInText = stripos($searchResultInTopics[$i]["topicText"], $target);
                if($positionInText !== false) {
                    $searchResultInTopics[$i]["topicText"] = str_ireplace($target, '<span style="background: yellow">' . substr($searchResultInTopics[$i]["topicText"], $positionInText, strlen($target)) . '</span>', $searchResultInTopics[$i]["topicText"]);
                }
            }
            
            $_SESSION["searchResultInTopics"] = $searchResultInTopics;
        } else {
            unset($_SESSION["searchResultInTopics"]);
        }

        if($searchResultInPosts) {
            for($i = 0; $i < count($searchResultInPosts); $i++) {
                $positionInText = stripos($searchResultInPosts[$i]["text"], $target);
                if($positionInText !== false) {
                    $searchResultInPosts[$i]["text"] = str_ireplace($target, '<span style="background: yellow">' . substr($searchResultInPosts[$i]["text"], $positionInText, strlen($target)) . '</span>', $searchResultInPosts[$i]["text"]);
                }
            }
            
            $_SESSION["searchResultInPosts"] = $searchResultInPosts;
        } else {
            unset($_SESSION["searchResultInPosts"]);
        }

        $result["data_type"] = 1;
        $result["data_value"] = "success";

        echo json_encode($result);
        exit;

        
    }

    if(isset($_POST["deletePostAttachmentImage"])) {
        $selectedID = htmlspecialchars(trim($_POST["selectedAttachmentID"]));

        $selectedImageName = $postObj->getAttachmentImage($selectedID);

        if($selectedImageName) {
            if($postObj->deleteAttachmentImage($selectedID)) {
                
                unlink("../../public/files/upload/" . $selectedImageName["postAttachmentName"]);
                
                $result["data_type"] = 1;
                $result["data_value"] = "The selected image was deleted";
    
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
            $result["data_value"] = "An error occured";

            echo json_encode($result);
            exit;
        }
    }

    if(isset($_POST["deleteTopicAttachmentImage"])) {
        $selectedID = htmlspecialchars(trim($_POST["selectedAttachmentID"]));

        $selectedImageName = $topicObj->getAttachmentImage($selectedID);

        if($selectedImageName) {
            if($topicObj->deleteAttachmentImage($selectedID)) {
                
                unlink("../../public/files/upload/" . $selectedImageName["topicAttachmentName"]);
                
                $result["data_type"] = 1;
                $result["data_value"] = "The selected image was deleted";
    
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
            $result["data_value"] = "An error occured";

            echo json_encode($result);
            exit;
        }
    }
    
?>