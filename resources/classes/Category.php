<?php

class Category {

    public $categoryName;   

    function __construct() {
        
    }

    function getCategoryName() {
        $categoryName = $this->categoryName;
        return $categoryName;
    }

    function getCategoryData() {

        global $db;
        include "database/selection.php";

        if($categoryQuery) {
            $categoryQuery->execute();

            $categoryData = $categoryQuery->fetchall(PDO::FETCH_ASSOC);
            
            return $categoryData;
        } else {
            header("Location: /error");
            exit;
        }
    }

    function getCategoryDataForSideBar() {

        global $db;
        include "database/selection.php";

        if($sideBarCategoryQuery) {
            $sideBarCategoryQuery->execute();

            return $sideBarCategoryData = $sideBarCategoryQuery->fetchall(PDO::FETCH_ASSOC);

        } else {
            header("Location: /error");
            exit;
        }
    }

    function checkCategory($selectedCategoryID) {
        
        global $db;
        include "database/selection.php";

        if($checkCategoryQuery) {
            $selectedCategoryID = htmlspecialchars(trim($selectedCategoryID));
            $checkCategoryQuery -> bindParam(':categoryID', $selectedCategoryID);
            $checkCategoryQuery -> execute();

            $checkCategoryData = $checkCategoryQuery -> fetch(PDO::FETCH_ASSOC);
            $this->categoryName = $checkCategoryData["categoryName"];
            return $checkCategoryResult = $checkCategoryQuery->rowCount();
            
        } else {
            header("Location: /error");
            exit;
        }
    }

    
}