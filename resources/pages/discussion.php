<?php
    if(isset($_SESSION["selectedTopicID"])) {
        $topicObj = new Topic();
        $postObj = new Post();
        $posts = $postObj->getPostsData($_SESSION["selectedTopicID"]);
        $topicData = $topicObj->getSelectedTopic($_SESSION["selectedTopicID"]);
?>
<div class="container contentContainer discussionPage">
    <div class="row noBottomMargin">
        <div class="col s8">
            <div class="titleSection">
                <div class="breadCrumb">
                    <a href="/home">Home</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories">Categories</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories/<?php echo $topicData["categoryID"]; ?>"><?php echo $topicData["categoryName"]; ?></a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/topics/<?php echo $topicData["topicID"]; ?>"><?php echo $topicData["shortTopicName"]; ?></a>
                </div>
            </div>
            <div class="topic container">
                <div class="row">
                    <div class="col s1 userImageContainer">
                        <?php
                             if($topicData["profileImage"] == 'defaultAvatar.png') {
                                echo "<a href='/profile/" . $topicData["username"] . "'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $topicData["username"] . "'></a>";
                            } else if ($topicData["profileImage"]  == 'admin.png') {
                                echo "<img src='/public/images/content/admin.png' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $topicData["username"] . "'>";
                            } else if ($topicData["profileImage"]  == 'anonymous.png') {
                                echo "<img src='/public/images/content/anonymous.png' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $topicData["username"] . "'>";
                            } else {
                                echo "<a href='/profile/" . $topicData["username"] . "'><img src='/public/images/upload/" . $topicData["profileImage"] ."' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $topicData["username"]  . "'></a>";
                            }
                        
                        if (
                            $topicData["rankID"] == 2 ||
                            $topicData["rankID"] == 3 ||
                            $topicData["rankID"] == 4) {
                        ?>
                        <div class="row">
                            <div class="col s12">
                                <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $topicData['rankColor'] . "'"; ?>></i>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="col s11 topicContainer">
                        <h3><?php echo $topicData["topicName"]?></h3>
                        <p class="topicDescription"><?php echo $topicData["topicText"]?></p>
                        <ul class="postAttachFiles">
                            <?php
                            $topicAttachedFiles = $topicObj->getAttachedFiles($topicData["attachedFilesCode"]);
                            if($topicAttachedFiles) {
                                foreach($topicAttachedFiles as $file) {
                                    $fileExtension = explode(".", $file["topicAttachmentName"]);
                                    if(in_array($fileExtension[1], array('png', 'jpg', 'jpeg'))) {
                                        echo '<li><a href="/public/files/upload/' . $file["topicAttachmentName"] . '" download="' . $file["topicAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["topicAttachmentDisplayName"] . '</a></li>';
                                        echo '<a href="/public/files/upload/' . $file["topicAttachmentName"] . '" data-lightbox="attachedTopicFiles" data-title="' . $file["topicAttachmentDisplayName"] . '"><img src="/public/files/upload/' . $file["topicAttachmentName"] . '"></a>';
                                        echo "<p>(To see the original size click on the name of the image)</p>";
                                    } else {
                                        echo '<li><a href="/public/files/upload/' . $file["topicAttachmentName"] . '" download="' . $file["topicAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["topicAttachmentDisplayName"] . '</a></li>';
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php
                    if(isset($loggedUser) && $loggedUser) {
                ?>
                <div class="row noMargin"> 
                    <div class="col s3">
                        <?php 
                            if($topicData["username"] != "admin" && !isset($_SESSION["user"]["isAdmin"]) && $topicData['createdBy'] !== $_SESSION['user']['userID']) {
                        ?>
                                <a class="waves-effect waves-light btn reportBtn" onclick="report(<?php echo $topicData["topicID"]; ?>, 'topic')">Report</a>
                        <?php
                            }
                        ?>
                    </div>
                    <?php
                        if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
                    ?>
                    <?php
                        }
                    ?>
                </div>
                <?php
                    }
                ?>
                <div class="row topicDetails">
                    <div class="col s11 offset-s1">
                        <div class="row">
                            <div class="created col s8">
                                <i class="far fa-clock fa-sm"></i>
                                <span>Created at: <?php echo $topicData["createdAt"]?> by 
                                    <?php 
                                        if($topicData["username"] == 'admin') {
                                            echo "<span class='noMargin originalAdmin'><strong>" . $topicData["username"] . "</strong></span>";
                                        } else if($topicData["username"] == 'Anonymous') {
                                            echo "<span class='postCreator'><strong>" . $topicData["username"] . "</strong><span>";
                                        } else {
                                            echo "<a href='/profile/" . $topicData["username"] . "' class='postCreator'><strong>" . $topicData["username"] . "</strong></a>"; 
                                        }
                                    ?>
                                </span>
                            </div>
                            <div class="controlBtns col s4">
                                <div class="row noTopMargin">
                                        <div class="col s6 offset-s<?php echo (isset($loggedUser) && $loggedUser ? "2 noRightPadding" : "6"); ?> right-align">
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
                                        </div>
                                        <div class="col s4 right-align noLeftPadding <?php echo (isset($loggedUser) && $loggedUser ? '' : 'hide'); ?>">
                                            <a class="btn-floating waves-effect waves-light blue replyBtn" onclick="replyForTheTopic()"><i class="material-icons">reply</i></a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(isset($_SESSION["user"]) && $topicData['createdBy'] != $_SESSION['user']['userID']) { ?>
                    <div id="topicLikeButtonContainer">
                    <a data-position="bottom" data-delay="50" data-tooltip="Add to favourites" class="btn-floating btn-large waves-effect waves-light topicLikeButton tooltipped" 
                    onclick="likeTopic(
                        <?php echo $_SESSION["user"]["userID"] . ", " . $_SESSION['selectedTopicID'] . ", " . 
                        ($topicObj->checkLikedTopics($_SESSION["user"]["userID"], $_SESSION["selectedTopicID"]) > 0 
                            ? "'remove')\"> <i class=\"fas "
                            : "'add')\"> <i class=\"far "); 
                        ?> fa-heart fa-lg"></i></a>
                    </div>
                <?php } ?>
            </div>
            <!-- sticky post container -->
            <div id="postContainer">
            <?php
            
            for($i = 0; $i < count($posts); $i++) {
                if($posts[$i]['isSticky'] == 1) {
                    if(isset($_SESSION["user"])) { $postObj->checkPostLikeStatus($_SESSION["user"]["userID"], $posts[$i]["postID"]); }
            ?>
                <div class="topic container post">
                    <div class="row postContent">
                        <div class="col s1 userImageContainer">
                            <?php
                                if($posts[$i]["profileImage"] == 'defaultAvatar.png') {
                                    echo "<a href='/profile/" . $posts[$i]["profileImage"] . "'><img src='/public/images/content/defaultAvatar.png' class=' newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'></a>";
                                } else if ($posts[$i]["profileImage"] == 'admin.png') {
                                    echo "<img src='/public/images/content/admin.png' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'>";
                                } else if ($posts[$i]["profileImage"] == 'anonymous.png') {
                                    echo "<img src='/public/images/content/anonymous.png' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'>";
                                } else {
                                    echo "<a href='/profile/" . $posts[$i]["username"] . "'><img src='/public/images/upload/" . $posts[$i]["profileImage"] ."' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'></a>";
                                }
                            
                            if (
                                $posts[$i]["rankID"] == 2 ||
                                $posts[$i]["rankID"] == 3 ||
                                $posts[$i]["rankID"] == 4) {
                            ?>
                            <div class="row">
                                <div class="col s12">
                                    <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $posts[$i]['rankColor'] . "'"; ?>></i>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="col s11 topicContainer">
                            <div class="row postedOnContainer">
                                <div class="col s4 postedBy">
                                    <?php
                                            if($posts[$i]['username'] == 'admin') {
                                                echo "<span class='noMargin adminTitle'>" . $posts[$i]['username'] . "</span>";
                                            } else if($posts[$i]['username'] == 'Anonymous') {
                                                echo  "<span>" . $posts[$i]['username'] . "</span>";
                                            } else {
                                                echo "<a href='/profile/" . $posts[$i]['username'] . "'>" . $posts[$i]['username'] . "</a>"; 
                                            }
                                    ?> 
                                </div>
                                <div class="col s4 offset-s4 postedOn">
                                    <i class="far fa-clock fa-xs"></i>
                                    <span>Posted on: <?php echo $posts[$i]["postedOn"]; ?></span>
                                    <span class="stickyPin">
                                        <i class="fas fa-thumbtack"></i>
                                    </span>
                                </div>
                            </div>
                            <?php 
                                if(isset($posts[$i]["replyID"])) {
                                    if($posts[$i]["replyID"] != -1) {
                            ?>
                                    <div class="replyContent">
                                        <div class="row postedOnContainer">
                                            <div class="col s12 postedBy">
                                                <span>Original Posted by - </span>
                                                <?php
                                                    if($posts[$posts[$i]["replyID"]-1]['username'] == 'admin') {
                                                        echo "<span class='noMargin originalAdmin'>" . $posts[$posts[$i]["replyID"]-1]['username'] . ":</span>";
                                                    } else if($posts[$posts[$i]["replyID"]-1]['username'] == 'Anonymous') {
                                                        echo "<span>" . $posts[$posts[$i]["replyID"]-1]['username'] . ":</span>";
                                                    } else {
                                                        echo "<a href='/profile/" . $posts[$posts[$i]["replyID"]-1]['username'] . "'>" . $posts[$posts[$i]["replyID"]-1]['username'] . ":</a>"; 
                                                    }
                                                ?>  
                                            </div>
                                        </div>
                                        <p class="topicDescription"><?php echo $posts[$posts[$i]["replyID"]-1]["text"];?></p>
                                        <div class="row postIndexContainer">
                                            <div class="col s12 postIndex right-align">
                                                <a href="/post/<?php echo $posts[$posts[$i]["replyID"]-1]["postID"]; ?>">#<?php echo $posts[$i]["replyID"]; ?></a>
                                            </div>
                                        </div>
                                    </div>      
                            <?php 
                                    } else {
                            ?>
                                        <div class="replyContent">
                                            <div class="row postedOnContainer">
                                                <div class="col s12 postedBy">
                                                    <span>The original post has been deleted by admin.</span>
                                                </div>
                                            </div>
                                        </div>
                            <?php
                                    }
                                }    
                            ?>
                            <p class="topicDescription"><?php echo $posts[$i]["text"]; ?></p>
                            <ul class="postAttachFiles">
                                <?php 
                                    $attachedFiles = $postObj->getAttachedFiles($posts[$i]["attachedFilesCode"]);
                                    if($attachedFiles) {
                                        foreach($attachedFiles as $file) {
                                            $fileExtension = explode(".", $file["postAttachmentName"]);
                                            if(in_array($fileExtension[1], array('png', 'jpg', 'jpeg'))) {
                                                echo '<li><a href="/public/files/upload/' . $file["postAttachmentName"] . '" download="' . $file["postAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["postAttachmentDisplayName"] . '</a></li>';
                                                echo '<a href="/public/files/upload/' . $file["postAttachmentName"] . '" data-lightbox="attachedImagePost' . $posts[$i]["postID"] . '" data-title="' . $file["postAttachmentDisplayName"] . '"><img src="/public/files/upload/' . $file["postAttachmentName"] . '"></a>';
                                                echo "<p>(To see the original size click on the name of the image)</p>";
                                            } else {
                                                echo '<li><a href="/public/files/upload/' . $file["postAttachmentName"] . '" download="' . $file["postAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["postAttachmentDisplayName"] . '</a></li>';
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                            <?php
                                if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
                            ?>
                                <div class="row postIndexContainer">
                                    <div class="col s12 right-align postIndex">
                                        <a href="/post/<?php echo $posts[$i]["postID"]; ?>">#<?php echo $i + 1; ?></a>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row noMargin">
                        <div class="col s1 <?php echo (isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"]) ? 'hide' : ''); ?>">
                            <?php
                                if(isset($loggedUser) && $loggedUser && $topicData["username"] != "admin" && $topicData['createdBy'] !== $_SESSION['user']['userID']) {
                            ?>
                                <a class="waves-effect waves-light btn reportBtn postReport" onclick="report(<?php echo $posts[$i]["postID"]; ?>, 'post')">Report</a>
                            <?php
                                }
                            ?>
                        </div>
                        <?php
                            if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
                        ?>
                        
                        <div class="col s2">
                            <a class="waves-effect waves-light btn stickyBtn" onclick="setSticky(<?php echo $posts[$i]["postID"] ."," . $_SESSION["selectedTopicID"]; ?>, 'unsticky')">Unsticky</a>
                        </div>
                        <div class="col s2 offset-s8 right-align adminIcons">
                            <div class="noPadding pencilIcon titleIcon center-align">
                                <a onclick="adminModification('post', <?php echo $posts[$i]["postID"]; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                    <i class="fas fa-pencil-alt fa-xs"></i>
                                </a>
                            </div>
                            <div class="noPadding trashIcon titleIcon center-align">
                                <a onclick="adminDelition('topics/<?php echo $_SESSION["selectedTopicID"]; ?>', '#postContainer', 'post', <?php echo $posts[$i]["postID"] ; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                    <i class="fas fa-trash fa-xs"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                            } else {
                        ?>
                            <div class="col s11 right-align postIndex">
                                <a href="/post/<?php echo $posts[$i]["postID"]; ?>">#<?php echo $i + 1; ?></a>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="row topicDetails">
                        <div class="col s11 offset-s1">
                            <div class="row">
                                <div class="col s4 likesContainer">
                                    <div class="row">
                                        <div class="col s6 likeBtnContainer">
                                            <a class="btn-floating waves-effect waves-light likeBtn 
                                                likeFloatBtn<?php echo $posts[$i]["postID"];
                                                 if (isset($_SESSION["user"])) {
                                                 echo ($postObj->isExistInPostLikeTable || $posts[$i]['userID'] === $_SESSION["user"]["userID"] ? " disabled disabledBtn" : "");
                                                ?>" <?php                                                
                                                    echo "onclick='likePost(" . $posts[$i]['postID'] . ")'";
                                                } else { echo '"'; } ?>>
                                                <i class="far fa-thumbs-up fa-lg likeIcon"></i>
                                            </a>
                                            <span class="likeValue<?php echo $posts[$i]["postID"]; ?>">
                                            <?php 
                                                echo ($posts[$i]["numberOfLikes"] ? $posts[$i]["numberOfLikes"] : "0"); ?>
                                            </span>
                                        </div>
                                        <div class="col s6 dislikeBtnContainer">
                                            <a class="btn-floating waves-effect waves-light likeBtn
                                                dislikeFloatBtn<?php echo $posts[$i]["postID"];
                                                if (isset($_SESSION["user"])) {
                                                echo ($postObj->isExistInPostLikeTable || $posts[$i]['userID'] === $_SESSION["user"]["userID"] ? " disabled disabledBtn" : "");
                                                ?>" <?php
                                                    echo "onclick='dislikePost(" . $posts[$i]['postID'] . ")'";
                                                } else { echo '"'; } ?>>
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
                                    <div class="row noTopMargin">
                                        <div class="col s6 offset-s<?php echo (isset($loggedUser) && $loggedUser ? "2 noRightPadding" : "6"); ?> right-align">
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
                                        </div>
                                        <div class="<?php echo (isset($loggedUser) && $loggedUser ? '' : 'hide'); ?> col s4 right-align noLeftPadding">
                                            <a class="btn-floating waves-effect waves-light blue replyBtn" onclick="replyPost(<?php echo $i + 1 . ", " . $posts[$i]["postID"] . ", '" . $posts[$i]['username'] ."'"; ?>)"><i class="material-icons">reply</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } } ?>
            <!-- original posts container (not sticky!!) -->
            <?php
            
            for($i = 0; $i < count($posts); $i++) {
                if($posts[$i]["isSticky"] != 1) {
                    if(isset($_SESSION["user"])) { $postObj->checkPostLikeStatus($_SESSION["user"]["userID"], $posts[$i]["postID"]); }
            ?>
                <div class="topic container post">
                    <div class="row postContent">
                        <div class="col s1 userImageContainer">
                            <?php
                                if($posts[$i]["profileImage"] == 'defaultAvatar.png') {
                                    echo "<a href='/profile/" . $posts[$i]["profileImage"] . "'><img src='/public/images/content/defaultAvatar.png' class=' newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'></a>";
                                } else if ($posts[$i]["profileImage"] == 'admin.png') {
                                    echo "<img src='/public/images/content/admin.png' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'>";
                                } else if ($posts[$i]["profileImage"] == 'anonymous.png') {
                                    echo "<img src='/public/images/content/anonymous.png' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'>";
                                } else {
                                    echo "<a href='/profile/" . $posts[$i]["username"] . "'><img src='/public/images/upload/" . $posts[$i]["profileImage"] ."' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"]  . "'></a>";
                                }
                            if (
                                $posts[$i]["rankID"] == 2 ||
                                $posts[$i]["rankID"] == 3 ||
                                $posts[$i]["rankID"] == 4) {
                            ?>
                            <div class="row">
                                <div class="col s12">
                                    <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $posts[$i]['rankColor'] . "'"; ?>></i>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="col s11 topicContainer">
                            <div class="row postedOnContainer">
                                <div class="col s4 postedBy">
                                    <?php
                                            if($posts[$i]['username'] == 'admin') {
                                                echo "<span class='noMargin adminTitle'>" . $posts[$i]['username'] . "</span>";
                                            } else if($posts[$i]['username'] == 'Anonymous') {
                                                echo  "<span>" . $posts[$i]['username'] . "</span>";
                                            } else {
                                                echo "<a href='/profile/" . $posts[$i]['username'] . "'>" . $posts[$i]['username'] . "</a>"; 
                                            }
                                    ?> 
                                </div>
                                <div class="col s4 offset-s4 postedOn">
                                    <i class="far fa-clock fa-xs"></i>
                                    <span>Posted on: <?php echo $posts[$i]["postedOn"]; ?></span>
                                </div>
                            </div>
                            <?php 
                                if(isset($posts[$i]["replyID"])) {
                                    if($posts[$i]["replyID"] != -1) {
                            ?>
                                    <div class="replyContent">
                                        <div class="row postedOnContainer">
                                            <div class="col s12 postedBy">
                                                <span>Original Posted by - </span>
                                                <?php
                                                    if($posts[$posts[$i]["replyID"]-1]['username'] == 'admin') {
                                                        echo "<span class='noMargin originalAdmin'>" . $posts[$posts[$i]["replyID"]-1]['username'] . ":</span>";
                                                    } else if($posts[$posts[$i]["replyID"]-1]['username'] == 'Anonymous') {
                                                        echo "<span>" . $posts[$posts[$i]["replyID"]-1]['username'] . ":</span>";
                                                    } else {
                                                        echo "<a href='/profile/" . $posts[$posts[$i]["replyID"]-1]['username'] . "'>" . $posts[$posts[$i]["replyID"]-1]['username'] . ":</a>"; 
                                                    }
                                                ?>  
                                            </div>
                                        </div>
                                        <p class="topicDescription"><?php echo $posts[$posts[$i]["replyID"]-1]["text"];?></p>
                                        <div class="row postIndexContainer">
                                            <div class="col s12 postIndex right-align">
                                                <a href="/post/<?php echo $posts[$posts[$i]["replyID"]-1]["postID"]; ?>">#<?php echo $posts[$i]["replyID"]; ?></a>
                                            </div>
                                        </div>
                                    </div>      
                            <?php 
                                    } else {
                            ?>
                                        <div class="replyContent">
                                            <div class="row postedOnContainer">
                                                <div class="col s12 postedBy">
                                                    <span>The original post has been deleted by admin.</span>
                                                </div>
                                            </div>
                                        </div>
                            <?php
                                    }
                                } 
                            ?>
                            <p class="topicDescription"><?php echo $posts[$i]["text"]; ?></p>
                            <ul class="postAttachFiles">
                                <?php 
                                    $attachedFiles = $postObj->getAttachedFiles($posts[$i]["attachedFilesCode"]);
                                    if($attachedFiles) {
                                        foreach($attachedFiles as $file) {
                                            $fileExtension = explode(".", $file["postAttachmentName"]);
                                            if(in_array($fileExtension[1], array('png', 'jpg', 'jpeg'))) {
                                                echo '<li><a href="/public/files/upload/' . $file["postAttachmentName"] . '" download="' . $file["postAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["postAttachmentDisplayName"] . '</a></li>';
                                                echo '<a href="/public/files/upload/' . $file["postAttachmentName"] . '" data-lightbox="attachedImagePost' . $posts[$i]["postID"] . '" data-title="' . $file["postAttachmentDisplayName"] . '"><img src="/public/files/upload/' . $file["postAttachmentName"] . '"></a>';
                                                echo "<p>(To see the original size click on the name of the image)</p>";
                                            } else {
                                                echo '<li><a href="/public/files/upload/' . $file["postAttachmentName"] . '" download="' . $file["postAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["postAttachmentDisplayName"] . '</a></li>';
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                            <?php
                                if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
                            ?>
                                <div class="row postIndexContainer">
                                    <div class="col s12 right-align postIndex">
                                        <a href="/post/<?php echo $posts[$i]["postID"]; ?>">#<?php echo $i + 1; ?></a>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row noMargin">
                        <div class="col s1 <?php echo (isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"]) ? 'hide' : ''); ?>">
                            <?php
                                if(isset($loggedUser) && $loggedUser && $topicData["username"] != "admin" && $topicData['createdBy'] !== $_SESSION['user']['userID']) {
                            ?>
                                <a class="waves-effect waves-light btn reportBtn postReport" onclick="report(<?php echo $posts[$i]["postID"]; ?>, 'post')">Report</a>
                            <?php
                                }
                            ?>
                        </div>
                        <?php
                            if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
                        ?>
                        <div class="col s2">
                            <a class="waves-effect waves-light btn stickyBtn" onclick="setSticky(<?php echo $posts[$i]["postID"] ."," . $_SESSION["selectedTopicID"]; ?>, 'sticky')">Sticky</a>
                        </div>
                        <div class="col s2 offset-s8 right-align adminIcons">
                            <div class="noPadding pencilIcon titleIcon center-align">
                                <a onclick="adminModification('post', <?php echo $posts[$i]["postID"]; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                    <i class="fas fa-pencil-alt fa-xs"></i>
                                </a>
                            </div>
                            <div class="noPadding trashIcon titleIcon center-align">
                                <a onclick="adminDelition('topics/<?php echo $_SESSION["selectedTopicID"]; ?>', '#postContainer', 'post', <?php echo $posts[$i]["postID"] ; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                    <i class="fas fa-trash fa-xs"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                            } else {
                        ?>
                            <div class="col s11 right-align postIndex">
                                <a href="/post/<?php echo $posts[$i]["postID"]; ?>">#<?php echo $i + 1; ?></a>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="row topicDetails">
                        <div class="col s11 offset-s1">
                            <div class="row">
                                <div class="col s4 likesContainer">
                                    <div class="row">
                                        <div class="col s6 likeBtnContainer">
                                            <a class="btn-floating waves-effect waves-light likeBtn 
                                                likeFloatBtn<?php echo $posts[$i]["postID"];
                                                 if (isset($_SESSION["user"])) {
                                                 echo ($postObj->isExistInPostLikeTable || $posts[$i]['userID'] === $_SESSION["user"]["userID"] ? " disabled disabledBtn" : "");
                                                ?>" <?php                                                
                                                    echo "onclick='likePost(" . $posts[$i]['postID'] . ")'";
                                                } else { echo '"'; } ?>>
                                                <i class="far fa-thumbs-up fa-lg likeIcon"></i>
                                            </a>
                                            <span class="likeValue<?php echo $posts[$i]["postID"]; ?>">
                                            <?php 
                                                echo ($posts[$i]["numberOfLikes"] ? $posts[$i]["numberOfLikes"] : "0"); ?>
                                            </span>
                                        </div>
                                        <div class="col s6 dislikeBtnContainer">
                                            <a class="btn-floating waves-effect waves-light likeBtn
                                                dislikeFloatBtn<?php echo $posts[$i]["postID"];
                                                if (isset($_SESSION["user"])) {
                                                echo ($postObj->isExistInPostLikeTable || $posts[$i]['userID'] === $_SESSION["user"]["userID"] ? " disabled disabledBtn" : "");
                                                ?>" <?php
                                                    echo "onclick='dislikePost(" . $posts[$i]['postID'] . ")'";
                                                } else { echo '"'; } ?>>
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
                                    <div class="row noTopMargin">
                                        <div class="col s6 offset-s<?php echo (isset($loggedUser) && $loggedUser ? "2 noRightPadding" : "6"); ?> right-align">
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
                                        </div>
                                        <div class="<?php echo (isset($loggedUser) && $loggedUser ? '' : 'hide'); ?> col s4 right-align noLeftPadding">
                                            <a class="btn-floating waves-effect waves-light blue replyBtn" onclick="replyPost(<?php echo $i + 1 . ", " . $posts[$i]["postID"] . ", '" . $posts[$i]['username'] ."'"; ?>)"><i class="material-icons">reply</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } }?>
            </div>
            <?php if(isset($_SESSION["user"])) { ?>
            <div class="topic commentCard">
                <div class="row postContent">
                    <div class="col s1 userImageContainer">
                        <?php

                            if($loggedUser['profileImage'] == 'defaultAvatar.png') {
                                echo "<a href='/profile/" . $loggedUser["username"] . "'><img src='/public/images/content/defaultAvatar.png' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $loggedUser["username"]  . "'></a>";
                            } else if ($loggedUser['profileImage'] == 'admin.png') {
                                echo "<img src='/public/images/content/admin.png' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $loggedUser["username"]  . "'>";
                            } else if ($loggedUser['profileImage'] == 'anonymous.png') {
                                echo "<img src='/public/images/content/anonymous.png' class=' newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $loggedUser["username"]  . "'>";
                            } else {
                                echo "<a href='/profile/" . $loggedUser["username"] . "'><img src='/public/images/upload/" . $loggedUser['profileImage'] ."' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $loggedUser["username"]  . "'></a>";
                            }

                        if (
                            $loggedUser["rankID"] == 2 ||
                            $loggedUser["rankID"] == 3 ||
                            $loggedUser["rankID"] == 4) {
                        ?>
                        <div class="row">
                            <div class="col s12">
                                <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $loggedUser['rankColor'] . "'"; ?>></i>
                            </div>
                        </div>
                        <?php
                            }
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
            <?php } ?>
            <div class="topic commentCardUnlogged <?php echo (isset($_SESSION["user"]) && $_SESSION["user"]["loggedIn"] ? 'hide' : ""); ?>">
                <p>You must <a href="#login" class="modal-trigger">Log In</a> or <a href="#signup" class="modal-trigger">Sign Up</a> to reply.</p>
            </div>
        </div>
        <div class="col s4 sideBarContainer">
            <div class="sideBar">
                <?php 
                    include "resources/sections/sideBarLoginBlock.php"; 
                    include "resources/sections/sideBarCategoryListBlock.php"; 
                    include "resources/sections/sideBarStickyPost.php";  
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    } else {
        header("Location: /error");
        exit;
    }
?>