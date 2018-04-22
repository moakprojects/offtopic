<?php
    if(isset($_SESSION["selectedCategoryID"])) {
        $topicObj = new Topic();
        $categoryObj = new Category();
        $categoryID = htmlspecialchars(trim($_SESSION["selectedCategoryID"]));
        $topics = $topicObj -> getTopicData($categoryID, $categoryObj);
        $categoryName = $categoryObj->categoryName;
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8">
            <div class="titleSection">
                <h2>Explore Topics</h2>
                <div class="breadCrumb">
                    <a href="/home">Home</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories">Categories</a>
                    <i class="material-icons">chevron_right</i>
                    <?php echo "<a href='/categories/" . $_SESSION['selectedCategoryID'] . "'>$categoryName</a>"; ?>
                </div>
                <div id="categoryLikeButtonContainer">
                    <a data-position="bottom" data-delay="50" data-tooltip="Add to favourites" class="btn-floating btn-large waves-effect waves-light categoryLikeButton tooltipped <?php echo (!isset($_SESSION['user']) ? 'hide' : ''); ?>" 
                    onclick="likeCategory(
                    <?php echo $_SESSION["user"]["userID"] . ", " . $_SESSION['selectedCategoryID'] . ", " . 
                    ($categoryObj->checkLikedCategories($_SESSION["user"]["userID"], $_SESSION["selectedCategoryID"]) > 0 
                        ? "'remove')\"> <i class=\"fas "
                        : "'add')\"> <i class=\"far "); 
                    ?> fa-heart fa-lg"></i></a>
                </div>
                <h3><?php echo $categoryName; ?></h3>
                <hr>
            </div>
            <?php
                
                foreach($topics as $topic) {
            ?>
            <div class="topic">
                <?php echo "<a href='/topics/" . $topic["topicID"] . "'>"; ?>
                    <div class="col s1 userImageContainer">
                        <div class="row">
                        <?php
                            echo "<a href='/profile/" . $topic["username"] . "'><img src=\"";
                            if($topic["profileImage"] == 'defaultAvatar.png') {
                                echo '/public/images/content/defaultAvatar.png';
                            } else {
                                echo "/public/images/upload/" . $topic["profileImage"] . "\"";
                            }
                            echo "\" class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip=" . $topic["username"] . "></a>";
                        ?> 
                        </div>
                    </div>
                    <div class="col s9 topicContainer">
                        <h3><?php echo $topic["shortTopicName"]?></h3>
                        <p class="topicDescription"><?php echo $topic["topicDescription"]; ?></p>
                        <?php 
                        if($topic["periodName"] !== "none") {
                            echo "<img src='/public/images/content/semesterMarker/" . $topic['periodName'] . ".png' class='periodImage'>";
                        }
                        ?>
                        <p class="viewTopic"><a href="/topics/<?php echo $topic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <div class="topicCreated">Created at: <?php echo $topic["createdAt"]?></div>
                    </div>
                    <div class="col s2">
                        <div class="likes">
                            <span class="favourite col s6"><i class="fas fa-heart"></i> <?php echo ($topic["numberOfLikes"] ? $topic["numberOfLikes"] : "0"); ?> likes</span>
                        </div>
                        <div class="comments">
                            <div class="commentbg">
                                <span><?php echo ($topic["numberOfPosts"] ? $topic["numberOfPosts"] : "0"); ?></span>
                                <div class="mark">

                                </div>
                            </div>
                        </div>
                        <div class="time">
                            <span class="iconTitle">Last comment</span>
                            <br>
                            <i class="far fa-clock fa-sm"></i>
                            <span><?php echo $topic["lastPostElapsedTime"]; ?> ago</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php
                }
            ?>
        </div>
        <div class="col s4">
            <?php 
                include "resources/sections/sideBarLoginBlock.php"; 
                include "resources/sections/sideBarCategoryListBlock.php";
            ?>
        </div>
    </div>
</div>
<?php
    } else {
        header("Location: /error");
        exit;
    }
?>