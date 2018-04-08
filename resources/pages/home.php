<div class="container contentContainer">
    <div class="row">
        <div class="col s8"> 
            <h2>Dashboard</h2>
            <ul class="tabs tabs-transparent tabsContainer">
                <li class="tab"><a href="#hotTopic">Hot Topics</a></li>
                <li class="tab"><a href="#latestTopic">What's new</a></li>
            </ul>
            <div id="hotTopic">
                <div class="topic">
                    <div class="col s1 userImageContainer">
                        <img src="/public/images/content/defaultAvatar.png" alt="profile picture">
                    </div>
                    <div class="col s9 topicContainer">
                        <h3>First hot example</h3>
                        <p class="topicDescription">lorem ipsum dolor sit amet, hedte hsafdsf jds ldsdsdf ldjfsd sdéflsdfsd éjédsjfs lkjshljsh  lsakshflkjash lohhlk lkhlh ll klkélkhé fgdfg fdgdffg gdfgdfg gdgdg dsgdv </p>
                        <p class="viewTopic"><a href="/discussion">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <div class="chip topicCategory">Computer Science</div>
                    </div>
                    <div class="col s2">
                        <div class="comments">
                            <div class="commentbg">
                                <span>89</span>
                                <div class="mark">

                                </div>
                            </div>
                        </div>
                        <div class="time">
                            <span class="iconTitle">Last comment</span>
                            <br>
                            <i class="far fa-clock fa-sm"></i>
                            <span>15 min ago</span>
                        </div>
                    </div>
                </div>
                <div class="topic">
                    <div class="col s1 userImageContainer">
                        <img src="/public/images/content/defaultAvatar.png" alt="profile picture">
                    </div>
                    <div class="col s9 topicContainer">
                        <h3>Lorem ipsum dolor sit</h3>
                        <p class="topicDescription">lorem ipsum dolor sit amet, hedte hsafdsf jds ldsdsdf ldjfsd sdéflsdfsd éjédsjfs lkjshljsh  lsakshflkjash lohhlk lkhlh ll klkélkhé fgdfg fdgdffg gdfgdfg gdgdg dsgdv </p>
                        <p class="viewTopic"><a href="/discussion">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <div class="chip topicCategory">Computer Science</div>
                    </div>
                    <div class="col s2">
                        <div class="comments">
                            <div class="commentbg">
                                <span>89</span>
                                <div class="mark">

                                </div>
                            </div>
                        </div>
                        <div class="time">
                            <span class="iconTitle">Last comment</span>
                            <br>
                            <i class="far fa-clock fa-sm"></i>
                            <span>15 min ago</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="latestTopic">
                <?php
                    $topicObj = new Topic();
                    $latestTopics = $topicObj->getLatestTopics();
                
                    foreach($latestTopics as $latestTopic) {
                ?>
                <div class="topic">
                    <div class="col s1 userImageContainer">
                        <?php
                            echo "<img src='";
                            if($latestTopic["profileImage"] == 'defaultAvatar.png') {
                                echo '/public/images/content/defaultAvatar.png';
                            } else {
                                echo "/public/images/upload/" . $latestTopic["profileImage"];
                            }
                            echo "' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $latestTopic["username"] . "'>";
                        ?>
                    </div>
                    <div class="col s9 topicContainer">
                        <h3><?php echo $latestTopic["shortTopicName"]; ?></h3>
                        <p class="topicDescription"><?php echo $latestTopic["topicDescription"]; ?></p>
                        <p class="viewTopic"><a href="/topics/<?php echo $latestTopic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                        <div class="chip topicCategory"><?php echo $latestTopic["categoryName"]; ?></div>
                    </div>
                    <div class="col s2">
                        <div class="comments">
                            <div class="commentbg">
                                <span><?php echo $latestTopic["numberOfPosts"]; ?></span>
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