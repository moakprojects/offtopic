<?php
    class General {

        // get rules and regulations from the database
        function getRulesAndRegulationsData() {

            global $db;
            global $rulesAndRegulationsQuery;
    
            if($rulesAndRegulationsQuery) {
                $rulesAndRegulationsQuery->execute();
    
                $rulesAndRegulationsData = $rulesAndRegulationsQuery->fetchall(PDO::FETCH_ASSOC);
                
                return $rulesAndRegulationsData;
            } else {
                header("Location: /error");
                exit;
            }
        }

        // get contact information from the database
        function getContactInformation() {

            global $db;
            global $contactInformationQuery;
    
            if($contactInformationQuery) {
                $contactInformationQuery->execute();
    
                $contactInformationData = $contactInformationQuery->fetchall(PDO::FETCH_ASSOC);
                
                return $contactInformationData;
            } else {
                header("Location: /error");
                exit;
            }
        }

        // get description of the site from the database
        function getDescriptionOfTheSite() {

            global $db;
            global $descriptionOfTheSiteQuery;
    
            if($descriptionOfTheSiteQuery) {
                
                return $db->query('select @out as description')->fetch(PDO::FETCH_ASSOC);
            } else {
                header("Location: /error");
                exit;
            }
        }

        // if the admin modified the description we save it
        function saveDescriptionChanges($aboutUs) {
            global $db;
            global $modifyDescriptionOfTheSiteQuery;
    
            if($modifyDescriptionOfTheSiteQuery) {
                $modifyDescriptionOfTheSiteQuery->bindParam(':aboutUs', $aboutUs);
                $modifyDescriptionOfTheSiteQuery->execute();
    
                return true;
            } else {
                return false;
            }
        }

        // if the admin modified the contact information we save it
        function saveContactChanges($generalText, $phoneNumber, $location) {
            global $db;
            global $modifyContactInformationQuery;
    
            if($modifyContactInformationQuery) {
                $modifyContactInformationQuery->bindParam(':generalText', $generalText);
                $modifyContactInformationQuery->bindParam(':phoneNumber', $phoneNumber);
                $modifyContactInformationQuery->bindParam(':location', $location);
                $modifyContactInformationQuery->execute();
    
                return true;
            } else {
                return false;
            }
        }

        // if the admin modified the rules we save it
        function saveRulesChanges($generalTxt, $acceptanceOfTerms, $modificationOfTerms, $rulesAndConduct, $termination, $integration) {
            global $db;
            global $modifyRulesAndRegulationsQuery;
    
            if($modifyRulesAndRegulationsQuery) {
                $modifyRulesAndRegulationsQuery->bindParam(':generalTxt', $generalTxt);
                $modifyRulesAndRegulationsQuery->bindParam(':acceptanceOfTerms', $acceptanceOfTerms);
                $modifyRulesAndRegulationsQuery->bindParam(':modificationOfTerms', $modificationOfTerms);
                $modifyRulesAndRegulationsQuery->bindParam(':rulesAndConduct', $rulesAndConduct);
                $modifyRulesAndRegulationsQuery->bindParam(':termination', $termination);
                $modifyRulesAndRegulationsQuery->bindParam(':integration', $integration);
                $modifyRulesAndRegulationsQuery->execute();
    
                return true;
            } else {
                return false;
            }
        }
        
    }
?>