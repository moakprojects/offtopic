<?php
if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"])) {
    $userObj = new User();
    $loggedUser = $userObj->loggedUser($_SESSION["user"]["userID"]);
?>
<div id="headerImage">
    <div></div>
</div>
<header>
    <div class="sideSpace">
        <a href="/home"><img src="/public/images/content/offtopicLogo.png" alt="Offtopic forum's logo" class="logo"></a>
        <div class="separator"></div>
        <div class="titleContainer">
            <p>OffTopic</p>
        </div>
        <ul id="dropdown1" class="dropdown-content dropdownMenu">
            <li><a id="logOutBtn">Logout</a></li>
        </ul>
        <nav>
            <div class="">
                <ul class="right hide-on-med-end-down">
                    <li class="input-field">
                        <i class="material-icons prefix searchIcon">search</i>
                        <input id="searchField" type="text" class="validate" Placeholder="Search...">
                    </li>
                    <li>
                    <a class="dropdown-button profileDropdown" href="#!" data-activates="dropdown1">
                        <?php

                            echo "<img src='/public/images/content/admin.png' class='newAvatarImg' alt='profile picture'>";
                        ?>
                        <i class="material-icons right">arrow_drop_down</i>
                    </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<?php
    } else {
        header("Location: /error");
        exit;
    }
?>