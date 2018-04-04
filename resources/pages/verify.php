<?php
    if(isset($_SESSION["verifyCode"])) {
        include "database/selection.php";

        $verifyEmailHash = htmlspecialchars(trim($_SESSION["verifyCode"]));
        if(isset($verifyEmailQuery)) {
            $verifyEmailQuery->bindParam(':emailHash', $verifyEmailHash);
            $verifyEmailQuery->execute();

            if($verifyEmailQuery->rowCount() > 0) {
                $verifyEmailResult = $verifyEmailQuery->fetch(PDO::FETCH_ASSOC);

                if($verifyEmailResult["accessLevel"] == 0) {

                    $regDate = new DateTime($verifyEmailResult["regDate"]);
                    $now = new DateTime('now');
                    $diff = $regDate -> diff($now);

                    if($diff -> d < 1) {
                        include "database/update.php";

                        if($modifyAccessLevelQuery) {
                            $modifyAccessLevelQuery->bindParam(':email', $verifyEmailResult["email"]);
                            $modifyAccessLevelQuery->execute();
                        } else {
                            header("Location: /error");
                            exit;
                        }
                    } else {
                        $verificationFailedMsg = "Your confirmation link has expired. Please <a href='#signup' class='modal-trigger'>re-register</a> your email.";
                    }
                } else {
                    $verificationFailedMsg = "You have already verified your email address! Please <a href='#login' class='modal-trigger'>login</a> to your account.";    
                }
            } else {
                $verificationFailedMsg = "You haven't registered yet!";
            }
        } else {
            header("Location: /error");
            exit;
        }

        unset($_SESSION["verifyCode"]);

        if(isset($verificationFailedMsg)) {
            ?>
            <div class="verificationFailed">
                <h2 class="center-align">Verification failed</h2>
                <p class="center-align"><?php echo $verificationFailedMsg; ?></p>
            </div>
            <?php
        } else {
            ?>
                <div class="verificationSuccess">
                    <h2 class="center-align">Verification was successfully</h2>
                    <p class="center-align">Please log in to your account</p>
                    <div class="row">
                        <div class="loginContainer offset-s4 col s4">
                            <h4>Login Account</h4>
                            <div class="line"></div>
                            <form action="" method="post" class="loginForm" id="verifyLogForm">
                                <div class="row">
                                    <input type="text" name="userName" Placeholder="Email or username" class="col s10 offset-s1 loginID">    
                                </div>
                                <div class="row">
                                    <input type="password" name="password" Placeholder="Password" class="col s10 offset-s1 password">    
                                </div>
                                <div class="row">
                                    <div class="col s6  offset-s1 rememberMeContainer">
                                        <input type="checkbox" name="rememberMe" id="rememberMe" class="filled-in"><label for="rememberMe">Remember me</label>    
                                    </div>
                                </div>
                                <div class="row verifyErrorMsg hide">
                                    <p class="col s10 offset-s1"></p>
                                </div>
                                <div class="row">
                                    <input type="button" value="Login" id="verifyLogBtn" class="btn wavew-effect waves-light blue col s4 offset-s7">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
        }
    } else {
        header("Location: /error");
        exit;
    }
?>