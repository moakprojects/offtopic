<?php
    $userObj = new User();
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s12"> 
            <div class="row noMargin">
                <div class="col s2 pageTitle">
                    <h2>Dashboard</h2>
                </div>
                <div class="col s3 offset-s1 newBtnContainer right-align noPadding">
                    <a href="#" class="waves-effect waves-light btn newBtn blue">Create New Category</a></li>
                </div>
                <div class="col s3 newBtnContainer right-align noPadding">
                    <a href="/new-topic" class="waves-effect waves-light btn newBtn blue">Start New Topic</a></li>
                </div>
                <div class="col s3 newBtnContainer right-align noPadding">
                    <a href="#" class="waves-effect waves-light btn newBtn blue">Create Sticky Post</a></li>
                </div>
            </div>
            <ul class="tabs tabs-transparent tabsContainer">
                <li class='tab'><a href='#users'>Users</a></li>
                <li class="tab"><a href="#categories">Categories</a></li>
                <li class='tab'><a href='#topics'>Topics</a></li>
                <li class='tab'><a href='#posts'>Posts</a></li>
                <li class='tab'><a href='#stickyPosts'>Sticky Posts</a></li>
                <li class='tab'><a href='#information'>Forum information</a></li>
            </ul>
            <div id="users" class="row">
                <div class="col s12 userCardContainer">
                    <?php
                        $userObj = new User();
                        $users = $userObj -> getAllUser();
                        
                        foreach($users as $user) {
                    ?>
                    <div class="col s5 cardSection">
                        <div class="row noMargin">
                            <div class="userCard valign-wrapper">
                                <div class="userCardContent row noMargin">
                                    <div class="col s4">
                                        <?php
                                            if($user["profileImage"] == 'defaultAvatar.png') {
                                                echo "<a href='/profile/" . $user["username"] . "'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $user["username"] . "'></a>";
                                            } else if ($user["profileImage"] == 'admin.png') {
                                                echo "<img src='/public/images/content/admin.png' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $user["username"] . "'>";
                                            } else if ($user["profileImage"] == 'anonymous.png') {
                                                echo "<img src='/public/images/content/anonymous.png' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $user["username"] . "'>";
                                            } else {
                                                echo "<a href='/profile/" . $user["username"] . "'><img src='/public/images/upload/" . $user["profileImage"] ."' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $user["username"] . "'></a>";
                                            }
                                        ?> 
                                    </div>
                                    <div class="col s8 personalInformation">
                                        <div class="row noMargin">
                                            <div class="col s11 noPadding">
                                                <h3 class="noTopMargin">
                                                    <?php 
                                                         if($user["username"] != 'admin' && $user["username"] != 'Anonymous' ) {
                                                            echo "<a href='/profile/" . $user["username"] . "'>" . $user["username"] . "</a>";
                                                        } else {
                                                            echo $user["username"];
                                                        }
                                                    ?>
                                                </h3>
                                            </div>
                                            <div class="col s1 noPadding right-align">
                                                <div class="ban center-align">
                                                    <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Ban">
                                                        <i class="fas fa-ban"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row noMargin">
                                            <p class="col s5 noMargin noPadding"><i class="far fa-envelope fa-sm"></i> Email:</p>
                                            <p class="col s7 noMargin noPadding right-align"><?php echo $user["email"]; ?></p>    
                                        </div>
                                        <div class="row noMargin">
                                            <p class="col s5 noMargin noPadding"><i class="fas fa-history fa-sm"></i> Member for:</p>
                                            <p class="col s7 noMargin noPadding right-align"><?php echo $user["memberFor"]; ?></p>    
                                        </div>
                                        <div class="row noMargin">
                                            <p class="col s5 noMargin noPadding"><i class="fas fa-circle-notch fa-sm"></i> Rank level:</p>
                                            <p class="col s7 noMargin noPadding right-align"><?php echo $user["rankID"]; ?></p>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row noMargin">
                            <div class="userIconCard row noMargin center-align">
                                <div class="col s12">
                                    <div class="row noMargin">
                                        <p class="userStat col s3"><i class="far fa-heart fa-sm"></i> <?php echo (isset($user["numberOfFollowers"]) ? $user["numberOfFollowers"] : "0"); ?></p> 
                                        <p class="userStat col s3"><i class="far fa-comments"></i> <?php echo (isset($user["numberOfTopics"]) ? $user["numberOfTopics"] : "0"); ?></p> 
                                        <p class="userStat col s3"><i class="far fa-comment-alt"></i> <?php echo (isset($user["numberOfPosts"]) ? $user["numberOfPosts"] : "0"); ?></p> 
                                        <p class="userStat col s3"><i class="far fa-thumbs-up"></i> <?php echo (isset($user["numberOfPostLikes"]) ? $user["numberOfPostLikes"] : "0"); ?></p> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div id="categories" class="row">
                <div class="categoryCardContainer col s12">
                    <?php
                        $categoryObj = new Category();
                        $categories = $categoryObj -> getCategoryData();
                        
                        foreach($categories as $category) {
                    ?>
                    <div class="post-module col s3">
                        <?php echo "<a href='/categories/" . $category["categoryID"] . "'>"; ?>
                            <!-- Thumbnail-->
                            <div class="thumbnail">
                                <div class="topicCounter">
                                    <div class="topicCounterValue"><?php echo ($category["numberOfTopics"] ? $category["numberOfTopics"] : "0"); ?></div>
                                    <div class="topicCounterTitle">Topics</div>
                                </div>
                                <?php echo "<img src='/public/images/content/categoryThumbnail/" . $category["thumbnail"] . "'>"; ?>
                            </div>
                        </a>
                            <!-- Post Content-->
                            <div class="post-content">
                                <div class="row noMargin">
                                    <div class="col s9 noPadding">
                                        <h1 class="title"><?php echo $category["categoryName"]; ?></h1>
                                    </div>
                                    <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                        <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="col s1 noPadding trashIcon titleIcon center-align">
                                        <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                <p class="description"><?php echo $category["categoryDescription"]; ?></p>
                                <div class="post-meta row">
                                    <span class="comments col s6"><i class="fa fa-comments"></i> <?php echo ($category["numberOfPosts"] ? $category["numberOfPosts"] : "0") ; ?> posts</span>
                                    <span class="favourite col s6"><i class="fas fa-heart"></i> <?php echo ($category["numberOfLikes"] ? $category["numberOfLikes"] : "0"); ?> likes</span>
                                </div>
                            </div>
                    </div>
                    <div class="col s1"></div>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div id="topics">
                <h3 class="titleName">Reported Topics </h3>
                <h3 class="titleName">Original Topics </h3>
                <?php
                    $topicObj = new Topic();
                    $topics = $topicObj -> getAllTopic();
                    
                    foreach($topics as $topic) {
                ?>
                <div class="topic row">
                    <div class="col s1 userImageContainer center-align">
                        <div class="row">
                        <?php
                            if($topic["profileImage"] == 'defaultAvatar.png') {
                                echo "<a href='/profile/" . $topic["username"] . "'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'></a>";
                            } else if ($topic["profileImage"] == 'admin.png') {
                                echo "<img src='/public/images/content/admin.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'>";
                            } else if ($topic["profileImage"] == 'anonymous.png') {
                                echo "<img src='/public/images/content/anonymous.png' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'>";
                            } else {
                                echo "<a href='/profile/" . $topic["username"] . "'><img src='/public/images/upload/" . $topic["profileImage"] ."' class='tooltipped' alt='profile picture' data-position='bottom' data-tooltip='" . $topic["username"]  . "'></a>";
                            }
                        ?> 
                        </div>
                    </div>
                    <div class="col s9 topicContainer">
                        <div class="row noMargin">
                            <div class="col s10">
                                <h3><?php echo $topic["topicName"]?></h3>
                                <em><p class="subtitle"> - in <a href="/categories/<?php echo $topic["categoryID"]?>"><?php echo $topic["categoryName"]?></a></p></em>
                            </div>
                            <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                    <i class="fas fa-pencil-alt fa-xs"></i>
                                </a>
                            </div>
                            <div class="col s1 noPadding trashIcon titleIcon center-align">
                                <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                    <i class="fas fa-trash fa-xs"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row noMargin">
                            <div class="col s12">
                                <p class="topicDescription"><?php echo $topic["topicText"]; ?></p>
                                <ul class="postAttachFiles">
                                <?php
                                $topicAttachedFiles = $topicObj->getAttachedFiles($topic["attachedFilesCode"]);
                                if($topicAttachedFiles) {
                                    foreach($topicAttachedFiles as $file) {
                                        $fileExtension = explode(".", $file["attachmentName"]);
                                        if(in_array($fileExtension[1], array('png', 'jpg', 'jpeg'))) {
                                            echo '<li><a href="/public/files/upload/' . $file["attachmentName"] . '" download="' . $file["displayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["displayName"] . '</a></li>';
                                            echo '<a href="/public/files/upload/' . $file["attachmentName"] . '" data-lightbox="attachedTopicFiles" data-title="' . $file["displayName"] . '"><img src="/public/files/upload/' . $file["attachmentName"] . '"></a>';
                                            echo "<p>(To see the original size click on the name of the image)</p>";
                                        } else {
                                            echo '<li><a href="/public/files/upload/' . $file["attachmentName"] . '" download="' . $file["displayName"] . '" target="_blank" type="applicatiob/octet-stream">' . $file["displayName"] . '</a></li>';
                                        }
                                    }
                                }
                                ?>
                            </ul>
                            </div>
                        </div>
                        <div class="row noMargin">
                            <div class="col s12 right-align">
                                <p class="viewTopic"><a href="/topics/<?php echo $topic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                            </div>
                        </div>   
                        <div class="row noMargin">
                            <div class="col s7">
                                <?php 
                                if($topic["periodName"] !== "none") {
                                    echo "<img src='/public/images/content/semesterMarker/" . $topic['periodName'] . ".png' class='periodImage'>";
                                }
                                ?>
                            </div>
                            <div class="col s5 right-align">
                                <div class="topicCreated">Created at: <?php echo $topic["createdAt"]?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col s2 topicInformation">
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
                </div>
                <?php } ?>
            </div>
            <div id="posts">
                <h3 class="titleName">Reported Posts </h3>
                <h3 class="titleName">Original Posts </h3>
                <?php
                    $postObj = new Post();
                    $posts = $postObj -> getAllPost();
                    
                    for($i = 0; $i < count($posts); $i++) {
                ?>
                    <div class="topic post">
                        <div class="row postContent">
                            <div class="col s1 userImageContainer center-align">
                                <?php
                                    if($posts[$i]['profileImage'] == 'defaultAvatar.png') {
                                        echo "<a href='/profile/" . $posts[$i]["username"] . "'><img src='/public/images/content/defaultAvatar.png' class=' newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"] . "'></a>";
                                    } else if ($posts[$i]['profileImage'] == 'admin.png') {
                                        echo "<img src='/public/images/content/admin.png' class=' newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"] . "'>";
                                    } else if ($posts[$i]['profileImage'] == 'anonymous.png') {
                                        echo "<img src='/public/images/content/anonymous.png' class=' newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"] . "'>";
                                    } else {
                                        echo "<a href='/profile/" . $posts[$i]["username"] . "'><img src='/public/images/upload/" . $posts[$i]["profileImage"] ."' class='newAvatarImg tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $posts[$i]["username"] . "'></a>";
                                    }
                                ?>
                            </div>
                            <div class="col s9 topicContainer">
                                <div class="row postContainer">
                                    <div class="col s10 postedBy">
                                        <?php
                                            if($posts[$i]['username'] == 'admin' && $posts[$i]['username'] != 'Anonymous' ) {
                                                echo "<p class='noMargin adminTitle'>" . $posts[$i]['username'] . "</p>";
                                            } else if($posts[$i]['username'] == 'Anonymous') {
                                                echo $posts[$i]['username'];
                                            } else {
                                                echo "<a href='/profile/" . $posts[$i]['username'] . "'>" . $posts[$i]['username'] . "</a>"; 
                                           }
                                        ?>  
                                    </div>
                                    <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                        <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="col s1 noPadding trashIcon titleIcon center-align">
                                        <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php 
                                    if(isset($posts[$i]["replyID"])) {
                                ?>
                                        <div class="replyContent">
                                            <div class="row postContainer">
                                                <div class="col s12 postedBy">
                                                    <span>Original Posted by - </span>
                                                    <a href="/profile/<?php echo $posts[$posts[$i]["replyID"]-1]['username']; ?>"><?php echo $posts[$posts[$i]["replyID"]-1]['username']; ?>:</a>
                                                </div>
                                            </div>
                                            <p class="topicDescription"><?php echo $posts[$posts[$i]["replyID"]-1]["text"];?></p>
                                        </div>      
                                <?php } ?>
                                <p class="topicDescription"><?php echo $posts[$i]["text"]; ?></p>
                                <ul class="postAttachFiles">
                                    <?php 
                                        $attachedFiles = $postObj->getAttachedFiles($posts[$i]["attachedFilesCode"]);
                                        if($attachedFiles) {
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
                                        }
                                    ?>
                                </ul>
                                <div class="row postContainer">
                                    <div class="col s8">
                                        <em><span>This post belongs to the <a href="/topics/<?php echo $posts[$i]["topicID"];?>"><?php echo $posts[$i]["topicName"]; ?></a> topic </span></em>
                                    </div>
                                    <div class="col s4 postedOn">
                                        <i class="far fa-clock fa-xs"></i>
                                        <span>Posted on: <?php echo $posts[$i]["postedOn"]; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col s2 valign-wrapper postRightSection">
                                <div>
                                    <div class="row center-align noBottomMargin">
                                        <div class="col s6">
                                            <i class="far fa-thumbs-up fa-lg"></i>
                                            <span><?php echo ($posts[$i]["numberOfLikes"] ? $posts[$i]["numberOfLikes"] : "0"); ?></span>
                                        </div>
                                        <div class="col s6">
                                            <i class="far fa-thumbs-down fa-lg center-align"></i>
                                            <span><?php echo ($posts[$i]["numberOfDislikes"] ? $posts[$i]["numberOfDislikes"] : "0"); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                ?>
            </div>
            <div id="stickyPosts">
                
            </div>
            <div id="information">
                
            </div>
        </div>
    </div>
</div>