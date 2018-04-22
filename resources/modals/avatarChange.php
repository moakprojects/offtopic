<div id="avatarChange" class="modal">
    <div class="row tmpAvatarTitle ">
        <p class="col s4 offset-s7">Change your avatar here<br><span class="tmpAvatarHint">Click to select</span></p>
    </div>
    <div class="row modalContent">
        <div class="col s5 fileUpload textCenter">
            <div class="tmpAvatarImage">
                <?php

                //display current avatar image
                echo "<img src='"; 
                echo ($loggedUser['profileImage'] === 'defaultAvatar.png' ?'/public/images/content/defaultAvatar.png' : '/public/images/upload/' . $loggedUser["profileImage"]);
                echo "' class='newAvatarImg' alt='profile picture'>";
                
                ?>
            </div>
        </div>
        <div class="col s7 builtIn">
            <div class="carousel">
                <?php

                //display default avatars
                $defaultAvatarImages = $userObj->getDefaultAvatars();
                if($defaultAvatarImages) {
                    
                    foreach($defaultAvatarImages as $image) {
                        echo "<a class='carousel-item' id='" . $image['fileName'] . "'><img src='/public/images/defaultAvatars/" . $image['fileName'] . "'></a>";
                    }
                } else {
                    header("Location: /error");
                    exit;
                }
        
                ?>
            </div>
            <div class="row sepContainer">
                <div class="col s3 offset-s2 sepLine"></div>
                <div class="col s2 sepOrContainer"><p class="textCenter">OR</p></div>
                <div class="col s3 sepLine"></div>
                <div class="col s2"></div>
            </div>
            <div class="formContainer">
                <div class="file-field textCenter">
                    <div class="btn blue waves-effect waves-light">
                        <span>Select File</span>
                        <input type="file" name="avatar" id="avatarImg">
                    </div>
                    <br>
                    <div class="preloader-wrapper small hide active">
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
                    <p id="errorMsg" class="hide"></p>
                </div>
            </div>
        </div>
    </div>
</div>