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
            <div class="image">
                <a href="#avatarChange" class="modal-trigger">
                    <img src="public/images/content/profile.png" alt="profile picture">
                    <div class="fadeContainer">
                        <i class="far fa-images fa-3x"></i>
                        <p class="textCenter">Change picture</p>
                    </div>
                </a>
            </div>
            <p class="userName">
                Test User
            </p>
        </div>
        <div class="row countContainer">
            <div class="col s5">
                <div class="row">
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="fas fa-circle-notch fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="3" data-speed="1500"></p>
                        <p class="textCenter countTitle">Badges</p>
                    </div>
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="far fa-comments fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="3650" data-speed="1500"></p>
                        <p class="textCenter countTitle">Topics</p>
                    </div>
                </div>
            </div>
            <div class="col s5 offset-s2">
                <div class="row">
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="far fa-comment-alt fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="365" data-speed="1500"></p>
                        <p class="textCenter countTitle">Posts</p>
                    </div>
                    <div class="col s6">
                        <p class="textCenter countIcon"><i class="far fa-thumbs-up fa-2x"></i></p>
                        <p class="textCenter timer countValue count-number" data-to="3650" data-speed="1500"></p>
                        <p class="textCenter countTitle">Likes</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                  
        </div>
    </div>
</div>
<?php
    include("resources/modals/avatarChange.php");
?>