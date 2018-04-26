<?php
    if(isset($_SESSION["selectedUsername"])) {
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
                    <?php echo (!$favouriteCategories && !$favouriteTopics && !$likedPosts ? "" : "<li class='tab'><a href='#favourites'>Favourites</a></li>");  ?>
                    <li class="tab"><a href="#ownThings" onclick="getOwnData('<?php echo $selectedUsername; ?>')">Own things</a></li>
                    <li class="tab"><a href="#general">General informations</a></li>
                    <li class="tab"><a href="#settings">Settings</a></li>
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
                                                <a href="/categories/<?php echo $userCreation["categoryID"]; ?>"><div class="chip ownTopicCategory topicCategory"><?php echo $userCreation["categoryName"]; ?></div></a>
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
                                                <p class="viewPost right-align"><a href="/posts/<?php echo $post["postID"]; ?>">View the post <i class="fas fa-angle-double-right"></i></a></p>
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
                                        uuuuu
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="far fa-envelope"></i></div>
                                        <div class="col s10 personalInformationLabel">Email:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        uuuuu@uuuuu.hu
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="far fa-calendar"></i></div>
                                        <div class="col s10 personalInformationLabel">Birthdate:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        15-01-1999 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="fas fa-globe"></i></div>
                                        <div class="col s10 personalInformationLabel">Country/Region:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        Denmark
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="fas fa-circle-notch"></i></div>
                                        <div class="col s10 personalInformationLabel">Rank level:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        1
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="fas fa-history"></i></div>
                                        <div class="col s10 personalInformationLabel">Member for:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        1 year, 7 month
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                        <div class="col s1 personalInformationIcon"><i class="far fa-clock"></i></div>
                                        <div class="col s10 personalInformationLabel">Last seen:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        5 minutes ago
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s5">
                                    <div class="col s1 personalInformationIcon"><i class="far fa-smile"></i></div>
                                        <div class="col s10 personalInformationLabel">About me:</div>
                                    </div>
                                    <div class="col s7 right-align">
                                        What about us?
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col s6">
                            <p class="profileTitle">Badges</p>
                            <div class="row badgeContainer">
                                <div class="col s5 badge center-align"><i class="fas fa-circle dot"></i>Student</div>
                                <div class="col s5 badge center-align"><i class="fas fa-circle dot"></i>Teacher</div>
                                <div class="col s5 badge center-align"><i class="fas fa-circle dot"></i>Autobiographer</div>
                            </div>
                            <p class="nextBadgeTitle">Next badge:</p>
                            <div class="row badgeContainer">
                                <div class="col s5 badge nextBadge center-align tooltipped" data-position="bottom" data-tooltip="Read the entire homepage"><i class="fas fa-circle dot"></i>Informed</div> <i class="fas fa-bars"></i>
                            </div>
                        </div>
                    </div>
                                
                </div>
                <div id="settings">
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
                                            <input value="uuuuu" id="newusername" type="text" class="validate noBottomMargin">
                                            <label class="active" for="newusername">Display name</label>
                                            <span class="inputHelper">People can mention you as @uuuuu</span>
                                        </div>
                                    </div>
                                    <div class="row accountInputContainer">
                                        <div class="input-field accountInput">
                                            <input value="uuuuu@uuuuu.hu" id="newemail" type="email" class="validate noBottomMargin">
                                            <label class="active" for="newemail">Email</label>
                                        </div>
                                    </div>
                                    <div class="row accountInputContainer">
                                        <div class="input-field accountInput">
                                            <textarea id="newaboutme" class="materialize-textarea noBottomMargin">What about us?</textarea>
                                            <label class="active" for="newaboutme">About me</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4 noLeftPadding">
                                            <label>Date of Birth</label>
                                            <select class="browser-default">
                                                <option value="" disabled selected>Choose a day</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                            </select> 
                                        </div>
                                        <div class="col s4">
                                            <label class="noLabel">D</label>
                                            <select class="browser-default">
                                                <option value="" disabled selected>Choose a month</option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="row accountInputContainer">
                                        <div class="input-field accountInput">
                                            <input value="Budapest, Magyarország" id="newlocation" type="text" class="validate noBottomMargin">
                                            <label class="active" for="newlocation">Location</label>
                                        </div>
                                    </div>
                                    <div class="row accountInputContainer">
                                        <a class="waves-effect waves-light btn blue">Save</a>
                                    </div>
                                </form>
                            </div>
                            <div id="notifications">Tab 4</div>
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
                                        <input type="checkbox"  id="approvedDeletion" />
                                        <label for="approvedDeletion">I have read the information stated above and understand the implications of having my profile deleted. I wish to proceed with the deletion of my profile.</label>
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
<?php
    } else {
        header("Location: /error");
        exit;
    }
?>