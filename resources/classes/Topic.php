<?php

class Topic {

    function __construct() {

    }

    // request topic data from database
    function getAllTopic() {
        global $db;
        global $allTopicQuery;

        if($allTopicQuery) {
            $allTopicQuery->execute();

            $allTopicData = $allTopicQuery->fetchall(PDO::FETCH_ASSOC);

                for($i = 0; $i < count($allTopicData); $i++) {
                    $allTopicData[$i]["lastPostElapsedTime"] = $this->calculateTimeDifferences($allTopicData[$i]["latestPost"]);
                }

            return $allTopicData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    // request topic data from database based on category id
    function getTopicData($selectedCategoryId, $categoryObj) {

        if($categoryObj -> checkCategory($selectedCategoryId) > 0) {
            
            global $db;
            global $topicQuery;

            if($topicQuery) {
                $topicQuery -> bindParam(":categoryID", $selectedCategoryId);
                $topicQuery -> execute();

                $topicData = $topicQuery->fetchall(PDO::FETCH_ASSOC);

                for($i = 0; $i < count($topicData); $i++) {
                    
                    $topicData[$i]["topicDescription"] = $this->textTrimmer($topicData[$i]["topicText"], 162);
                    $topicData[$i]["shortTopicName"] = $this->textTrimmer($topicData[$i]["topicName"], 53);

                    $topicData[$i]["lastPostElapsedTime"] = $this->calculateTimeDifferences($topicData[$i]["latestPost"]);
                }

                return $topicData;

            } else {
                header("Location: /error");
                exit;
            }
        } else {
            header("Location: /error");
            exit;
        }
    }

    // trim the long text depending on parameters
    function textTrimmer($longText, $length) {
        if(strlen($longText) > $length) {
                        
            return $cuttedTopicText = substr($longText, 0, $length) . "...";  
        } else {
            return $longText; 
        }
    }

    // calculate time difference between a specific time and now 
    function calculateTimeDifferences($latest) {
        $latestTime = new DateTime($latest);
        $now = new DateTime('now');
        $diff = $latestTime -> diff($now);

        if($diff -> y > 1 ) {
            return $difference = $diff -> y . " years";
        } else if ($diff -> y == 1 ){
            return $difference =  $diff -> y . " year";
        } else if ($diff -> m > 1 ){
            return $difference =  $diff -> m . " months";
        } else if ($diff -> m == 1 ){
            return $difference =  $diff -> m . " month";
        } else if ($diff -> d > 1 ){
            return $difference =  $diff -> d . " days";
        } else if ($diff -> d == 1 ){
            return $difference =  $diff -> d . " day";
        } else if ($diff -> h > 1 ){
            return $difference =  $diff -> h . " hours";
        } else if ($diff -> h == 1 ){
            return $difference =  $diff -> h . " hour";
        } else if ($diff -> i > 1 ){
            return $difference =  $diff -> i . " mins";
        } else {
            return $difference =  "1 min";
        }
    }

    // request selected topic information
    function getSelectedTopic($selectedTopicID) {
        global $db;
        global $selectedTopicQuery;

        try {
            $selectedTopicID = htmlspecialchars(trim($selectedTopicID));
            $selectedTopicQuery->bindParam(":topicID", $selectedTopicID);
            $selectedTopicQuery->execute();

            if($selectedTopicQuery->rowCount() > 0) {
                return $selectedTopicQuery->fetch(PDO::FETCH_ASSOC);
            } else {
                header("Location: /error");
                exit;
            }
        } catch(PDOException $e) {
            header("Location: /error");
            exit;
        }
    }

    //get attached files for the topic
    function getAttachedFiles($attachedCode) {
        global $db;
        global $attachedFilesQuery;

        try {
            $attachedFilesQuery->bindParam(":attachedFileCode", $attachedCode);
            $attachedFilesQuery->execute();

            if($attachedFilesQuery->rowCount() > 0) {
                return $attachedFilesQuery->fetchall(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch(PDOException $e) {
            return false;
        }
    }

    // request latest topic information for what's new
    function getLatestTopics() {
        global $db;
        global $latestTopicsQuery;

        if($latestTopicsQuery) {
            $latestTopicsQuery->execute();

            $latestTopicsData = $latestTopicsQuery->fetchall(PDO::FETCH_ASSOC);

            for($i = 0; $i < count($latestTopicsData); $i++) {
                
                $latestTopicsData[$i]["topicDescription"] = $this->textTrimmer($latestTopicsData[$i]["topicText"], 162);
                $latestTopicsData[$i]["shortTopicName"] = $this->textTrimmer($latestTopicsData[$i]["topicName"], 53);

                $latestTopicsData[$i]["latestTopicElapsedTime"] = $this->calculateTimeDifferences($latestTopicsData[$i]["createdAt"]);
            }

            return $latestTopicsData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    //modify favouritetopic table 
    function likeTopic($userID, $topicID) {
        global $db;
        global $likeTopicQuery ;

        if($likeTopicQuery ) {

            $likeTopicQuery->bindParam(":userID", $userID);
            $likeTopicQuery->bindParam(":topicID", $topicID);
            $likeTopicQuery ->execute();

            return true;
        } else {
            return false;
        }
    }

    //modify favouritelike table
    function dislikeTopic($userID, $topicID) {
        global $db;
        global $dislikeTopicQuery ;

        if($dislikeTopicQuery ) {

            $dislikeTopicQuery->bindParam(":userID", $userID);
            $dislikeTopicQuery->bindParam(":topicID", $topicID);
            $dislikeTopicQuery ->execute();

            return true;
        } else {
            return false;
        }
    }

    // check that the user liked the topic or not to change the appearance 
    function checkLikedTopics($userID, $topicID) {
        global $db;
        global $checkFavouriteTopicQuery;

        if(isset($checkFavouriteTopicQuery)) {

            $checkFavouriteTopicQuery->bindParam(":userID", $userID);
            $checkFavouriteTopicQuery->bindParam(":topicID", $topicID);
            $checkFavouriteTopicQuery ->execute();

            return $checkFavouriteTopicQuery->rowCount();
        } else {
            return false;
        }
    }

    // request hot topic information
    function getHotTopics() {
        global $db;
        global $hotTopicsQuery;

        if(isset($hotTopicsQuery)) {

            $selectionDate = date('Y-m-d H:i:s', strtotime('-3 days'));
            
            $hotTopicsQuery->bindParam(":selectionDate", $selectionDate);
            $hotTopicsQuery ->execute();

            if($hotTopicsQuery->rowCount() > 0) {
                $hotTopicsData = $hotTopicsQuery->fetchall(PDO::FETCH_ASSOC);

                for($i = 0; $i < count($hotTopicsData); $i++) {
                    
                    $hotTopicsData[$i]["topicDescription"] = $this->textTrimmer($hotTopicsData[$i]["topicText"], 162);
                    $hotTopicsData[$i]["shortTopicName"] = $this->textTrimmer($hotTopicsData[$i]["topicName"], 53);

                    $hotTopicsData[$i]["latestPostElapsedTime"] = $this->calculateTimeDifferences($hotTopicsData[$i]["latestPost"]);
                }

                return $hotTopicsData;
            } else {
                return false;
            }
        } else {
            header("Location: /error");
            exit;
        }
    }

    function getPeriodInfo() {
        global $db;
        global $periodQuery;

        if(isset($periodQuery)) {
            
            $periodQuery ->execute();
            $periods = $periodQuery->fetchall(PDO::FETCH_ASSOC);

            return $periods;
        } else {
            header("Location: /error");
            exit;
        }
    }

    function uploadNewTopic($topicName, $topicText, $createdBy, $period, $category, $attachedFilesCode) {
        global $db;
        global $newTopicQuery;
        global $getIdOfCreatedTopicQuery;

        if(isset($newTopicQuery)) {

            try {
                $topicName = htmlspecialchars(trim($topicName));
                $topicText = htmlspecialchars(trim($topicText));
                $createdBy = htmlspecialchars(trim($createdBy));
                $period = htmlspecialchars(trim($period));
                $category = htmlspecialchars(trim($category));
                $attachedFilesCode = htmlspecialchars(trim($attachedFilesCode));

                try {
                    $db->beginTransaction();
                    
                    $newTopicQuery->bindParam(":topicName", $topicName);
                    $newTopicQuery->bindParam(":topicText", $topicText);
                    $createdAt = date("Y-m-d H:i:s");
                    $newTopicQuery->bindParam(":createdAt", $createdAt);
                    $newTopicQuery->bindParam(":createdBy", $createdBy);
                    $newTopicQuery->bindParam(":period", $period);
                    $newTopicQuery->bindParam(":category", $category);
                    $newTopicQuery->bindParam(":attachedFilesCode", $attachedFilesCode);
                    $newTopicQuery ->execute();

                    $getIdOfCreatedTopicQuery->bindParam(":createdBy", $createdBy);
                    $getIdOfCreatedTopicQuery->bindParam(":createdAt", $createdAt);
                    $getIdOfCreatedTopicQuery->execute();

                    $db->commit();

                    return $getIdOfCreatedTopicQuery->fetch(PDO::FETCH_ASSOC); 
                } catch (PDOException $ex) {
                    $db->rollback();
                    return false;
                }
            } catch (PDOException $e) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    // upload attached file information into database
    function uploadFiles($filename, $displayname, $attachedFileCode) {

        global $db;
        global $fileUploadQuery;

        if(isset($fileUploadQuery)) {
            $fileUploadQuery->bindParam(':attachmentName', $filename);
            $fileUploadQuery->bindParam(':displayName', $displayname);
            $fileUploadQuery->bindParam(':attachedFileCode', $attachedFileCode);

            $fileUploadQuery->execute();

            return true;
        } else {
            return false;
        }
    }
}