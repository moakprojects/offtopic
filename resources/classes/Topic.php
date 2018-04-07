<?php

class Topic {
    private $topicID;
    private $topicName;
    private $topicText;
    private $createdAt;
    private $createdBy;

    private $numberOfLikes;
    private $numberOfPosts;
    private $lastCommentTimeDifference;

    function __construct() {

    }

    function getTopicData() {
        
        global $db;
        include "database/selection.php";

        if($topicQuery) {
            $topicQuery -> execute();

            $topicData = $topicQuery->fetchall(PDO::FETCH_ASSOC);

            for($i = 0; $i < count($topicData); $i++) {
                
                if(strlen($topicData[$i]["topicText"]) > 162) {
                    
                    $cuttedTopicText = substr($topicData[$i]["topicText"], 0, 162);

                    $topicData[$i]["topicDescription"] = $cuttedTopicText . "...";    
                } else {
                    $topicData[$i]["topicDescription"] = $topicData[$i]["topicText"]; 
                }

                if(strlen($topicData[$i]["topicName"]) > 53) {
                    
                    $cuttedTopicName = substr($topicData[$i]["topicName"], 0, 53);

                    $topicData[$i]["shortTopicName"] = $cuttedTopicName . "...";    
                } else {
                    $topicData[$i]["shortTopicName"] = $topicData[$i]["topicName"]; 
                }

                $latestPost = new DateTime($topicData[$i]["latestPost"]);
                $now = new DateTime('now');
                $diff = $latestPost -> diff($now);

                if($diff -> y > 1 ) {
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> y . " years";
                } else if ($diff -> y == 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> y . " year";
                } else if ($diff -> m > 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> m . " months";
                } else if ($diff -> m == 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> m . " month";
                } else if ($diff -> d > 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> d . " days";
                } else if ($diff -> d == 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> d . " day";
                } else if ($diff -> h > 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> h . " hours";
                } else if ($diff -> h == 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> h . " hour";
                } else if ($diff -> i > 1 ){
                    $topicData[$i]["lastPostElapsedTime"] = $diff -> i . " mins";
                } else {
                    $topicData[$i]["lastPostElapsedTime"] = "1 min";
                }
            }

            return $topicData;

        } else {
            header("Location: /error");
            exit;
        }
    }

    function getNumberOfLikes() {
        if($numberOfTopicLikesQuery) {

            $numberOfTopicLikesQuery -> execute();
            $numberOfTopicLikesResult = $numberOfTopicLikesQuery -> fetchall(PDO::FETCH_ASSOC);

            for($i = 0; $i < count($this -> topicID); $i++) {
                if(in_array($i + 1, $numberOfTopicLikesResult["topicID"])) {
                    array_push($numberOfLikes, $numberOfTopicLikesResult["numberOfLikes"]);
                } else {
                    array_push($numberOfLikes, 0);
                }
            }
            
        } else {
            header("Location: /error");
            exit;
        }
    }

    function getNumberOfPosts() {
        if($numberOfPostsQuery) {

            $numberOfPostsQuery -> execute();
            $numberOfPostsResult = $numberOfPostsQuery -> fetchall(PDO::FETCH_ASSOC);

            for($i = 0; $i < count($this -> topicID); $i++) {
                if(in_array($i + 1, $numberOfPostsResult["topicID"])) {
                    array_push($numberOfPosts, $numberOfPostsResult["numberOfPosts"]);
                } else {
                    array_push($numberOfPosts, 0);
                }
            }
            
        } else {
            header("Location: /error");
            exit;
        }
    }

    function getLastCommentTimeDifference() {
        if($lastCommentTimeDifferenceQuery) {

            $lastCommentTimeDifferenceQuery -> execute();
            $lastCommentTimeDifferenceResult = $lastCommentTimeDifferenceQuery -> fetchall(PDO::FETCH_ASSOC);

            foreach($lastCommentTimeDifferenceResult as $postedOn) {
                $postTime = new DateTime($postedOn["postedOn"]);
                $now = new DateTime('now');
                $diff = $postTime -> diff($now);

                array_push($lastCommentTimeDifference, $diff);
            }
            
        } else {
            header("Location: /error");
            exit;
        }
    }
}