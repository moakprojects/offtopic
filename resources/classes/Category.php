<?php

class Category {
    private $categoryName;
    private $categoryDescription;
    private $categoryThumbnail;

    private $numberOfTopics;
    private $numberOfLikes;
    private $numberOfPosts;

    function __construct() {

    }

    function getCategoryDatas() {
        if($categoryQuery) {
            $categoryQuery -> execute();

            while($row = $categoryQuery->fetch(PDO::FETCH_ASSOC)) {
                $this->categoryName = $row["categoryName"];
                $this->categoryDescription = $row["categoryDescription"];
                $this->categoryThumbnail = $row["categoryThumbnail"];
            }

            

        } else {
            header("Location: /error");
            exit;
        }
    }
}