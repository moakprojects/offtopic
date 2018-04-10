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

            return $selectedTopicData = $selectedTopicQuery->fetch(PDO::FETCH_ASSOC);
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
}