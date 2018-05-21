<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Explore Categories</h2>
            <div class="breadCrumb">
                <a href="/home">Home</a>
                <i class="material-icons">chevron_right</i>
                <a href="/categories">Categories</a>
            </div>
            <div class="categoryCardContainer">
                <?php
                    $categoryObj = new Category();
                    if(!isset($_SESSION["user"])) {
                        $categories = $categoryObj -> getAllCategoryData();
                    } else {
                        $favouriteCategories = $categoryObj -> getFavouriteCategoryData($_SESSION["user"]["userID"]);
                        $categories = $categoryObj -> getFurtherCategoryData($_SESSION["user"]["userID"]);

                        if($favouriteCategories) {
                ?>
                <div class="row noMargin">
                    <h3>Favourite categories</h3>
                    <hr>
                <?php 
                    foreach($favouriteCategories as $favouriteCategory) {
                ?>
                    <div class="post-module col s5">
                        <?php echo "<a href='/categories/" . $favouriteCategory["categoryID"] . "'>"; ?>
                            <!-- Thumbnail-->
                            <div class="thumbnail">
                                <div class="topicCounter">
                                    <div class="topicCounterValue"><?php echo ($favouriteCategory["numberOfTopics"] ? $favouriteCategory["numberOfTopics"] : "0"); ?></div>
                                    <div class="topicCounterTitle">Topics</div>
                                </div>
                                <?php echo "<img src='/public/images/content/categoryThumbnail/" . $favouriteCategory["thumbnail"] . "'>"; ?>
                            </div>
                            <!-- Post Content-->
                            <div class="post-content">
                                <div class="row noMargin">
                                    <div class="col s9 noPadding">
                                        <h1 class="title"><?php echo $favouriteCategory["categoryName"]; ?></h1>
                                    </div>
                                    <?php if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) { ?>
                                    <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                        <a onclick="adminModification('category', <?php echo $favouriteCategory['categoryID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="col s1 noPadding trashIcon titleIcon center-align">
                                        <a onclick="adminDelition('categories', '.categoryCardContainer', 'category', <?php echo $favouriteCategory['categoryID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                                <p class="description"><?php echo $favouriteCategory["categoryDescription"]; ?></p>
                                <div class="post-meta row">
                                    <span class="comments col s6"><i class="fa fa-comments"></i> <?php echo ($favouriteCategory["numberOfPosts"] ? $favouriteCategory["numberOfPosts"] : "0") ; ?> posts</span>
                                    <span class="favourite col s6"><i class="fas fa-heart"></i> <?php echo ($favouriteCategory["numberOfLikes"] ? $favouriteCategory["numberOfLikes"] : "0"); ?> likes</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col s1"></div>
                <?php
                    }
                ?>
                    </div>
                    <h3>Further available categories</h3>
                    <hr>
                <?php
                        }
                    }
                    foreach($categories as $category) {
                ?>
                <div class="post-module col s5">
                    <?php echo "<a href='/categories/" . $category["categoryID"] . "'>"; ?>
                        <!-- Thumbnail-->
                        <div class="thumbnail">
                            <div class="topicCounter">
                                <div class="topicCounterValue"><?php echo ($category["numberOfTopics"] ? $category["numberOfTopics"] : "0"); ?></div>
                                <div class="topicCounterTitle">Topics</div>
                            </div>
                            <?php echo "<img src='/public/images/content/categoryThumbnail/" . $category["thumbnail"] . "'>"; ?>
                        </div>
                        <!-- Post Content-->
                        <div class="post-content">
                            <div class="row noMargin">
                                <div class="col s9 noPadding">
                                    <h1 class="title"><?php echo $category["categoryName"]; ?></h1>
                                </div>
                                <?php if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) { ?>
                                <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                    <a onclick="adminModification('category', <?php echo $category['categoryID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                        <i class="fas fa-pencil-alt fa-xs"></i>
                                    </a>
                                </div>
                                <div class="col s1 noPadding trashIcon titleIcon center-align">
                                    <a onclick="adminDelition('categories', '.categoryCardContainer', 'category', <?php echo $category['categoryID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                        <i class="fas fa-trash fa-xs"></i>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <p class="description"><?php echo $category["categoryDescription"]; ?></p>
                            <div class="post-meta row">
                                <span class="comments col s6"><i class="fa fa-comments"></i> <?php echo ($category["numberOfPosts"] ? $category["numberOfPosts"] : "0") ; ?> posts</span>
                                <span class="favourite col s6"><i class="fas fa-heart"></i> <?php echo ($category["numberOfLikes"] ? $category["numberOfLikes"] : "0"); ?> likes</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col s1"></div>
                <?php
                    }
                ?>
            </div>
        </div>
        <div class="col s4 sideBarContainer">
            <div class="sideBar">
                <?php 
                    include "resources/sections/sideBarLoginBlock.php";
                    include "resources/sections/sideBarStickyPost.php";  
                    include "resources/sections/sideBarLatestPostsBlock.php"; 
                ?>
            </div>
        </div>
    </div>
</div>