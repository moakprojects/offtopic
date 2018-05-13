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
                <?php if(isset($_SESSION["user"])) { ?>
                    <div id="categoryLikeButtonContainer">
                        <a data-position="bottom" data-delay="50" data-tooltip="Add to favourites" class="btn-floating btn-large waves-effect waves-light categoryLikeButton tooltipped <?php echo (!isset($_SESSION['user']) ? 'hide' : ''); ?>" 
                        onclick="likeCategory(
                        <?php echo $_SESSION["user"]["userID"] . ", " . $_SESSION['selectedCategoryID'] . ", " . 
                        ($categoryObj->checkLikedCategories($_SESSION["user"]["userID"], $_SESSION["selectedCategoryID"]) > 0 
                            ? "'remove')\"> <i class=\"fas "
                            : "'add')\"> <i class=\"far "); 
                        ?> fa-heart fa-lg"></i></a>
                    </div>
                <?php } ?>
                <h3><?php echo $categoryName; ?></h3>
                <hr>
            </div>
            <?php
                if($topics) {
                    foreach($topics as $topic) {
            ?>
            <div class="topic">
                <?php echo "<a href='/topics/" . $topic["topicID"] . "'>"; ?>
                    <div class="col s1 userImageContainer">
                        <div class="row">
                        <?php
                            if($topic['profileImage'] == 'defaultAvatar.png') {
                                echo "<a href='/profile/" . $topic["username"] . "'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'></a>";
                            } else if ($topic['profileImage'] == 'admin.png') {
                                echo "<img src='/public/images/content/admin.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'>";
                            } else if ($topic['profileImage'] == 'anonymous.png') {
                                echo "<img src='/public/images/content/anonymous.png' class=' tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'>";
                            } else {
                                echo "<a href='/profile/" . $topic["username"] . "'><img src='/public/images/upload/" . $topic['profileImage'] ."' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'></a>";
                            }
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
                            <span class="favourite col s6"><i class="fas fa-heart"></i> <?php echo ($topic["numberOfLikes"] ? $topic["numberOfLikes"] : "0"); ?> follows</span>
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
             } else {
            ?>
                <div class="topic emptyCategoryCard">
                    <p>There is no topic in this category. If you have any <?php echo $categoryName ?> related question, just create a <a href="/new-topic">new topic.</a></p>
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