<?php
    if(isset($_SESSION["selectedUsername"]) && $_SESSION["selectedUsername"] != "admin" && $_SESSION["selectedUsername"] != "Anonymous") {
        $userObj = new User();
        $categoryObj = new Category();
        $topicObj = new Topic();
        $postObj = new Post();
        $selectedUsername = htmlspecialchars(trim($_SESSION["selectedUsername"]));
        if(isset($loggedUser) && $loggedUser["username"] === $selectedUsername) {
            $myprofile = true;
            include("resources/modals/avatarChange.php");
        } else {
            $myprofile = false;
            $userObj->increaseNumberOfVisitors($selectedUsername);
        }
        $selectedUserData = $userObj -> getSelectedUser($selectedUsername);
        $favouriteCategories = $categoryObj->getFavouriteCategoryData($selectedUserData["userID"]);
        $favouriteTopics = $userObj->getFavouriteTopics($selectedUserData["userID"]);
        $likedPosts = $userObj->getLikedPosts($selectedUsername);
        $createdTopics = $userObj->getCreatedTopics($selectedUsername);
        if($selectedUserData["accessLevel"] != 0) {
?>
<div class="container contentContainer">
    <div class="row breadCrumbContainer">
        <div class="col s12">
            <div class="titleSection">
                <div class="breadCrumb">
                    <a href="/home">Home</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories">My profile</a>
                </div>
            </div>
        </div>
    </div>
    <div class="profileContainer">
        <div class="profileImageContainer textCenter">    
            <div class="image <?php echo ($myprofile ? "changeAvatar" : ""); ?>">
                <a <?php echo ($myprofile ? "href='#avatarChange'" : ""); ?> class="modal-trigger">
                    <?php

                    echo "<img src='"; 
                    echo ($selectedUserData['profileImage'] === 'defaultAvatar.png' ?'/public/images/content/defaultAvatar.png' : '/public/images/upload/' . $selectedUserData["profileImage"]);
                    echo "' class='newAvatarImg' alt='profile picture'>";

                    ?>
                    <div class="fadeContainer">
                        <i class="far fa-images fa-3x"></i>
                        <p class="textCenter">Change picture</p>
                    </div>
                </a>
            </div>
            <p class="userName">
                <?php echo $selectedUserData['username']; ?>
            </p>
        </div>
        <div class="row countContainer">
            <div class="col s5">
                <div class="row">
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="far fa-heart fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="<?php echo (isset($selectedUserData["numberOfFollowers"]) ? $selectedUserData["numberOfFollowers"] : "0"); ?>" data-speed="1500"></p>
                        <p class="textCenter countTitle">Follow your topics</p>
                    </div>
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="far fa-comments fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="<?php echo (isset($selectedUserData["numberOfTopics"]) ? $selectedUserData["numberOfTopics"] : "0"); ?>" data-speed="1500"></p>
                        <p class="textCenter countTitle">Created topics</p>
                    </div>
                </div>
            </div>
            <div class="col s5 offset-s2">
                <div class="row">
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="far fa-comment-alt fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="<?php echo (isset($selectedUserData["numberOfPosts"]) ? $selectedUserData["numberOfPosts"] : "0"); ?>" data-speed="1500"></p>
                        <p class="textCenter countTitle">Written posts</p>
                    </div>
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="far fa-thumbs-up fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="<?php echo (isset($selectedUserData["numberOfPostLikes"]) ? $selectedUserData["numberOfPostLikes"] : "0"); ?>" data-speed="1500"></p>
                        <p class="textCenter countTitle">Likes of posts</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row">
            <div class="tabsContainer">
                <ul class="tabs tabs-transparent tabList">
                    <li class="tab"><a href="#userStatistics">User Statistics</a></li>
                    <?php echo (!$favouriteCategories && !$favouriteTopics && !$likedPosts ? "" : "<li class='tab'><a href='#favourites'>Favourites</a></li>"); ?>
                    <li class="tab"><a href="#ownThings" onclick="getOwnData('<?php echo $selectedUsername; ?>')">Own things</a></li>
                    <li class="tab"><a href="#general">General informations</a></li>
                    <?php echo (!$myprofile ? "" : "<li class='tab'><a href='#settings'>Settings</a></li>"); ?>
                </ul>
                <div id="userStatistics">
                    <div class="row">
                        <div class="col s12 activityChartContainer">
                            <p class="chartTitle">Activity</p>
                            <div id="activityChart"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6 historyChartContainer">
                            <p class="chartTitle lessMargin">History</p>
                            <svg style="height: 0">
                                <defs>
                                    <linearGradient id="gradient-0" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0" />
                                        <stop offset="1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div id="historyChart"></div>
                        </div>
                        <div class="col s6 pieChartContainer">
                            <p class="chartTitle lessMargin">Social Influence</p>
                            <div id="pieChart"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6 donutChartContainer">
                            <p class="chartTitle lessTopMargin">Acceptance</p>
                            <p class="noValue noAcceptanceValue class center-align">There is no data for this chart</p>
                            <div id="donutChart"></div>
                        </div>
                        <div class="col s6 reputationChartContainer">
                            <p class="chartTitle lessTopMargin">Reputation</p>
                            <div id="reputationChart" class="row">
                                <div class="col s12">
                                    <div class="row firstRow">
                                        <div class="col s4 offset-s1 reputationCard">
                                            <div class="row">
                                                <div class="col s3 iconContainer">
                                                    <i class="fas fa-chart-area fa-3x"></i>
                                                </div>
                                                <div class="col s9">
                                                    <div class="row noMargin">
                                                        <div class="col s12">
                                                            <p class="reputationLabel noBottomMargin">Average like on posts</p>
                                                        </div>
                                                    </div>
                                                    <div class="row noMargin">
                                                        <div class="col s12">
                                                            <p class="noBottomMargin reputationValue reputationValueFirstLine reputationLikePosts center-align"><?php echo (isset($selectedUserData["numberOfPosts"]) && isset($selectedUserData["numberOfPostLikes"]) ? Round($selectedUserData["numberOfPostLikes"] / $selectedUserData["numberOfPosts"], 1) : "0"); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s4 offset-s2 reputationCard">
                                            <div class="row">
                                                <div class="col s3 iconContainer">
                                                    <i class="fas fa-chart-bar fa-3x"></i>
                                                </div>
                                                <div class="col s9">
                                                    <div class="row noMargin">
                                                        <div class="col s12 noRightPadding">
                                                            <p class="reputationLabel noBottomMargin">Average topic popularity</p>
                                                        </div>
                                                    </div>
                                                    <div class="row noMargin">
                                                        <div class="col s12">
                                                            <p class="noBottomMargin reputationValue reputationValueFirstLine reputationTopicPopularity center-align"><?php echo (isset($selectedUserData["numberOfFollowers"]) && isset($selectedUserData["numberOfTopics"]) ? Round($selectedUserData["numberOfFollowers"] / $selectedUserData["numberOfTopics"], 1) : "0"); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row firstRow">
                                        <div class="col s4 offset-s4 reputationCard">
                                        <div class="row">
                                                <div class="col s3 iconContainer lastIconContainer">
                                                    <i class="fas fa-eye fa-3x"></i>
                                                </div>
                                                <div class="col s9">
                                                    <div class="row noMargin">
                                                        <div class="col s12">
                                                            <p class="reputationLabel noBottomMargin">Profile visitor</p>
                                                        </div>
                                                    </div>
                                                    <div class="row noMargin">
                                                        <div class="col s12">
                                                            <p class="noBottomMargin reputationValue reputationProfileVisitor center-align"><?php echo $selectedUserData["visitors"]; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="favourites">
                    <?php
                        if(isset($favouriteCategories) && $favouriteCategories) {
                    ?>
                    <div class="row noBottomMargin">
                        <div class="col s12">
                            <p class="favouriteTitle">Favourite Categories</p>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                            foreach($favouriteCategories as $favouriteCategory) {
                                ?>
                                    <div class="col s3">
                                        <a href="/categories/<?php echo $favouriteCategory["categoryID"]; ?>">
                                        <div class="card categoryCard">
                                            <div class="card-image">
                                                <img src="/public/images/content/categoryThumbnail/<?php echo $favouriteCategory["thumbnail"]; ?>" alt="category image">
                                                <div class="fade"></div>
                                            </div>
                                            <div class="card-content">
                                                <span class="card-title grey-text text-darken-4"><?php echo $favouriteCategory["categoryName"]; ?></span>
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="row noBottomMargin">
                        <?php
                            if(isset($favouriteTopics) && $favouriteTopics) {
                        ?>
                        <div class="col s7">
                            <p class="favouriteTitle">Followed Topics</p>
                        </div>
                        <?php
                            }

                            if(isset($likedPosts) && $likedPosts) {
                        ?>
                        <div class="col s5">
                            <p class="favouriteTitle">Liked Posts</p>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="row">
                        <?php
                            if(isset($favouriteTopics) && $favouriteTopics) {
                        ?>
                        <div class="col s7">
                        <?php
                                foreach($favouriteTopics as $favouriteTopic) {
                            ?>
                            <div class="contentCard favouriteTopicCard">
                                <div class="col s1 createdByContainer">
                                    <?php
                                        echo "<a href='/profile/" . $favouriteTopic["username"] . "'><img src='";
                                        if($favouriteTopic["profileImage"] == 'defaultAvatar.png') {
                                            echo '/public/images/content/defaultAvatar.png';
                                        } else {
                                            echo "/public/images/upload/" . $favouriteTopic["profileImage"];
                                        }
                                        echo "' class='tooltipped' alt='profile picture' data-position='bottom' data-delay='50' data-tooltip='" . $favouriteTopic["username"] . "'></a>";
                                    ?>
                                </div>
                                <div class="col s11 contentCardBody">
                                    <h3><?php echo $favouriteTopic["shortTopicName"]; ?></h3>
                                    <p><?php echo $favouriteTopic["topicDescription"]; ?></p>
                                    <p class="redirectLink"><a href="/topics/<?php echo $favouriteTopic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                                    <div class="clear"></div>
                                    <div class="row">
                                        <div class="col s4">
                                            <a href="/categories/<?php echo $favouriteTopic["categoryID"]; ?>"><div class="chip topicCategory"><?php echo $favouriteTopic["categoryName"]; ?></div></a>
                                        </div>
                                        <div class="col s6 offset-s2">
                                            <p class="right-align createdAt"><em>Created at: <?php echo $favouriteTopic["createdAt"]; ?></em></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                    }
                        ?>
                        </div>
                        <?php
                            }
                            
                            if(isset($likedPosts) && $likedPosts) {
                        ?>
                        <div class="col s5">
                            <div class="latestPostsContainer">
                            <ul>
                                <?php
                                    foreach($likedPosts as $likedPost) {
                                ?>
                                <li>
                                    <span></span>
                                        <div class="title"><?php echo $likedPost["shortTopicName"];?></div>
                                        <div class="info"><?php echo $likedPost["shortPostText"];?></div>
                                    <div class="time"><span><?php echo $likedPost["monthDay"];?></span><span><?php echo $likedPost["time"];?></span></div>
                                </li>
                                <?php
                                    }
                                ?>
                            </ul>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div id="ownThings">
                    <div class="row noBottomMargin">
                        <div id="animationWindow"></div>
                    </div>
                    <div id="valami">
                    <div class="row noBottomMargin">
                        <div class="col s6">
                            <p class="ownTitle noMargin">Created Topics</p>
                        </div>
                    </div>
                    <div class="row createdTopics">
                        <div class="col s10 offset-s1">
                            <?php
                                if($createdTopics) {
                                    foreach($createdTopics as $createdTopic) {
                            ?>
                                    <div class="contentCard ownTopicCard">
                                        <div class="col s10 ownTopicContainer contentCardBody">
                                            <h3><?php echo $createdTopic["shortTopicName"]; ?></h3>
                                            <p><?php echo $createdTopic["topicDescription"]; ?></p>
                                            <p class="redirectLink"><a href="/topics/<?php echo $createdTopic["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                                            <div class="clear"></div>
                                            <div class="row ownTopicBottomSection">
                                                <div class="col s4">
                                                    <a href="/categories/<?php echo $createdTopic["categoryID"]; ?>"><div class="chip ownTopicCategory topicCategory"><?php echo $createdTopic["categoryName"]; ?></div></a>
                                                </div>
                                                <div class="col s6 offset-s2">
                                                    <p class="right-align ownCreatedAt"><em>Created at: <?php echo $createdTopic["createdAt"]; ?></em></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s2">
                                            <div class="comments">
                                                <div class="commentbg">
                                                    <span><?php echo ($createdTopic["numberOfPosts"] ? $createdTopic["numberOfPosts"] : '0'); ?></span>
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
                                                        <p class="noMargin"><?php echo ($createdTopic["numberOfFollowers"] ? $createdTopic["numberOfFollowers"] : '0'); ?> Followers</p>
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
                    <div class="row createdPosts hide">
                        <div class="col s10 offset-s1 ownConteiner">
                            <?php
                            if(isset($_POST["data"])) {
                                
                                $userCreations = $_POST["data"];
                                if($userCreations) {
                                    foreach($userCreations as $userCreation) {
                            ?>
                            <div class="row noMargin">
                                <div class="contentCard col s10 offset-s1 noBottomMargin ownPostTopicCard">
                                    <div class="col s12 ownPostTopicContainer contentCardBody">
                                        <h3><?php echo $userCreation["topicName"]; ?></h3>
                                        <p class="redirectLink"><a href="/topics/<?php echo $userCreation["topicID"]; ?>">View the topic <i class="fas fa-angle-double-right"></i></a></p>
                                        <div class="clear"></div>
                                        <div class="row ownPostTopicBottomSection">
                                            <div class="col s4">
                                                <a href="/categories/<?php echo $userCreation["categoryID"]; ?>"><div class="chip ownTopicCategory topicCategory"><?php echo htmlspecialchars($userCreation["categoryName"]); ?></div></a>
                                            </div>
                                            <div class="col s6 offset-s2">
                                                <p class="right-align ownCreatedAt"><em>Created at: <?php echo $userCreation["createdAt"]; ?></em></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                foreach($userCreation["posts"] as $post) {
                            ?>
                            <div class="row noMargin">
                                <div class="threeDot col s12 center-align"></div>
                            </div>
                            <div class="row noMargin">
                                <div class="contentCard noMargin col s12 ownPostCard">
                                    <div class="col s10 ownPostContainer contentCardBody">
                                        <p class="ownPostText"><?php echo $post["text"]; ?></p>
                                        <div class="clear"></div>
                                        <div class="row ownPostBottomSection valign-wrapper">
                                            <div class="col s4">
                                                <p class="left-align ownCreatedAt"><em>Posted on: <?php echo $post["postedOn"]; ?></em></p>
                                            </div>
                                            <div class="col s6 offset-s2">
                                                <p class="viewPost right-align"><a href="/post/<?php echo $post["postID"]; ?>">View the post <i class="fas fa-angle-double-right"></i></a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s2 valign-wrapper ownPostRightSection">
                                        <div>
                                            <div class="row center-align noBottomMargin">
                                                <div class="col s6">
                                                    <i class="far fa-thumbs-up fa-lg"></i>
                                                    <span><?php echo ($post["numberOfLikes"] ? $post["numberOfLikes"] : "0"); ?></span>
                                                </div>
                                                <div class="col s6">
                                                    <i class="far fa-thumbs-down fa-lg center-align"></i>
                                                    <span><?php echo ($post["numberOfDislikes"] ? $post["numberOfDislikes"] : "0"); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                            </div>
                </div>
                <div id="general">
                    <div class="row">
                        <div class="col s6">
                            <p class="profileTitle">Personal Information</p>
                            <div class="personalInformationContainer col s10">
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"> <i class="far fa-user"></i></div>
                                        <div class="col s10 personalInformationLabel">Username:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        <?php echo $selectedUserData["username"]; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="far fa-envelope"></i></div>
                                        <div class="col s10 personalInformationLabel">Email:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        <?php echo $selectedUserData["email"]; ?>
                                    </div>
                                </div>
                                <?php if(!is_null($selectedUserData["birthdate"])) { ?>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="far fa-calendar"></i></div>
                                        <div class="col s10 personalInformationLabel">Birthdate:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        <?php echo $selectedUserData["birthdate"]; ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(!is_null($selectedUserData["location"])) { ?>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="fas fa-globe"></i></div>
                                        <div class="col s10 personalInformationLabel">Country/Region:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                    <?php echo $selectedUserData["location"]; ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="fas fa-circle-notch"></i></div>
                                        <div class="col s10 personalInformationLabel">Rank level:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                    <?php echo $selectedUserData["rankID"]; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="fas fa-history"></i></div>
                                        <div class="col s10 personalInformationLabel">Member for:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        since <?php echo $selectedUserData["memberFor"]; ?>
                                    </div>
                                </div>
                                <?php if($selectedUserData["lastSeen"]) { ?>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="far fa-clock"></i></div>
                                        <div class="col s10 personalInformationLabel">Last seen:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        <?php echo $selectedUserData["lastSeen"]; ?> ago
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(!is_null($selectedUserData["aboutMe"])) { ?>
                                <div class="row">
                                    <div class="col s5">
                                    <div class="col s1 personalInformationIcon"><i class="far fa-smile"></i></div>
                                        <div class="col s10 personalInformationLabel">About me:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                    <?php echo $selectedUserData["aboutMe"]; ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="earnedBadgeContainer">
                                <?php $earnedBadges = $userObj->getEarnedBadges($selectedUsername); ?>
                                <div class="row noMargin">
                                    <div class='col s2 noPadding'>
                                        <p class="profileTitle">Badges </p> 
                                    </div>
                                    <div class="col s9 badgeDetails valign-wrapper noPadding">
                                        <a href="#badgeSystem" class="modal-trigger"><i class="fas fa-bars"></i></a>
                                    </div>
                                </div>
                                <?php 
                                    if($earnedBadges) {
                                ?>
                                <div class="row badgeContainer">
                                    <?php 
                                        foreach($earnedBadges as $badge) {
                                    ?>
                                            <div class="col s5 badge ownBadge center-align">
                                                <i class="fas fa-circle dot"></i>
                                                <?php echo $badge['badgeName']?>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <?php } else { ?>
                                <p class="noMargin">Have not received a badge yet</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>     
                    <?php
                        if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
                    ?>
                        <div class="row">
                            <a class="waves-effect waves-light btn suspendBtn" onclick="Materialize.toast('You suspended this user', 4000);">Suspend this user</a>
                        </div>  
                    <?php
                        }
                    ?>
                </div>
                <div id="settings">
                    <?php if($myprofile) { ?>
                    <div class="row settings">
                        <div class="col s2 verticalTabContainer">
                            <ul class="tabs verticalTabs">
                                <li class="tab"><a href="#modifyAccount">Account</a></li>
                                <li class="tab"><a href="#notifications">Notifications</a></li>
                                <li class="tab"><a href="#deleteAccount">Delete Profile</a></li>
                            </ul>
                        </div>
                        <div class="col s9 verticalTabContent">
                            <div id="modifyAccount">
                                <form class="accountForm" action="" method="post" id="accountForm">  
                                    <div class="row">
                                        <div class="input-field accountInput">
                                            <input value="" id="newUsername" type="text" class="noBottomMargin" maxlength="16" placeholder="Enter your username">
                                            <label class="active" for="newUsername">Display name</label>
                                        </div>
                                    </div>
                                    <div class="row accountInputContainer">
                                        <div class="input-field accountInput">
                                            <input value="" id="newEmail" type="email" class="" placeholder="Enter your email">
                                            <label class="active" for="newEmail">Email</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field accountInput">
                                            <textarea id="newAboutMe" class="materialize-textarea noBottomMargin" maxlength="255" data-length="255" placeholder="Add something about you"></textarea>
                                            <label class="active" for="newAboutMe">About me</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4 noLeftPadding">
                                            <label>Date of Birth</label>
                                            <select class="browser-default" id="birthdateMonth">
                                                <option value="" disabled selected>Choose a month</option>
                                                <option class="month1" value="1">January</option>
                                                <option class="month2" value="2">February</option>
                                                <option class="month3" value="3">March</option>
                                                <option class="month4" value="4">April</option>
                                                <option class="month5" value="5">May</option>
                                                <option class="month6" value="6">June</option>
                                                <option class="month7" value="7">July</option>
                                                <option class="month8" value="8">August</option>
                                                <option class="month9" value="9">September</option>
                                                <option class="month10" value="10">October</option>
                                                <option class="month11" value="11">November</option>
                                                <option class="month12" value="12">December</option>
                                            </select> 
                                        </div>
                                        <div class="col s4">
                                            <label class="noLabel">D</label>
                                            <select class="browser-default" id="birthdateDay" disabled>
                                                <option value="" disabled selected>Choose a day</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="row accountInputContainer">
                                        <div class="input-field accountInput">
                                            <input class="autocomplete noBottomMargin validate" id="newLocation" type="text" placeholder="Enter a location" value="">
                                            <label class="active" for="newLocation">Location</label>
                                        </div>
                                    </div>
                                    <div class="row accountInputContainer noLeftMargin noPadding right-align col s9">
                                        <div class="row">
                                            <div class="col s7 noLeftPadding">
                                                <p class="noMargin noLeftPadding hide settingsErrorMsg"></p>
                                                <ul class="noMargin noLeftPadding settingsErrorMsgList left-align"></ul>
                                            </div>
                                            <div class="col s4 offset-s1">
                                                <div class="preloader-wrapper settingsPreloader hide small active">
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
                                                <a class="waves-effect waves-light btn blue" id="saveAccountSettings">Save</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="notifications" class="col s10">
                                <div class="row noti">
                                    <p class="notiTitle col s4 noMargin">Desktop Notifications</p>
                                    <div class="switch col s4">
                                        <label>
                                            Off
                                        <input type="checkbox">
                                        <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                                <div class="labeledNoti">
                                    <div class="row noMargin">
                                        <p class="notiTitle noLeftPadding col s4 noMargin">Notify me when liked</p>
                                        <div class="col s8 notiOptionsContainer">
                                            <div class="notiOptions">
                                                <label class="white"> 
                                                    <input name="notification" type="radio" checked />
                                                    <span>My Topic</span>
                                                </label>
                                            </div>
                                            <div class="notiOptions">
                                                <label class="white">
                                                    <input name="notification" type="radio" />
                                                    <span>My Post</span>
                                                </label>
                                            </div>
                                            <div class="notiOptions">
                                                <label class="white">
                                                    <input name="notification" type="radio" />
                                                    <span>Everything</span>
                                                </label>
                                            </div>
                                            <div class="notiOptions">
                                                <label class="white">
                                                    <input name="notification" type="radio" />
                                                    <span>Nothing</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inputHelper">When someone liked my topic, my post, both of them or none of them depending of these settings</span>
                                </div>  
                                <div class="row noti">
                                    <p class="notiTitle col s4 noMargin">Weekly Newsletter</p>
                                    <div class="switch col s4">
                                        <label>
                                            Off
                                        <input type="checkbox">
                                        <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                                <div class="noti">
                                    <div class="row noMargin">
                                        <p class="notiTitle noLeftPadding col s4 noMargin">Activity Summary</p>
                                        <div class="switch labeledSwitch col s4">
                                            <label>
                                                Off
                                            <input type="checkbox">
                                            <span class="lever"></span>
                                                On
                                            </label>
                                        </div>
                                    </div>
                                    <span class="inputHelper">When I don't visit here, send me an email summary of popular topics and replies</span>
                                </div> 
                                <div class="right-align col s11 noRightPadding saveNotificationSettingsBtn">
                                    <a class="waves-effect waves-light btn blue right-align" id="saveNotificationSettings" >Save</a>
                                </div> 
                            </div>
                            <div id="deleteAccount">
                                <div class="col s11 deleteDescriptionContainer">
                                    <p class="deleteDescription">
                                        Before confirming that you would like your profile deleted, we'd like to take a moment to explain the implications of deletion:
                                    </p>
                                    <ul class="browser-default">
                                        <li class="first">
                                            Deletion is <b>irreversible</b>, and you will have no way to regain any of your original content, should this deletion be carried out and you change your mind later on.
                                        </li>
                                        <li>
                                            Your questions and answers will remain on the site, but will be disassociated and anonymized and will not indicate your authorship even if you later return to the site.
                                        </li>
                                    </ul>
                                    <div class="deleteAccount">
                                        <input type="checkbox" id="approvedDeletion"/>
                                        <label for="approvedDeletion">I have read the information stated above and understand the implications of having my profile deleted. I wish to proceed with the deletion of my profile.</label>
                                    </div>
                                    <div class="row">
                                        <div class="col s6">
                                            <p class="deleteProfileError noMargin hide"></p>
                                        </div>
                                        <div class="col s4 right-align offset-s2">
                                            <a class="waves-effect waves-light btn blue disabled right-align" id="deleteProfile" >Delete Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
        include("resources/modals/badgeSystem.php");
        } else {
            header("Location: /error");
            exit;
        }
    } else {
        header("Location: /error");
        exit;
    }
?>