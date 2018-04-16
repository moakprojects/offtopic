<?php
    if(isset($_SESSION["selectedUsername"])) {
        $userObj = new User();
        $selectedUsername = htmlspecialchars(trim($_SESSION["selectedUsername"]));
        $selectedUserData = $userObj -> getSelectedUser($selectedUsername);
        if(isset($loggedUser) && $loggedUser["username"] === $selectedUsername) {
            $myprofile = true;
            include("resources/modals/avatarChange.php");
        } else {
            $myprofile = false;
            $userObj->increaseNumberOfVisitors($_SESSION["selectedUsername"]);
        }
        $visitors = $userObj->getNumberOfVisitors($_SESSION["selectedUsername"]);
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
                    <li class="tab"><a href="#fav">Favourites</a></li>
                    <li class="tab"><a href="#fav">My own things</a></li>
                    <li class="tab"><a href="#stat">General informations</a></li>
                    <li class="tab"><a href="#set">Settings</a></li>
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
                                                            <p class="noBottomMargin reputationValue reputationLikePosts center-align"><?php echo (isset($selectedUserData["numberOfPosts"]) && isset($selectedUserData["numberOfPostLikes"]) ? Round($selectedUserData["numberOfPostLikes"] / $selectedUserData["numberOfPosts"]) : "0"); ?></p>
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
                                                            <p class="noBottomMargin reputationValue reputationTopicPopularity center-align"><?php echo (isset($selectedUserData["numberOfFollowers"]) && isset($selectedUserData["numberOfTopics"]) ? $selectedUserData["numberOfFollowers"] / $selectedUserData["numberOfTopics"] : "0"); ?></p>
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
                                                            <p class="noBottomMargin reputationValue reputationProfileVisitor center-align"><?php echo $visitors["visitors"]; ?></p>
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
                <div id="stat"><h1>itt lennének a statok</h1></div>
                <div id="fav"><h1>itt lennének a favoritok</h1></div>
                <div id="set"><h1>itt lennének a settingek</h1></div>
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