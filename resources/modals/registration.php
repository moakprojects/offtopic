<div id="signup" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="row">
                <h4 class="col s10">Create New Account</h4>
                <button type="button" class="btn-flat modal-action modal-close col s2"><i class="material-icons">clear</i></button>
            </div>
        </div>
        <div class="modal-body">
            <div class="socialButtons">
                <a href="" class="btn btnFacebook">
                    <div class="row">
                        <div class="col s2">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <div class="col s10 socialTitle">Signup with Facebook</div>
                    </div>
                </a>
                <a href="" class="btn btnTwitter">
                    <div class="row">
                        <div class="col s2">
                            <i class="fab fa-twitter"></i>
                        </div>
                        <div class="col s10 socialTitle">Signup with Twitter</div>
                    </div>
                </a>
                <a href="" class="btn btnGoogle">
                    <div class="row">
                        <div class="col s2">
                            <i class="fab fa-google-plus-g"></i>
                        </div>
                        <div class="col s10 socialTitle">Signup with Google+</div>
                    </div>
                </a>
            </div>
            <div class="row separateContainer">
                <div class="col s5 separateLine"></div>
                <div class="col s2"><p>OR</p></div>
                <div class="col s5 separateLine"></div>
            </div>
            <div class="loginForm">
                <form method="post" id="regForm">
                    <input type="text" name="emailAddress" class="email" Placeholder="Email">   
                    <input type="text" name="userName" class="username" Placeholder="Username">   
                    <input type="password" name="password" class="password" Placeholder="Password">    
                    <input type="password" name="passwordConfirm" class="passwordConfirm" Placeholder="Re-Password">    
                    <br>
                    <div class="row modalErrorMsg hide">
                        <p></p>
                    </div>
                    <div class="row">
                        <input type="button" id="regBtn" value="Create" class="btn wavew-effect waves-light blue col s4 offset-s8">
                    </div>
                </form>
            </div>
            <p class="registerLink">
                Have an account? <a href="#login" class="modal-trigger modal-close">Login</a>
            </p>
        </div>
    </div>
</div>