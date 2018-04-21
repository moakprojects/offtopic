<div id="categorySideBarContainer">
<div class="sideBarBlock">
    <h4><a href="/categories" class="topicCategoriesTitle">Topic Categories</a></h4>
    <div class="line"></div>
    <div class="categoryContainer">
        <?php
            $sideBarCategoryObj = new Category();
            if(!isset($_SESSION["user"])) {
                $sideBarCategories = $sideBarCategoryObj -> getCategoryData();
            } else {
                $sideBarCategories = $sideBarCategoryObj -> getCategoryDataForSideBar($_SESSION["user"]["userID"]);
                $sideBarFavouriteCategories = $sideBarCategoryObj -> getFavouriteCategoryData($_SESSION["user"]["userID"]);

                if($sideBarFavouriteCategories) {
                    $sideBarFavouriteCategories = array_slice($sideBarFavouriteCategories, 0, 5, true);
        ?>
        <p class="center-align" style="font-size: 15px;">Favourite Categories</p>
        <ul>
            <?php
                foreach($sideBarFavouriteCategories as $sideBarFavouriteCategory) {
            ?>
            <li><a href="/categories/<?php echo $sideBarFavouriteCategory["categoryID"]; ?>"><?php echo $sideBarFavouriteCategory["categoryName"]; ?></a><span data-badge-caption="" class="new badge blue"><?php echo ($sideBarFavouriteCategory["numberOfTopics"] ? $sideBarFavouriteCategory["numberOfTopics"] : "0"); ?></span></li>
            <?php
                }
            ?>
        </ul>
        <?php
                }
            }
                if($sideBarCategories) {
        ?>
        <p class="center-align" style="font-size: 15px;">Recommended Categories</p>
        <ul>
            <?php  
                foreach($sideBarCategories as $sideBarCategory) {
            ?>
            <li><a href="/categories/<?php echo $sideBarCategory["categoryID"]; ?>"><?php echo $sideBarCategory["categoryName"]; ?></a><span data-badge-caption="" class="new badge blue"><?php echo ($sideBarCategory["numberOfTopics"] ? $sideBarCategory["numberOfTopics"] : "0"); ?></span></li>
            <?php
                }
            ?>
        </ul>
        <?php
                }
        ?>
        <a href="/categories">View the categories <i class="fas fa-angle-double-right"></i></a>
    </div>
</div>
</div>