<div class="sideBarBlock <?php echo (isset($_SESSION["user"]) && $_SESSION["user"]["loggedIn"] ? 'hide' : ""); ?>">
    <h4>Login Account</h4>
    <div class="line"></div>
    <form action="" method="post" class="sideBarLoginContainer" id="sideBarLogForm">
        <div class="row">
            <input type="text" name="userName" Placeholder="Email or username" class="loginID col s8 offset-s1">    
        </div>
        <div class="row">
            <input type="password" name="password" Placeholder="Password" id="sideBarPassword" class="password col s8 offset-s1">    
        </div>
        <div class="row">
            <div class="col s6  offset-s1 rememberMeContainer">
                <input type="checkbox" name="rememberMe" id="rememberMe" class="filled-in"><label for="rememberMe">Remember me</label>    
            </div>
        </div>
        <div class="row sideBarErrorMsg hide">
            <p class="col s10 offset-s1"></p>
        </div>
        <div class="row">
            <input type="button" value="Login" id="sideBarLogBtn" class="btn wavew-effect waves-light blue col s4 offset-s7">
        </div>
    </form>
</div>