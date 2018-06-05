<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Search</h2>
            <div class="breadCrumb">
                <a href="/home">Home</a>
                <i class="material-icons">chevron_right</i>
                <a href="/search">Search</a>
            </div>
            <div class="searchResultContainer">
                <div class="row noMargin">
                    <h3>Categories</h3>
                    <hr>
                    <div class="categoryContainer">
                        <?php
                            if(isset($_SESSION["searchResultInCategories"]) && $_SESSION["searchResultInCategories"]) {
                                foreach($_SESSION["searchResultInCategories"] as $category) {
                        ?>
                            <p class="categoryName"><?php echo $category["categoryName"]; ?></p>
                            <p class="noMargin categoryDescription"><?php echo $category["categoryDescription"]; ?></p>
                            <p class="right-align goToCategory"><a href="/categories/<?php echo $category["categoryID"]; ?>">View the category <i class="fas fa-angle-double-right fa-sm align-right"></i></a></p>
                        <?php 
                                }
                                unset($_SESSION["searchResultInCategories"]);
                            } else {
                        ?>
                            <p>No matching search found</p>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="row noMargin">
                    <h3>Topics</h3>
                    <hr>
                    <div class="topicContainer">
                        <?php
                            if(isset($_SESSION["searchResultInTopics"]) && $_SESSION["searchResultInTopics"]) {
                                foreach($_SESSION["searchResultInTopics"] as $topic) {
                        ?>
                            <p class="topicName"><?php echo $topic["topicName"]; ?></p>
                            <p class="noMargin topicDescription"><?php echo $topic["topicText"]; ?></p>
                            <p class="right-align goToTopic"><a href="/topics/<?php echo $topic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right fa-sm align-right"></i></a></p>
                        <?php 
                                }
                                unset($_SESSION["searchResultInTopics"]);
                            } else {
                        ?>
                            <p>No matching search found</p>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="row noMargin">
                    <h3>Posts</h3>
                    <hr>
                    <div class="topicContainer">
                        <?php
                            if(isset($_SESSION["searchResultInPosts"]) && $_SESSION["searchResultInPosts"]) {
                                foreach($_SESSION["searchResultInPosts"] as $post) {
                        ?>
                            <p class="noMargin postText"><?php echo $post["text"]; ?></p>
                            <p class="right-align goToPost"><a href="/posts/<?php echo $post["postID"]; ?>">View the post <i class="fas fa-angle-double-right fa-sm align-right"></i></a></p>
                        <?php 
                                }
                                unset($_SESSION["searchResultInPosts"]);
                            } else {
                        ?>
                            <p>No matching search found</p>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
