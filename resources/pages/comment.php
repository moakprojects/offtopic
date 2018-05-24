<?php
    if(isset($_SESSION["selectedTopicID"])) {
        $postObj = new Post();
        $topicObj = new Topic();
        $postData = $postObj->getSelectedPost($_SESSION["selectedPost"]);
        $originalPost = $postObj->getOriginalPost($postData["topicID"], $postData["replyID"] - 1);
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8">
            <div class="titleSection">
                <div class="breadCrumb">
                    <a href="/home">Home</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories">Categories</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories/<?php echo $postData["categoryID"]; ?>"><?php echo $postData["categoryName"]; ?></a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/topics/<?php echo $postData["topicID"]; ?>"><?php echo $postData["shortTopicName"]; ?></a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/post/<?php echo $postData["postID"]; ?>">#<?php echo $postData["postIdInTopic"]; ?></a>
                </div>
            </div>
            <div class="topic container noBottomMargin">
                <div class="row">
                    <div class="col s1 userImageContainer">
                        <?php
                            if($postData["topicCreatorImage"] == 'defaultAvatar.png') {
                                echo "<a href='/profile/" . $postData["topicCreatorName"] . "'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["topicCreatorName"]  . "'></a>";
                            } else if ($postData["topicCreatorImage"] == 'admin.png') {
                                echo "<img src='/public/images/content/admin.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["topicCreatorName"]  . "'>";
                            } else if ($postData["topicCreatorImage"] == 'anonymous.png') {
                                echo "<img src='/public/images/content/anonymous.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["topicCreatorName"]  . "'>";
                            } else {
                                echo "<a href='/profile/" . $postData["topicCreatorName"] . "'><img src='/public/images/upload/" . $postData["topicCreatorImage"] ."' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["topicCreatorName"]  . "'></a>";
                            }

                        if (
                            $postData["topicCreatorRankID"] == 2 ||
                            $postData["topicCreatorRankID"] == 3 ||
                            $postData["topicCreatorRankID"] == 4) {
                        ?>
                        <div class="row">
                            <div class="col s12">
                                <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $postData['topicCreatorRankColor'] . "'"; ?>></i>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="col s11 topicContainer">
                        <a href="/topics/<?php echo $postData["topicID"]; ?>"><h3 class="postPageTopicTitle"><?php echo $postData["topicName"]; ?></h3></a>
                        <p class="topicDescription"><?php echo $postData["topicText"]; ?></p>
                        <ul class="postAttachFiles">
                            <?php
                                $attachedFiles = $topicObj->getAttachedFiles($postData["topicAttachedFilesCode"]);
                                if($attachedFiles) {
                                    foreach($attachedFiles as $file) {
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
                <div class="row topicDetails">
                    <div class="col s11 offset-s1">
                        <div class="row">
                            <div class="created col s12">
                                <i class="far fa-clock fa-sm"></i>
                                <span>Created at: <?php echo $postData["createdAt"]; ?> by 
                                    <?php
                                        if($postData["topicCreatorName"] == 'admin') {
                                            echo "<span class='topicCreator noMargin originalAdmin'>" . $postData["topicCreatorName"] . "</span>";
                                        } else if($postData["topicCreatorName"] == 'Anonymous') {
                                            echo "<span class='topicCreator'>". $postData["topicCreatorName"] ."</span>";
                                        } else {
                                            echo "<a class='topicCreator' href='/profile/" . $postData["topicCreatorName"]. "'>" . $postData["topicCreatorName"] . "</a>"; 
                                        }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row noMargin">
                <div class="threeDot col s12 center-align"></div>
            </div>
            <div class="topic container post noTopMargin">
                <div class="row postContent">
                    <div class="col s1 userImageContainer">
                        <?php
                             if($postData["postWriterImage"] == 'defaultAvatar.png') {
                                echo "<a href='/profile/" . $postData["postWriterName"] . "'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["postWriterName"]  . "'></a>";
                            } else if ($postData["postWriterImage"]  == 'admin.png') {
                                echo "<img src='/public/images/content/admin.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["postWriterName"]  . "'>";
                            } else if ($postData["postWriterImage"]  == 'anonymous.png') {
                                echo "<img src='/public/images/content/anonymous.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["postWriterName"] . "'>";
                            } else {
                                echo "<a href='/profile/" . $postData["postWriterName"] . "'><img src='/public/images/upload/" . $postData["postWriterImage"] ."' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $postData["postWriterName"]  . "'></a>";
                            }

                        if (
                            $postData["postWriterRankID"] == 2 ||
                            $postData["postWriterRankID"] == 3 ||
                            $postData["postWriterRankID"] == 4) {
                        ?>
                        <div class="row">
                            <div class="col s12">
                                <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $postData['postWriterRankColor'] . "'"; ?>></i>
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
                                    if($postData["postWriterName"] == 'admin') {
                                        echo "<span class='noMargin adminTitle'>" . $postData["postWriterName"] . "</span>";
                                    } else if($postData["postWriterName"] == 'Anonymous') {
                                        echo $postData["postWriterName"];
                                    } else {
                                        echo "<a href='/profile/" . $postData["postWriterName"] . "'>" . $postData["postWriterName"] . "</a>"; 
                                    }
                                ?>
                            </div>
                        </div>
                            <?php 
                                if($originalPost) { 
                                    if($originalPost != -1) {
                            ?> 
                            <div class="replyContent">
                                <div class="row postedOnContainer">
                                    <div class="col s12 postedBy">
                                        <span>Original Posted by - </span>
                                        <?php
                                            if($originalPost['username'] == 'admin') {
                                                echo "<span class='noMargin originalAdmin'>" . $originalPost["username"] . "</span>";
                                            } else if($originalPost["username"] == 'Anonymous') {
                                                echo  "<span>" . $originalPost["username"] . "</span>";
                                            } else {
                                                echo "<a href='/profile/" . $originalPost["username"] . "'>" . $originalPost["username"] . "</a>"; 
                                            }
                                        ?>
                                    </div>
                                </div>
                                <p class="topicDescription"><?php echo $originalPost["text"]; ?></p>
                                <div class="row postIndexContainer">
                                    <div class="col s12 postIndex right-align">
                                        <a href="/post/<?php echo $originalPost["postID"]; ?>">#<?php echo $postData["replyID"]; ?></a>
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
                        <p class="topicDescription"><?php echo $postData["text"]; ?></p>
                        <ul class="postAttachFiles">
                            <?php
                                $postAttachedFiles = $postObj->getAttachedFiles($postData["postAttachedFilesCode"]);
                                if($postAttachedFiles) {
                                    foreach($postAttachedFiles as $file) {
                                        $fileExtension = explode(".", $file["postAttachmentName"]);
                                        if(in_array($fileExtension[1], array('png', 'jpg', 'jpeg'))) {
                                            echo '<li><a href="/public/files/upload/' . $file["postAttachmentName"] . '" download="' . $file["postAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["postAttachmentDisplayName"] . '</a></li>';
                                            echo '<a href="/public/files/upload/' . $file["postAttachmentName"] . '" data-lightbox="attachedTopicFiles" data-title="' . $file["postAttachmentDisplayName"] . '"><img src="/public/files/upload/' . $file["postAttachmentName"] . '"></a>';
                                            echo "<p>(To see the original size click on the name of the image)</p>";
                                        } else {
                                            echo '<li><a href="/public/files/upload/' . $file["postAttachmentName"] . '" download="' . $file["postAttachmentDisplayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["postAttachmentDisplayName"] . '</a></li>';
                                        }
                                    }
                                }
                            ?>
                        </ul>
                        <div class="row postIndexContainer">
                            <div class="col s12 postIndex">
                                <a href="/post/<?php echo $postData["postID"]; ?>">#<?php echo $postData["postIdInTopic"]; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row topicDetails">
                    <div class="col s11 offset-s1">
                        <div class="row noBottomMargin valign-wrapper">
                            <div class="col s4 noLeftPadding">
                                <div class="postedOn">
                                    <i class="far fa-clock fa-sm"></i>
                                    <span>Posted on: <?php echo $postData["postedOn"]; ?></span>
                                </div>
                            </div>                            
                            <div class="controlBtns col s4 offset-s4">
                                <div class="row valign-wrapper">
                                    <div class="col s6 offset-s6 right-align">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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