<div id="headerImage">
    <div></div>
</div>
<header>
    <div class="sideSpace">
        <a href="/home"><img src="public/images/content/offtopicLogo.png" alt="Offtopic forum's logo" class="logo"></a>
        <div class="separator"></div>
        <div class="titleContainer">
            <p>OffTopic</p>
        </div>
        <nav class="generalNav">
            <ul class="right hide-on-med-end-down">
                <li class="input-field">
                    <i class="material-icons prefix searchIcon">search</i>
                    <input id="searchField" type="text" class="validate" Placeholder="Search...">
                </li>
                <li class="logInTitle">
                    <a href="#login" class="modal-trigger">Log In</a>
                </li>
                <li class="signUpTitle">
                    <a href="#signup" class="modal-trigger">Sign Up</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php
        include("resources/modals/login.php");
        include("resources/modals/registration.php");
    ?>
</header>