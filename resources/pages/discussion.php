<?php
    if(isset($_SESSION["selectedTopicID"])) {
        $topicObj = new Topic();
        $selectedTopic = $topicObj -> getSelectedTopic($_SESSION["selectedTopicID"]);
?>
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
                        <?php
                            echo "<img src=\"";
                            if($selectedTopic["profileImage"] == 'defaultAvatar.png') {
                                echo '/public/images/content/defaultAvatar.png';
                            } else {
                                echo "/public/images/upload/" . $selectedTopic["profileImage"] . "\"";
                            }
                            echo "\" class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip=" . $selectedTopic["username"] . ">";
                        ?>
                    </div>
                    <div class="col s11 topicContainer">
                        <h3><?php echo $selectedTopic["topicName"]?></h3>
                        <p class="topicDescription"><?php echo $selectedTopic["topicText"]?></p>
                    </div>
                </div>
                <div class="row topicDetails">
                    <div class="col s11 offset-s1">
                        <div class="row">
                            <div class="created col s5">
                                <i class="far fa-clock fa-sm"></i>
                                <span>Created at: <?php echo $selectedTopic["createdAt"]?> by <a href="#" class="postCreator"><strong><?php echo $selectedTopic["username"]?> </strong></a></span>
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
                                <a class="btn-floating waves-effect waves-light blue replyBtn" onclick="scrollToEditor()"><i class="material-icons">reply</i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="topicLikeButtonContainer" class="<?php echo (!isset($_SESSION['user']) || $selectedTopic['createdBy'] === $_SESSION['user']['userID'] ? 'hide' : ''); ?>">
                <a data-position="bottom" data-delay="50" data-tooltip="Add to favourites" class="btn-floating btn-large waves-effect waves-light topicLikeButton tooltipped" 
                onclick="likeTopic(
                    <?php echo $_SESSION["user"]["userID"] . ", " . $_SESSION['selectedTopicID'] . ", " . 
                    ($topicObj->checkLikedTopics($_SESSION["user"]["userID"], $_SESSION["selectedTopicID"]) > 0 
                        ? "'remove')\"> <i class=\"fas "
                        : "'add')\"> <i class=\"far "); 
                    ?> fa-heart fa-lg"></i></a>
                </div>
            </div>
            <div id="postContainer">
            <?php

            $postObj = new Post();
            $posts = $postObj->getPostData($_SESSION["selectedTopicID"]);
            
            for($i = 0; $i < count($posts); $i++) {
            if(isset($_SESSION["user"])) { $postObj->checkPostLikeStatus($_SESSION["user"]["userID"], $posts[$i]["postID"]); }
            ?>
                <div class="topic container post">
                    <div class="row postContent">
                        <div class="col s1 userImageContainer">
                            <?php

                                echo "<img src='"; 
                                echo ($posts[$i]['profileImage'] === 'defaultAvatar.png' ?'/public/images/content/defaultAvatar.png' : '/public/images/upload/' . $posts[$i]["profileImage"]);
                                echo "' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]['username'] . "'>";
                            ?>
                        </div>
                        <div class="col s11 topicContainer">
                            <div class="row postedOnContainer">
                                <div class="col s4 postedBy">
                                    <a href="#"><?php echo $posts[$i]['username']; ?></a>
                                </div>
                                <div class="col s4 offset-s4 postedOn">
                                    <i class="far fa-clock fa-xs"></i>
                                    <span>Posted on: <?php echo $posts[$i]["postedOn"]; ?></span>
                                </div>
                            </div>
                            <?php 
                                if(isset($posts[$i]["replyID"])) {
                            ?>
                                    <div class="replyContent">
                                        <div class="row postedOnContainer">
                                            <div class="col s12 postedBy">
                                                <span>Original Posted by - </span>
                                                <a href="#"><?php echo $posts[$posts[$i]["replyID"]-1]['username']; ?>:</a>
                                            </div>
                                        </div>
                                        <p class="topicDescription"><?php echo $posts[$posts[$i]["replyID"]-1]["text"];?></p>
                                        <div class="row postIndexContainer">
                                            <div class="col s12 postIndex">
                                                <a href="#">#<?php echo $posts[$i]["replyID"]; ?></a>
                                            </div>
                                        </div>
                                    </div>      
                            <?php } ?>
                            <p class="topicDescription"><?php echo $posts[$i]["text"]; ?></p>
                            <ul class="postAttachFiles">
                                <?php 
                                    $attachedFiles = $postObj->getAttachedFiles($posts[$i]["attachedFilesCode"]);

                                    foreach($attachedFiles as $file) {
                                        $fileExtension = explode(".", $file["attachmentName"]);
                                        if(in_array($fileExtension[1], array('png', 'jpg', 'jpeg'))) {
                                            echo '<li><a href="/public/files/upload/' . $file["attachmentName"] . '" download="' . $file["displayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["displayName"] . '</a></li>';
                                            echo '<a href="/public/files/upload/' . $file["attachmentName"] . '" data-lightbox="attachedImagePost' . $posts[$i]["postID"] . '" data-title="' . $file["displayName"] . '"><img src="/public/files/upload/' . $file["attachmentName"] . '"></a>';
                                            echo "<p>(To see the original size click on the name of the image)</p>";
                                        } else {
                                            echo '<li><a href="/public/files/upload/' . $file["attachmentName"] . '" download="' . $file["displayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["displayName"] . '</a></li>';
                                        }
                                    }

                                ?>
                            </ul>
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
                                                    echo ($postObj->isExistInPostLikeTable || $posts[$i]['userID'] === $_SESSION["user"]["userID"] ? " disabled disabledBtn" : "");
                                                ?> 
                                                likeFloatBtn<?php echo $posts[$i]["postID"]; ?>" <?php if (isset($_SESSION["user"])) {
                                                    echo "onclick='likePost(" . $posts[$i]['postID'] . ")'";
                                                } ?>>
                                                <i class="far fa-thumbs-up fa-lg likeIcon"></i>
                                            </a>
                                            <span class="likeValue<?php echo $posts[$i]["postID"]; ?>">
                                            <?php 
                                                echo ($posts[$i]["numberOfLikes"] ? $posts[$i]["numberOfLikes"] : "0"); ?>
                                            </span>
                                        </div>
                                        <div class="col s6 dislikeBtnContainer">
                                            <a class="btn-floating waves-effect waves-light likeBtn
                                                <?php 
                                                    echo ($postObj->isExistInPostLikeTable || $posts[$i]['userID'] === $_SESSION["user"]["userID"] ? " disabled disabledBtn" : "");
                                                ?>
                                                dislikeFloatBtn<?php echo $posts[$i]["postID"]; ?>"
                                                <?php if (isset($_SESSION["user"])) {
                                                    echo "onclick='dislikePost(" . $posts[$i]['postID'] . ")'";
                                                } ?>>
                                                <i class="far fa-thumbs-down fa-lg likeIcon"></i>
                                            </a>
                                            <span class="dislikeValue<?php echo $posts[$i]["postID"]; ?>">
                                            <?php 
                                                echo ($posts[$i]['numberOfDislikes'] ? $posts[$i]['numberOfDislikes'] : '0');
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
                                    <a class="btn-floating waves-effect waves-light blue replyBtn"><i class="material-icons" onclick="replyPost(<?php echo $i + 1 . ", '" . $posts[$i]['username'] ."'"; ?>)">reply</i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
            <div class="topic commentCard  <?php echo (!isset($_SESSION["user"]) ? 'hide' : ""); ?>">
                <div class="row postContent">
                    <div class="col s1 userImageContainer">
                    <?php
                        echo "<img src='"; 
                        echo ($loggedUser['profileImage'] === 'defaultAvatar.png' ?'/public/images/content/defaultAvatar.png' : '/public/images/upload/' . $loggedUser["profileImage"]);
                        echo "' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $loggedUser["username"] . "'>";
                    ?>
                    </div>
                    <div class="col s11 topicContainer editorTop">
                        <p class="replyLabel hide">Reply to <strong id="originalPostID"></strong> written by <em id="replyTo"></em>:</p>
                        <div class="editorContainer">
                            <div id="editor"></div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <p id="errorMsg"></p>
                                <hr id="errorMsgSeparator" class="hide">
                                <ul id="attachFiles"></ul>
                            </div>
                            <div class="col s1 offset-s3">
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
                            <a class="btn waves-effect waves-light blue col s2 postReplyBtn disabled">Reply</a>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="topic commentCardUnlogged <?php echo (isset($_SESSION["user"]) && $_SESSION["user"]["loggedIn"] ? 'hide' : ""); ?>">
                <p>You must <a href="#login" class="modal-trigger">Log In</a> or <a href="#signup" class="modal-trigger">Sign Up</a> to reply.</p>
            </div>
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