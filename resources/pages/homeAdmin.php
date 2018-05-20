<?php
if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
    $userObj = new User();
?>
<div class="container contentContainer homeAdmin">
    <div class="row noBottomMargin">
        <div class="col s12"> 
            <div class="row noMargin">
                <div class="col s2 pageTitle">
                    <h2>Dashboard</h2>
                </div>
                <div class="col s3 offset-s1 newBtnContainer right-align noPadding">
                    <a href="/new-category" class="waves-effect waves-light btn newBtn blue">Create New Category</a></li>
                </div>
                <div class="col s3 newBtnContainer right-align noPadding">
                    <a href="/new-topic" class="waves-effect waves-light btn newBtn blue">Start New Topic</a></li>
                </div>
                <div class="col s3 newBtnContainer right-align noPadding">
                    <a href="/new-sticky-post" class="waves-effect waves-light btn newBtn blue">Create Sticky Post</a></li>
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
                        if($users) {
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
                                                    <a class="tooltipped" data-position="bottom" data-tooltip="Suspend" onclick="suspendUser('<?php echo $user["userID"]; ?>')">
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
                                            <p class="col s7 noMargin noPadding right-align"><?php echo $user["rankTitle"]; ?></p>    
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
                    } else {
                    ?>
                        <p class="noMargin empty">Something went wrong, please refresh the page.</p>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div id="categories" class="row">
                <div class="categoryCardContainer col s12">
                    <?php
                        $categoryObj = new Category();
                        $categories = $categoryObj -> getAllCategoryData();
                        
                        if($categories) {
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
                                        <a onclick="adminModification('category', <?php echo $category['categoryID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="col s1 noPadding trashIcon titleIcon center-align">
                                        <a onclick="adminDelition('home', '#categories', 'category', <?php echo $category['categoryID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
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
                        } else {
                    ?>
                        <p class="noMargin empty">There isn't any category yet.</p>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div id="topics">
                <?php
                    $topicObj = new Topic();
                    $topics = $topicObj -> getAllTopic();
                    
                    if($topics) {
                ?>
                <h3 class="titleName">Reported Topics </h3>
                <?php
                    foreach($topics as $topic) {
                        if($topic["isReported"] == 1) {
                ?>
                <div class="topic row topicGrid">
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
                        <?php
                        
                        if (
                            $topic["rankID"] == 2 ||
                            $topic["rankID"] == 3 ||
                            $topic["rankID"] == 4) {
                        ?>
                        <div class="row">
                            <div class="col s12">
                                <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $topic['rankColor'] . "'"; ?>></i>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="col s9 topicContainer">
                        <div class="row noMargin">
                            <div class="col s10">
                                <h3><?php echo $topic["topicName"]?></h3>
                                <em><p class="subtitle"> - in <a href="/categories/<?php echo $topic["categoryID"]?>"><?php echo $topic["categoryName"]?></a></p></em>
                            </div>
                            <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                <a onclick="adminModification('topic', <?php echo $topic['topicID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                    <i class="fas fa-pencil-alt fa-xs"></i>
                                </a>
                            </div>
                            <div class="col s1 noPadding trashIcon titleIcon center-align">
                                <a onclick="adminDelition('home', '#topics', 'topic', <?php echo $topic['topicID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
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
                <?php 
                        }
                    } 
                }
                ?>
                <h3 class="titleName">Original Topics </h3>
                <?php
                    if($topics) {
                        foreach($topics as $topic) {
                            if($topic['isReported'] != 1) {
                ?>
                <div class="topic row topicGrid">
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
                        <?php
                        if (
                                $topic["rankID"] == 2 ||
                                $topic["rankID"] == 3 ||
                                $topic["rankID"] == 4) {
                            ?>
                            <div class="row">
                                <div class="col s12">
                                    <i class="fas fa-trophy rankTrophy" <?php echo "style='color: " . $topic['rankColor'] . "'"; ?>></i>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                    </div>
                    <div class="col s9 topicContainer">
                        <div class="row noMargin">
                            <div class="col s10">
                                <h3><?php echo $topic["topicName"]?></h3>
                                <em><p class="subtitle"> - in <a href="/categories/<?php echo $topic["categoryID"]?>"><?php echo $topic["categoryName"]?></a></p></em>
                            </div>
                            <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                <a onclick="adminModification('topic', <?php echo $topic['topicID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                    <i class="fas fa-pencil-alt fa-xs"></i>
                                </a>
                            </div>
                            <div class="col s1 noPadding trashIcon titleIcon center-align">
                                <a onclick="adminDelition('home', '#topics', 'topic', <?php echo $topic['topicID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
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
                <?php 
                        }
                    }
                } else {
                ?>
                    <p class="noMargin empty">There isn't any topic yet.</p>
                <?php
                    }
                ?>
            </div>
            <div id="posts">
                <?php
                    $postObj = new Post();
                    $posts = $postObj -> getAllPost();

                    if($posts) {
                ?>
                <h3 class="titleName">Reported Posts </h3>
                <?php
                    for($i = 0; $i < count($posts); $i++) {
                        if($posts[$i]["isReported"] == 1) {
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
                            <?php
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
                            <div class="col s9 topicContainer">
                                <div class="row postContainer">
                                    <div class="col s10 postedBy">
                                        <?php
                                            if($posts[$i]['username'] == 'admin') {
                                                echo "<span class='noMargin adminTitle'>" . $posts[$i]['username'] . "</span>";
                                            } else if($posts[$i]['username'] == 'Anonymous') {
                                                echo "<span>" . $posts[$i]['username'] . "</span>";
                                            } else {
                                                echo "<a href='/profile/" . $posts[$i]['username'] . "'>" . $posts[$i]['username'] . "</a>"; 
                                            }
                                        ?>  
                                    </div>
                                    <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                        <a onclick="adminModification('post', <?php echo $posts[$i]["postID"]; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="col s1 noPadding trashIcon titleIcon center-align">
                                        <a onclick="adminDelition('home', '#posts', 'post', <?php echo $posts[$i]['postID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php 
                                    if(isset($posts[$i]["replyID"])) {
                                        if($posts[$i]["replyID"] != -1) {
                                ?>
                                        <div class="replyContent">
                                            <div class="row postContainer">
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
                                        <?php if($posts[$i]["isSticky"] == 1) { ?>
                                            <span class="stickyPin">
                                                <i class="fas fa-thumbtack"></i>
                                            </span>
                                        <?php } ?>
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
                    }
                }
                ?>
                <h3 class="titleName">Original Posts </h3>
                <?php
                    if($posts) {
                        for($i = 0; $i < count($posts); $i++) {
                            if($posts[$i]["isReported"] != 1 && $posts[$i]["isSticky"] != 1) {
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
                            <div class="col s9 topicContainer">
                                <div class="row postContainer">
                                    <div class="col s10 postedBy">
                                        <?php
                                            if($posts[$i]['username'] == 'admin') {
                                                echo "<span class='noMargin adminTitle'>" . $posts[$i]['username'] . "</span>";
                                            } else if($posts[$i]['username'] == 'Anonymous') {
                                                echo "</span>" . $posts[$i]['username'] . "</span>";
                                            } else {
                                                echo "<a href='/profile/" . $posts[$i]['username'] . "'>" . $posts[$i]['username'] . "</a>"; 
                                            }
                                        ?>  
                                    </div>
                                    <div class="col s1 noPadding pencilIcon titleIcon center-align">
                                        <a onclick="adminModification('post', <?php echo $posts[$i]["postID"]; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="col s1 noPadding trashIcon titleIcon center-align">
                                        <a onclick="adminDelition('home', '#posts', 'post', <?php echo $posts[$i]['postID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php 
                                    if(isset($posts[$i]["replyID"])) {
                                        if($posts[$i]["replyID"] != -1) {
                                ?>
                                        <div class="replyContent">
                                            <div class="row postContainer">
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
                    }
                } else {
                ?>
                    <p class="noMargin empty">There isn't any post yet.</p>
                <?php
                    }
                ?>
            </div>
            <div id="stickyPosts">
                <h3 class="titleName">Sidebar stickies </h3>
                <div class="row noMargin stickyContainer">
                <?php
                    $stickyPosts = $postObj -> getAllSidebarSticky();
                    if($stickyPosts) {
                        foreach($stickyPosts as $stickyPost) {
                ?>
                        <div class="col s4 sticky">
                            <div class="stickyPost">
                                <div class="row noMargin">
                                    <h4 class="col s9 noPadding"><?php echo $stickyPost["stickyPostTitle"]; ?></h4>
                                    <div class="col s1 pencilIcon titleIcon center-align">
                                        <a onclick="adminModification('sticky', <?php echo $stickyPost['stickyPostID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="col s1 trashIcon titleIcon center-align">
                                        <a onclick="adminDelition('home', '#stickyPosts', 'sticky', <?php echo $stickyPost['stickyPostID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="line"></div>
                                <div class="stickyContent">
                                    <p><?php echo $stickyPost["stickyPostText"]; ?> </p>
                                </div>
                            </div>
                        </div>
                <?php
                        }
                    } else {
                    ?>
                        <p class="noMargin">There isn't any sticky post yet.</p>
                    <?php
                        }
                    ?>
                </div>
                <h3 class="titleName">Topic related stickies </h3>
                <div class="topicRelatedStickies">
                <?php
                    if($posts) {
                        for($i = 0; $i < count($posts); $i++) {
                            if($posts[$i]["isSticky"] == 1) {
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
                            <div class="col s9 topicContainer">
                                <div class="row postContainer stickyPostContainer">
                                    <div class="col s9 postedBy">
                                        <?php
                                            if($posts[$i]['username'] == 'admin') {
                                                echo "<span class='noMargin adminTitle'>" . $posts[$i]['username'] . "</span>";
                                            } else if($posts[$i]['username'] == 'Anonymous') {
                                                echo "<span>" . $posts[$i]['username'] . "</span>";
                                            } else {
                                                echo "<a href='/profile/" . $posts[$i]['username'] . "'>" . $posts[$i]['username'] . "</a>"; 
                                            }
                                        ?>  
                                    </div>
                                    <div class="col s2 right-align noPadding">
                                        <a class="waves-effect waves-light btn stickyBtn" onclick="setSticky(<?php echo $posts[$i]["postID"]?>)">Unsticky</a>
                                    </div>
                                    <div class="noPadding pencilIcon titleIcon center-align">
                                        <a onclick="adminModification('post', <?php echo $posts[$i]["postID"]; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                            <i class="fas fa-pencil-alt fa-xs"></i>
                                        </a>
                                    </div>
                                    <div class="noPadding trashIcon titleIcon center-align">
                                        <a onclick="adminDelition('home', '#stickyPosts', 'post', <?php echo $posts[$i]['postID']; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php 
                                    if(isset($posts[$i]["replyID"])) {
                                        if($posts[$i]["replyID"] != -1) {
                                ?>
                                        <div class="replyContent">
                                            <div class="row postContainer">
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
                    } 
                } else {
                ?>
                    <p class="noMargin empty">There isn't any post yet.</p>
                <?php
                    }
                ?>
                </div>
            </div>
            <div id="information">
                <?php
                    $generalObj = new General();
                    $descriptionOfTheSite = $generalObj -> getDescriptionOfTheSite();
                    $contactInformation = $generalObj -> getContactInformation();
                    $rulesAndRegulations = $generalObj -> getRulesAndRegulationsData();
                ?>
                <div class="row">
                    <div class="col s3 verticalTabContainer">
                        <ul class="tabs verticalTabs">
                            <li class="tab"><a href="#contact">Contact informations</a></li>
                            <li class="tab"><a href="#rules">Rules and regulations</a></li>
                            <li class="tab"><a href="#description">Description of the site</a></li>
                        </ul>
                    </div>
                    <div class="col s8 verticalTabContent">
                        <div id="description">
                            <form class="modifyDescriptionForm" action="" method="post" id="modifyDescriptionForm">  
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newDescription" class="materialize-textarea"><?php echo $descriptionOfTheSite[0]["aboutUs"]; ?></textarea>
                                        <label for="newDescription">About Us</label>
                                    </div>
                                </div>
                                <div class="row inputContainer">
                                    <div class="col s8">
                                        <p id="descriptionError" class="errorMsg"></p>
                                    </div>
                                    <div class="col s1">
                                        <div class="preloader-wrapper small active hide modifyDescriptionSpinner">
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
                                    <a class="btn waves-effect waves-light blue col s2 changeForumInformationBtn" id="modifyDescriptionSubmit">Save</a>
                                </div>
                            </form>
                        </div>
                        <div id="contact">
                            <form class="modifyContactForm" action="" method="post" id="modifyContactForm">  
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newGeneralContactText" class="materialize-textarea"><?php echo $contactInformation[0]["generalText"]; ?></textarea>
                                        <label for="newGeneralContactText">General text</label>
                                    </div>
                                </div>
                                <div class="row inputContainer">
                                    <div class="input-field forumInformationInput">
                                        <input id="newPhoneNumber" type="text" class="noBottomMargin" value="<?php echo $contactInformation[0]["phoneNumber"]; ?>">
                                        <label for="newPhoneNumber">Phone number</label>
                                    </div>
                                </div>
                                <div class="row inputContainer">
                                    <div class="input-field forumInformationInput">
                                        <input id="newLocation" type="text" class="noBottomMargin" value="<?php echo $contactInformation[0]["location"]; ?>">
                                        <label for="newLocation">Location</label>
                                    </div>
                                </div>
                                <div class="row inputContainer">    
                                    <div class="col s8">
                                        <p id="contactErrorMsg" class="errorMsg"></p>
                                    </div>
                                    <div class="col s1">
                                        <div class="preloader-wrapper small active hide modifyContactSpinner">
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
                                    <a class="btn waves-effect waves-light blue col s2 changeForumInformationBtn" id="modifyContactSubmit">Save</a>
                                </div>
                            </form>
                        </div>
                        <div id="rules">
                            <form class="modifyRules" action="" method="post" id="modifyRulesForm">  
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newGeneralRules" class="materialize-textarea"><?php echo $rulesAndRegulations[0]["generalTxt"]; ?></textarea>
                                        <label for="newGeneralRules">General information about rules</label>
                                    </div>
                                </div>
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newAcceptanceOfTerms" class="materialize-textarea"><?php echo $rulesAndRegulations[0]["acceptanceOfTerms"]; ?></textarea>
                                        <label for="newAcceptanceOfTerms">Acceptance of Terms</label>
                                    </div>
                                </div>
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newModificationOfTerms" class="materialize-textarea"><?php echo $rulesAndRegulations[0]["modificationOfTerms"]; ?></textarea>
                                        <label for="newModificationOfTerms">Modification of Terms of Use</label>
                                    </div>
                                </div>
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newRulesAndConduct" class="materialize-textarea"><?php echo $rulesAndRegulations[0]["rulesAndConduct"]; ?></textarea>
                                        <label for="newRulesAndConduct">Rules and Conduct</label>
                                    </div>
                                </div>
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newTermination" class="materialize-textarea"><?php echo $rulesAndRegulations[0]["termination"]; ?></textarea>
                                        <label for="newTermination">Termination</label>
                                    </div>
                                </div>
                                <div class="row textareaContainer">
                                    <div class="input-field forumInformationInput">
                                        <textarea id="newIntegration" class="materialize-textarea"><?php echo $rulesAndRegulations[0]["integration"]; ?></textarea>
                                        <label for="newIntegration">Integration and Severability</label>
                                    </div>
                                </div>
                                <div class="row inputContainer">
                                    <div class="col s8">
                                        <p id="rulesErrorMsg" class="errorMsg"></p>
                                    </div>
                                    <div class="col s1">
                                        <div class="preloader-wrapper small active hide modifyRulesSpinner">
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
                                    <a class="btn waves-effect waves-light blue col s2 changeForumInformationBtn" id="modifyRulesSubmit">Save</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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