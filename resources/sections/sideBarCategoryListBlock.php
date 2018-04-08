<div class="sideBarBlock">
    <h4><a href="/categories" class="topicCategoriesTitle">Topic Categories</a></h4>
    <div class="line"></div>
    <div class="categoryContainer">
        <ul>
            <?php
                $sideBarCategoryObj = new Category();
                $sideBarCategories = $sideBarCategoryObj -> getCategoryData();
                
                foreach($sideBarCategories as $sideBarCategory) {
            ?>
            <li><a href="/categories/<?php echo $sideBarCategory["categoryID"]; ?>"><?php echo $sideBarCategory["categoryName"]; ?></a><span data-badge-caption="" class="new badge blue"><?php echo ($sideBarCategory["numberOfTopics"] ? $sideBarCategory["numberOfTopics"] : "0"); ?></span></li>
            <?php
                }
            ?>
        </ul>
        <a href="/categories">View the categories <i class="fas fa-angle-double-right"></i></a>
    </div>
</div>