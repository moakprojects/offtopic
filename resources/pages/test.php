<div class="formContainer">
    <div class="file-field">
        <div class="btn blue waves-effect waves-light">
            <span>Select File</span>
            <input type="file" name="avatar" id="avatarImg">
        </div>
    </div>
</div>
<p id="errorMsg">
    <?php
        echo (isset($_SESSION["avatarChangeError"]) ? $_SESSION["avatarChangeError"] : "semmi");   
        //echo (isset($_COOKIE["avatarChangeError"]) ? $_COOKIE["avatarChangeError"] : "semmi");   
    ?>
</p>