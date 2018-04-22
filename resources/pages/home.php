<?php
    $topicObj = new Topic();
    $userObj = new User();

    if(isset($_SESSION["user"])) {
        $favouriteTopics = $userObj->getFavouriteTopics($_SESSION["user"]["userID"]);
        $ownTopics = $userObj->getOwnTopics($_SESSION["user"]["userID"]);
    }

    $hotTopics = $topicObj->getHotTopics();
?>
<div class="container contentContainer">
    <div class="row">
        <div class="col s8"> 
            <h2>Dashboard</h2>
            <ul class="tabs tabs-transparent tabsContainer">
                <?php echo (isset($hotTopics) && $hotTopics ? "<li class='tab'><a href='#hotTopic'>Hot Topics</a></li>" : ""); ?>
                <li class="tab"><a href="#latestTopic">What's new</a></li>
                <?php echo (isset($favouriteTopics) && $favouriteTopics ? "<li class='tab'><a href='#favouriteTopic'>Followed Topics</a></li>" : ""); ?>
                <?php echo (isset($ownTopics) && $ownTopics ? "<li class='tab'><a href='#ownTopic'>Own Topics</a></li>" : ""); ?>
            </ul>
            <div id="hotTopic">
                <?php
                    if($hotTopics) {
                    foreach ($hotTopics as $hotTopic) {
                ?>
                <div class="topic">
                    <div class="col s1 userImageContainer">
                    <?php
                            echo "<a href='/profile/" . $hotTopic["username"] . "'><img src='";
                            if($hotTopic["profileImage"] == 'defaultAvatar.png') {
                                echo '/public/images/content/defaultAvatar.png';
                            } else {
                                echo "/public/images/upload/" . $hotTopic["profileImage"];
                            }
                            echo "' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $hotTopic["username"] . "'></a>";
                        ?>
                    </div>
                    <div class="col s9 topicContainer">
                        <h3><?php echo $hotTopic["shortTopicName"]; ?></h3>
                        <p class="topicDescription"><?php echo $hotTopic["topicDescription"]; ?></p>
                        <p class="viewTopic"><a href="/topics/<?php echo $hotTopic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <a href="/categories/<?php echo $hotTopic["categoryID"]; ?>"><div class="chip topicCategory"><?php echo $hotTopic["categoryName"]; ?></div></a>
                    </div>
                    <div class="col s2">
                        <div class="comments">
                            <div class="commentbg">
                                <span><?php echo ($hotTopic["numberOfPosts"] ? $hotTopic["numberOfPosts"] : "0"); ?></span>
                                <div class="mark">

                                </div>
                            </div>
                        </div>
                        <div class="time">
                            <span class="iconTitle">Last comment</span>
                            <br>
                            <i class="far fa-clock fa-sm"></i>
                            <span><?php echo $hotTopic["latestPostElapsedTime"]; ?> ago</span>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <div id="latestTopic">
                <?php
                    $latestTopics = $topicObj->getLatestTopics();
                
                    foreach($latestTopics as $latestTopic) {
                ?>
                <div class="topic">
                    <div class="col s1 userImageContainer">
                        <?php
                            echo "<a href='/profile/" . $latestTopic["username"] . "'><img src='";
                            if($latestTopic["profileImage"] == 'defaultAvatar.png') {
                                echo '/public/images/content/defaultAvatar.png';
                            } else {
                                echo "/public/images/upload/" . $latestTopic["profileImage"];
                            }
                            echo "' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $latestTopic["username"] . "'></a>";
                        ?>
                    </div>
                    <div class="col s9 topicContainer">
                        <h3><?php echo $latestTopic["shortTopicName"]; ?></h3>
                        <p class="topicDescription"><?php echo $latestTopic["topicDescription"]; ?></p>
                        <p class="viewTopic"><a href="/topics/<?php echo $latestTopic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <a href="/categories/<?php echo $latestTopic["categoryID"]; ?>"><div class="chip topicCategory"><?php echo $latestTopic["categoryName"]; ?></div></a>
                    </div>
                    <div class="col s2">
                        <div class="comments">
                            <div class="commentbg">
                                <span><?php echo ($latestTopic["numberOfPosts"] ? $latestTopic["numberOfPosts"] : "0"); ?></span>
                                <div class="mark">

                                </div>
                            </div>
                        </div>
                        <div class="time">
                            <span class="iconTitle">Created at</span>
                            <br>
                            <i class="far fa-clock fa-sm"></i>
                            <span><?php echo $latestTopic["latestTopicElapsedTime"]; ?> ago</span>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <div id="favouriteTopic" class="<?php echo (isset($favouriteTopics) && $favouriteTopics ? '' : 'hide'); ?>">
                <?php
                    if(isset($favouriteTopics)) {
                        foreach($favouriteTopics as $favouriteTopic) {
                ?>
                <div class="topic">
                    <div class="col s1 userImageContainer">
                        <?php
                            echo "<a href='/profile/" . $favouriteTopic["username"] . "'><img src='";
                            if($favouriteTopic["profileImage"] == 'defaultAvatar.png') {
                                echo '/public/images/content/defaultAvatar.png';
                            } else {
                                echo "/public/images/upload/" . $favouriteTopic["profileImage"];
                            }
                            echo "' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $favouriteTopic["username"] . "'></a>";
                        ?>
                    </div>
                    <div class="col s9 topicContainer">
                        <h3><?php echo $favouriteTopic["shortTopicName"]; ?></h3>
                        <p class="topicDescription"><?php echo $favouriteTopic["topicDescription"]; ?></p>
                        <p class="viewTopic"><a href="/topics/<?php echo $favouriteTopic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <a href="/categories/<?php echo $favouriteTopic["categoryID"]; ?>"><div class="chip topicCategory"><?php echo $favouriteTopic["categoryName"]; ?></div></a>
                    </div>
                    <div class="col s2">
                        <div class="comments">
                            <div class="commentbg">
                                <span><?php echo ($favouriteTopic["numberOfPosts"] ? $favouriteTopic["numberOfPosts"] : "0"); ?></span>
                                <div class="mark">

                                </div>
                            </div>
                        </div>
                        <div class="time">
                            <span class="iconTitle">Last comment</span>
                            <br>
                            <i class="far fa-clock fa-sm"></i>
                            <span><?php echo $favouriteTopic["latestPostElapsedTime"]; ?> ago</span>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
            </div>
            <div id="ownTopic" class="<?php echo (isset($ownTopics) && $ownTopics ? '' : 'hide'); ?>">
                <?php
                
                    if(isset($ownTopics)) {
                        foreach($ownTopics as $ownTopic) {
                ?>
                <div class="topic">
                    <div class="col s10 ownTopicContainer topicContainer">
                        <h3><?php echo $ownTopic["shortTopicName"]; ?></h3>
                        <p class="topicDescription"><?php echo $ownTopic["topicDescription"]; ?></p>
                        <p class="viewTopic"><a href="/topics/<?php echo $ownTopic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <div class="clear"></div>
                        <div class="row">
                            <div class="col s4">
                                <a href="/categories/<?php echo $ownTopic["categoryID"]; ?>"><div class="chip ownTopicCategory topicCategory"><?php echo $ownTopic["categoryName"]; ?></div></a>
                            </div>
                            <div class="col s6 offset-s2">
                                <p class="right-align createdAt"><em>Created at: <?php echo $ownTopic["createdAt"]; ?></em></p>
                            </div>
                        </div>
                    </div>
                    <div class="col s2">
                        <div class="comments">
                            <div class="commentbg">
                                <span><?php echo ($ownTopic["numberOfPosts"] ? $ownTopic["numberOfPosts"] : "0"); ?></span>
                                <div class="mark">

                                </div>
                            </div>
                        </div>
                        <div class="time">
                            <div class="row heartContainer noBottomMargin">
                                <div class="col s12">
                                    <i class="fas fa-heart fa-2x center-align"></i>
                                </div>
                            </div>
                            <div class="row noBottomMargin">
                                <div class="col s12">
                                    <p class="noMargin">34 Followers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
        <div class="col s4">
            <?php 
                include "resources/sections/sideBarLoginBlock.php"; 
                include "resources/sections/sideBarCategoryListBlock.php"; 
                include "resources/sections/sideBarLatestPostsBlock.php";
            ?>
        </div>
    </div>
</div>