<?php

class Category {

    public $categoryName;

    function __get($name) {
        return $this->$name;
    }

    // request categories data from database
    function getAllCategoryData() {

        global $db;
        global $allCategoryQuery;

        try {
            $allCategoryQuery->execute();
            $categoryData = $allCategoryQuery->fetchall(PDO::FETCH_ASSOC);
            $allCategoryQuery->closeCursor();
            
            if($categoryData) {
                return $categoryData;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            header("Location: /error");
            exit;
        }
    }

    // request selected category data from database
    function getCategoryData($categoryID) {

        global $db;
        global $categoryQuery;

        try {
            $categoryQuery->bindParam(':categoryID', $categoryID);
            $categoryQuery->execute();
            $categoryData = $categoryQuery->fetch(PDO::FETCH_ASSOC);
            
            if($categoryData) {
                return $categoryData;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            header("Location: /error");
            exit;
        }
    }

     // add new category
     function uploadNewCategory($categoryName, $categoryDescription, $attachment) {
        global $db;
        global $newCategoryQuery;

        try {
            $newCategoryQuery->bindParam(':categoryName', $categoryName);
            $newCategoryQuery->bindParam(':categoryDescription', $categoryDescription);
            $newCategoryQuery->bindParam(':thumbnail', $attachment);
            $newCategoryQuery->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // delete selected category
    function deleteCategory($categoryID) {
        global $db;
        global $deleteCategoryQuery;
        global $deleteTopicByCategoryQuery;
        global $deletePostByCategoryQuery;

        try {
            $db->beginTransaction();

            $deletePostByCategoryQuery->bindParam(':categoryID', $categoryID);
            $deletePostByCategoryQuery->execute();

            $deleteTopicByCategoryQuery->bindParam(':categoryID', $categoryID);
            $deleteTopicByCategoryQuery->execute();            

            $deleteCategoryQuery->bindParam(':categoryID', $categoryID);
            $deleteCategoryQuery->execute();

            $db->commit();

            return true;
        } catch (PDOException $e) {
            $db->rollback();
            return false;
        }
    }

    // request category data for the sidebar from database
    function getCategoryDataForSideBar($userID) {

        global $db;
        global $sideBarCategoriesQuery;

        if(isset($sideBarCategoriesQuery)) {
            $sideBarCategoriesQuery -> bindParam(':userID', $userID);
            $sideBarCategoriesQuery->execute();

            return $sideBarCategoriesData = $sideBarCategoriesQuery->fetchall(PDO::FETCH_ASSOC);

        } else {
            header("Location: /error");
            exit;
        }
    }

    // request favourite categories data from database
    function getFavouriteCategoryData($userID) {

        global $db;
        global $sideBarFavouriteCategoriesQuery;

        if(isset($sideBarFavouriteCategoriesQuery)) {
            $sideBarFavouriteCategoriesQuery->bindParam(':userID', $userID);
            $sideBarFavouriteCategoriesQuery->execute();

            if($sideBarFavouriteCategoriesQuery->rowCount()) {
                $sideBarFavouriteCategoriesResult = $sideBarFavouriteCategoriesQuery->fetchall(PDO::FETCH_ASSOC);
                return $sideBarFavouriteCategoriesResult;
            } else {
                return false;
            }

        } else {
            header("Location: /error");
            exit;
        }
    }

    //check selected data to avoid errors
    function checkCategory($selectedCategoryID) {
        
        global $db;
        global $checkCategoryQuery;

        if(isset($checkCategoryQuery)) {
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

    // bind category data to modify favourite category table
    function likeCategory($userID, $categoryID) {
        global $db;
        global $likeCategoryQuery ;

        if($likeCategoryQuery ) {

            $likeCategoryQuery->bindParam(":userID", $userID);
            $likeCategoryQuery->bindParam(":categoryID", $categoryID);
            $likeCategoryQuery ->execute();

            return true;
        } else {
            return false;
        }
    }

    // bind category data to modify favourite category table
    function dislikeCategory($userID, $categoryID) {
        global $db;
        global $dislikeCategoryQuery;

        if($dislikeCategoryQuery) {

            $dislikeCategoryQuery->bindParam(":userID", $userID);
            $dislikeCategoryQuery->bindParam(":categoryID", $categoryID);
            $dislikeCategoryQuery ->execute();

            return true;
        } else {
            return false;
        }
    }

    // check that the user added to favourite the category or not to change the appearance 
    function checkLikedCategories($userID, $categoryID) {
        global $db;
        global $checkFavouriteCategoryQuery;

        if(isset($checkFavouriteCategoryQuery)) {

            $checkFavouriteCategoryQuery->bindParam(":userID", $userID);
            $checkFavouriteCategoryQuery->bindParam(":categoryID", $categoryID);
            $checkFavouriteCategoryQuery ->execute();

            return $checkFavouriteCategoryQuery->rowCount();
        } else {
            return false;
        }
    }

    /* modify the data of a selected category */
    function modifyCategoryData($categoryID, $categoryName, $categoryDescription, $thumbnail) {
        global $db;
        global $modifyCategoryQuery;

        if($modifyCategoryQuery) {

            $modifyCategoryQuery->bindParam(":categoryID", $categoryID);
            $modifyCategoryQuery->bindParam(":categoryName", $categoryName);
            $modifyCategoryQuery->bindParam(":categoryDescription", $categoryDescription);
            $modifyCategoryQuery->bindParam(":thumbnail", $thumbnail);
            $modifyCategoryQuery ->execute();

            return true;
        } else {
            return false;
        }
    }
}