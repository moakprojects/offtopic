<?php
include "../../config/connection.php";
include "../../database/selection.php";
include "../../database/modification.php";
include "../classes/General.php";
$generalObj = new General();
    /* save new description data */
    if(isset($_POST["changeDescription"])) {
        if($generalObj->saveDescriptionChanges($_POST["newDescription"])) {
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
        if($generalObj->saveContactChanges($_POST["newGeneralContactText"], $_POST["newPhoneNumber"], $_POST["newLocation"])) {
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
        if($generalObj->saveRulesChanges($_POST["newGeneralRules"], $_POST["newAcceptanceOfTerms"], $_POST["newModificationOfTerms"], $_POST["newRulesAndConduct"], $_POST["newTermination"], $_POST["newIntegration"])) {
            $result["data_type"] = 1;
            $result["data_value"] = "Changes were saved";

            echo json_encode($result);
        } else {
            $result["data_type"] = 0;
            $result["data_value"] = "An error occured";

            echo json_encode($result);
        }
    }
?>