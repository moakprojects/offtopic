<div class="container contentContainer">
    <div class="row">
        <div class="col s8">
            <div class="titleSection">
                <div class="breadCrumb">
                    <a href="/home">Home</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories">Categories</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/topics">Topics</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/discussion">Discussion</a>
                </div>
            </div>
            <div class="topic container">
                <div class="row">
                    <div class="col s1 userImageContainer">   
                        <img src="public/images/content/profile.png" class="tooltipped" alt="profile picture" data-position="bottom" data-delay="50" data-tooltip="Test User">
                    </div>
                    <div class="col s11 topicContainer">
                        <h3>Backend course - Sticky post</h3>
                        <p class="topicDescription">Can anybody help me with what you learned last lesson? And should we do something for the next class? What's your opinion on PHP anyway?</p>
                        <div class="row postIndexContainer">
                            <div class="col s12 postIndex">
                                <a href="#">#14</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row topicDetails">
                    <div class="col s11 offset-s1">
                        <div class="row">
                            <div class="created col s5">
                                <i class="far fa-clock fa-sm"></i>
                                <span>Created at: 02-01-2018 17:41 by <a href="#" class="postCreator">Test User</strong></span>
                            </div>
                            <div class="controlBtns col s4 offset-s3">
                                <div class="fixed-action-btn horizontal socialButttons">
                                    <a class="btn-floating shareBtn">
                                    <i class="material-icons shareIcon">share</i>
                                    </a>
                                    <ul>
                                    <li><a class="btn-floating facebook"><i class="fab fa-facebook-f fa-lg"></i></a></li>
                                    <li><a class="btn-floating twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a class="btn-floating google"><i class="fab fa-google-plus-g"></i></a></li>
                                    </ul>
                                </div>
                                <a href="#" class="btn-floating waves-effect waves-light blue replyBtn"><i class="material-icons">reply</i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#" data-position="bottom" data-delay="50" data-tooltip="Add to favourites" class="btn-floating btn-large waves-effect waves-light blue topicLikeButton tooltipped"><i class="far fa-heart fa-lg"></i></a>
            </div>
            <div id="postContainer">
            <?php

            require "database/selection.php";
            require "database/discussionFeatures.php";
            
            for($i = 0; $i < count($post); $i++) {
            ?>
                <div class="topic container post">
                    <div class="row postContent">
                        <div class="col s1 userImageContainer">
                            <?php
                                echo "<img src='";
                                if($avatarFileName == 'defaultAvatar.png') {
                                    echo 'public/images/content/defaultAvatar.png';
                                } else {
                                    echo "public/images/upload/$avatarFileName";
                                }
                                echo "' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='$username'>";
                            ?>
                        </div>
                        <div class="col s11 topicContainer">
                            <div class="row postedOnContainer">
                                <div class="col s4 postedBy">
                                    <a href="#"><?php echo $username; ?></a>
                                </div>
                                <div class="col s4 offset-s4 postedOn">
                                    <i class="far fa-clock fa-xs"></i>
                                    <span>Posted on: <?php echo $post[$i]["postedOn"]; ?></span>
                                </div>
                            </div>
                            <?php 
                                if(isset($post[$i]["replyID"])) {
                            ?>
                                    <div class="replyContent">
                                        <div class="row postedOnContainer">
                                            <div class="col s12 postedBy">
                                                <span>Original Posted by - </span>
                                                <a href="#"><?php echo $username; ?>:</a>
                                            </div>
                                        </div>
                                        <p class="topicDescription"><?php echo $post[$post[$i]["replyID"]-1]["text"];?></p>
                                        <div class="row postIndexContainer">
                                            <div class="col s12 postIndex">
                                                <a href="#">#<?php echo $post[$i]["replyID"]; ?></a>
                                            </div>
                                        </div>
                                    </div>      
                            <?php } ?>
                            <p class="topicDescription"><?php echo $post[$i]["text"]; ?></p>
                            <div class="row postIndexContainer">
                                <div class="col s12 postIndex">
                                    <a href="#">#<?php echo $i + 1; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row topicDetails">
                        <div class="col s11 offset-s1">
                            <div class="row">
                                <div class="col s4 likesContainer">
                                    <div class="row">
                                        <div class="col s6 likeBtnContainer">
                                            <a class="btn-floating waves-effect waves-light likeBtn 
                                                <?php
                                                    if(checkPostStatus(10, $post[$i]["postID"]) > 0) {
                                                        echo " disabled disabledBtn";
                                                    }
                                                ?> 
                                                likeFloatBtn<?php echo $post[$i]["postID"]; ?>" onclick="like(<?php echo $post[$i]["postID"]; ?>)">
                                                <i class="far fa-thumbs-up fa-lg likeIcon"></i>
                                            </a>
                                            <span class="likeValue<?php echo $post[$i]["postID"]; ?>">
                                            <?php 
                                                countLikes($post[$i]["postID"], "like");
                                            ?>
                                            </span>
                                        </div>
                                        <div class="col s6 dislikeBtnContainer">
                                            <a class="btn-floating waves-effect waves-light likeBtn
                                                <?php 
                                                    if(checkPostStatus(10, $post[$i]["postID"]) > 0) {
                                                        echo " disabled disabledBtn";
                                                    }
                                                ?>
                                                dislikeFloatBtn<?php echo $post[$i]["postID"]; ?>" onclick="dislike(<?php echo $post[$i]["postID"]; ?>)">
                                                <i class="far fa-thumbs-down fa-lg likeIcon"></i>
                                            </a>
                                            <span class="dislikeValue<?php echo $post[$i]["postID"]; ?>">
                                            <?php 
                                                countLikes($post[$i]["postID"], "dislike");
                                            ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col s4"></div>
                                <div class="controlBtns col s4">
                                    <div class="fixed-action-btn horizontal socialButttons">
                                        <a class="btn-floating shareBtn">
                                        <i class="material-icons shareIcon">share</i>
                                        </a>
                                        <ul>
                                        <li><a class="btn-floating facebook"><i class="fab fa-facebook-f fa-lg"></i></a></li>
                                        <li><a class="btn-floating twitter"><i class="fab fa-twitter"></i></a></li>
                                        <li><a class="btn-floating google"><i class="fab fa-google-plus-g"></i></a></li>
                                        </ul>
                                    </div>
                                    <a href="#" class="btn-floating waves-effect waves-light blue replyBtn"><i class="material-icons">reply</i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
            <div class="topic commentCard">
                <div class="row postContent">
                    <div class="col s1 userImageContainer">   
                        <img src="public/images/content/profile.png" class="tooltipped" alt="profile picture" data-position="bottom" data-delay="50" data-tooltip="Test User">
                    </div>
                    <div class="col s11 topicContainer">
                        <div class="editorContainer">
                            <div id="editor"></div>
                        </div>
                        <div class="row">
                            <div class="col s3">
                                <p id="errorMsg"></p>
                            </div>
                            <div class="col s1 offset-s6">
                                <div class="preloader-wrapper small active hide replySpinner">
                                    <div class="spinner-layer spinner-blue-only">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div><div class="gap-patch">
                                            <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="btn waves-effect waves-light blue col s2 postReplyBtn">Reply</a>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="topic commentCardUnlogged hide">
                <p>You must <a href="#login" class="modal-trigger">Log In</a> or <a href="#signup" class="modal-trigger">Sign Up</a> to reply.</p>
            </div>
        </div>
        <div class="col s4">
            <div class="sideBarBlock">
                <h4>Login Account</h4>
                <div class="line"></div>
                <form action="" method="post" class="sideBarLoginContainer">
                    <div class="row">
                        <div class="col s1"></div>
                        <input type="text" name="userName" Placeholder="Username" class="col s8">    
                    </div>
                    <div class="row">
                        <div class="col s1"></div>
                        <input type="password" name="password" Placeholder="Password" class="col s8">    
                    </div>
                    <div class="row">
                    <div class="col s1"></div>
                        <div class="col s6 rememberMeContainer">
                            <input type="checkbox" name="rememberMe" id="rememberMe" class="filled-in"><label for="rememberMe">Remember me</label>    
                        </div>
                    </div>
                    <div class="row">
                        <input type="submit" value="Login" class="btn wavew-effect waves-light blue col s4 offset-s7">
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
<?php
    include("resources/modals/login.php");
    include("resources/modals/registration.php");
?>