<div class="container contentContainer">
    <div class="row">
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
                    $categories = $categoryObj -> getCategoryData();
                    
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
                            <h1 class="title"><?php echo $category["categoryName"]; ?></h1>
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
        <div class="col s4">
            <?php 
                include "resources/sections/sideBarLoginBlock.php"; 
                include "resources/sections/sideBarLatestPostsBlock.php"; 
            ?>
        </div>
    </div>
</div>