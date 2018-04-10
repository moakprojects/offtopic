<?php

class Post {   

    function __construct() {
        
    }

    function getPostData($selectedTopicID) {
        global $db;
        global $postQuery;

        if($postQuery) {
            $selectedTopicID = htmlspecialchars(trim($selectedTopicID));
            $postQuery -> bindParam(":topicID", $selectedTopicID);
            $postQuery->execute();

            $postData = $postQuery->fetchall(PDO::FETCH_ASSOC);
            
            return $postData;
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
    
    function getLatestPosts() {
        global $db;
        global $latestPostsQuery;

        if($latestPostsQuery) {
            $latestPostsQuery->execute();

            $latestPostsData = $latestPostsQuery->fetchall(PDO::FETCH_ASSOC);
            
            for($i = 0; $i < count($latestPostsData); $i++) {

                $latestPostsData[$i]["shortTopicName"] = $this->textTrimmer($latestPostsData[$i]["topicName"], 18); 
                $latestPostsData[$i]["shortPostText"] = $this->textTrimmer($latestPostsData[$i]["text"], 218); 

                $postedOn = new DateTime($latestPostsData[$i]["postedOn"]);
                $latestPostsData[$i]["monthDay"] = $postedOn -> format('M, j') . "<sup>" . $postedOn -> format('S') . "</sup>";
                $latestPostsData[$i]["time"] = $postedOn -> format('h:ia');
                $valami = "";
            }

            return $latestPostsData;
        } else {
            header("Location: /error");
            exit;
        }
    }
}