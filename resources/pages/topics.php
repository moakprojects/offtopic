<div class="container contentContainer">
    <div class="row">
        <div class="col s8">
            <div class="titleSection">
                <h2>Explore Topics</h2>
                <div class="breadCrumb">
                    <a href="/home">Home</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories">Categories</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/topics">Topics</a>
                </div>
                <h3>PBA Web Development</h3>
                <hr>
            </div>
            <?php 
                $topicObj = new Topic();
                $topics = $topicObj -> getTopicData();
                
                foreach($topics as $topic) {
            ?>
            <div class="topic">
                <a href="/discussion">
                    <div class="col s1 userImageContainer">
                        <div class="row">
                        <?php
                            echo "<img src=\"";
                            if($topic["profileImage"] == 'defaultAvatar.png') {
                                echo '/public/images/content/defaultAvatar.png';
                            } else {
                                echo "/public/images/upload/" . $topic["profileImage"] . "\"";
                            }
                            echo "\" class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip=" . $topic["username"] . ">";
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
                        <p class="viewTopic"><a href="/discussion">View the topic <i class="fas fa-angle-double-right"></i></a></p>
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
            <div class="sideBarBlock <?php if(isset($_SESSION["user"]) && $_SESSION["user"]["loggedIn"]) { echo 'hide'; } ?>">
                <h4>Login Account</h4>
                <div class="line"></div>
                <form action="" method="post" class="sideBarLoginContainer" id="sideBarLogForm">
                    <div class="row">
                        <input type="text" name="userName" Placeholder="Email or username" class="loginID col s8 offset-s1">    
                    </div>
                    <div class="row">
                        <input type="password" name="password" Placeholder="Password" class="password col s8 offset-s1">    
                    </div>
                    <div class="row">
                        <div class="col s6  offset-s1 rememberMeContainer">
                            <input type="checkbox" name="rememberMe" id="rememberMe" class="filled-in"><label for="rememberMe">Remember me</label>    
                        </div>
                    </div>
                    <div class="row sideBarErrorMsg hide">
                        <p class="col s10 offset-s1"></p>
                    </div>
                    <div class="row">
                        <input type="button" value="Login" id="sideBarLogBtn" class="btn wavew-effect waves-light blue col s4 offset-s7">
                    </div>
                </form>
            </div>
            <div class="sideBarBlock">
                <h4><a href="/categories" class="topicCategoriesTitle">Topic Categories</a></h4>
                <div class="line"></div>
                <div class="categoryContainer">
                    <ul>
                        <li><a href="#">PBA Web Development</a><span data-badge-caption="" class="new badge blue">4</span></li>
                        <li><a href="#">Computer Science</a><span data-badge-caption="" class="new badge blue">25</span></li>
                        <li><a href="#">Everyday life</a><span data-badge-caption="" class="new badge blue">18</span></li>
                    </ul>
                    <a href="/categories">View the categories <i class="fas fa-angle-double-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>