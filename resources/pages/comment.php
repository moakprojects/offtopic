<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8">
            <div class="titleSection">
                <div class="breadCrumb">
                    <a href="/home">Home</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/categories">Categories</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/topic">Topics</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/discussion">Discussion</a>
                    <i class="material-icons">chevron_right</i>
                    <a href="/discussion">Post</a>
                </div>
            </div>
            <div class="topic container noBottomMargin">
                <div class="row">
                    <div class="col s1 userImageContainer">
                        <?php
                            echo "<a href='/profile/Hans'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Hans'></a>";
                        ?>
                    </div>
                    <div class="col s11 topicContainer">
                        <h3>Backend assignment</h3>
                        <p class="topicDescription">Ez a tartalom tök hosszúúúúúúúúúúúúúúúúúúúúúúúúúúúúúúúúú</p>
                    </div>
                </div>
                <div class="row topicDetails">
                    <div class="col s11 offset-s1">
                        <div class="row">
                            <div class="created col s5">
                                <i class="far fa-clock fa-sm"></i>
                                <span>Created at: 2018-04-28 by <a class="topicCreator" href="#">Hans </a></span>
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
                            echo "<a href='/profile/Hans'><img src='/public/images/content/defaultAvatar.png' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Hans'></a>";
                        ?>
                    </div>
                    <div class="col s11 topicContainer">
                        <div class="row postedOnContainer">
                            <div class="col s4 postedBy">
                                <a href="#"> Hans </a>
                            </div>
                        </div>
                            <div class="replyContent">
                                <div class="row postedOnContainer">
                                    <div class="col s12 postedBy">
                                        <span>Original Posted by - </span>
                                        <a href="#">Johnny:</a>
                                    </div>
                                </div>
                                <p class="topicDescription">Blabla</p>
                                <div class="row postIndexContainer">
                                    <div class="col s12 postIndex">
                                        <a href="#">#1</a>
                                    </div>
                                </div>
                            </div>      
                        <p class="topicDescription">Neked is blabla</p>
                        <ul class="postAttachFiles">
                            <?php 
                                echo '<li><a href="#" download="" target="_blank" type="applicatiob/octet-stream">Attach file</a></li>';
                            ?>
                        </ul>
                        <div class="row postIndexContainer">
                            <div class="col s12 postIndex">
                                <a href="#">#2</a>
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
                                    <span>Posted on: 2018-04-28 17:00:11</span>
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
        <div class="col s4">
            <?php 
                include "resources/sections/sideBarLoginBlock.php"; 
                include "resources/sections/sideBarCategoryListBlock.php"; 
            ?>
        </div>
    </div>
</div>