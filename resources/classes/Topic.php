<?php

class Topic {

    function __construct() {

    }

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

    function textTrimmer($longText, $length) {
        if(strlen($longText) > $length) {
                        
            return $cuttedTopicText = substr($longText, 0, $length) . "...";  
        } else {
            return $longText; 
        }
    }

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

    function getSelectedTopic($selectedTopicID) {
        global $db;
        global $selectedTopicQuery;

        if($selectedTopicQuery) {
            $selectedTopicID = htmlspecialchars(trim($selectedTopicID));
            $selectedTopicQuery->bindParam(":topicID", $selectedTopicID);
            $selectedTopicQuery->execute();

            if($selectedTopicQuery->rowCount() > 0) {
                return $selectedTopicData = $selectedTopicQuery->fetch(PDO::FETCH_ASSOC);
            } else {
                header("Location: /error");
                exit;
            }
        } else {
            header("Location: /error");
            exit;
        }
    }

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

    function getFavouriteTopics($userID) {
        global $db;
        global $favouriteTopicsQuery;

        if(isset($favouriteTopicsQuery)) {
            
            $favouriteTopicsQuery->bindParam(":userID", $userID);
            $favouriteTopicsQuery->execute();

            if($favouriteTopicsQuery->rowCount() > 0) {
                $favouriteTopicsData = $favouriteTopicsQuery->fetchall(PDO::FETCH_ASSOC);
            
                for($i = 0; $i < count($favouriteTopicsData); $i++) {
                
                    $favouriteTopicsData[$i]["topicDescription"] = $this->textTrimmer($favouriteTopicsData[$i]["topicText"], 162);
                    $favouriteTopicsData[$i]["shortTopicName"] = $this->textTrimmer($favouriteTopicsData[$i]["topicName"], 53);
    
                    $favouriteTopicsData[$i]["latestPostElapsedTime"] = $this->calculateTimeDifferences($favouriteTopicsData[$i]["latestPost"]);
                }

                return $favouriteTopicsData;
            } else {
                return false;
            }
        } else {
            header("Location: /error");
            exit;
        }
    }

    function getOwnTopics($userID) {
        global $db;
        global $ownTopicsQuery;

        if(isset($ownTopicsQuery)) {
            
            $ownTopicsQuery->bindParam(":userID", $userID);
            $ownTopicsQuery->execute();

            if($ownTopicsQuery->rowCount() > 0) {
                $ownTopicsData = $ownTopicsQuery->fetchall(PDO::FETCH_ASSOC);
            
                for($i = 0; $i < count($ownTopicsData); $i++) {
                
                    $ownTopicsData[$i]["topicDescription"] = $this->textTrimmer($ownTopicsData[$i]["topicText"], 162);
                    $ownTopicsData[$i]["shortTopicName"] = $this->textTrimmer($ownTopicsData[$i]["topicName"], 53);
    
                    $ownTopicsData[$i]["latestPostElapsedTime"] = $this->calculateTimeDifferences($ownTopicsData[$i]["latestPost"]);
                }

                return $ownTopicsData;
            } else {
                return false;
            }
        } else {
            header("Location: /error");
            exit;
        }
    }

    function getCreatedTopics($username) {
        global $db;
        global $createdTopicsQuery;

        if(isset($createdTopicsQuery)) {

            $createdTopicsQuery->bindParam(':username', $username);
            $createdTopicsQuery->execute();

            if($createdTopicsQuery->rowCount() > 0) {
                $createdTopicsData = $createdTopicsQuery->fetchall(PDO::FETCH_ASSOC);
            
                for($i = 0; $i < count($createdTopicsData); $i++) {
                
                    $createdTopicsData[$i]["topicDescription"] = $this->textTrimmer($createdTopicsData[$i]["topicText"], 200);
                    $createdTopicsData[$i]["shortTopicName"] = $this->textTrimmer($createdTopicsData[$i]["topicName"], 53);
    
                }

                return $createdTopicsData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}