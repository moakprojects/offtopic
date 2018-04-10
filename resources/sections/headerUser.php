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
            <li><a href="/profile">My profile</a></li>
            <li class="divider"></li>
            <li><a id="logOutBtn">Logout</a></li>
        </ul>
        <nav>
            <div class="">
                <ul class="right hide-on-med-end-down">
                    <li class="input-field">
                        <i class="material-icons prefix searchIcon">search</i>
                        <input id="searchField" type="text" class="validate" Placeholder="Search...">
                    </li>
                    <li><a class="waves-effect waves-light btn btnNewTopic blue">Start New Topic</a></li>
                    <li>
                    <a class="dropdown-button profileDropdown" href="#!" data-activates="dropdown1">
                        <?php

                            $userObj = new User();
                            $loggedUser = $userObj->loggedUser($_SESSION["user"]["userID"]);

                            echo "<img src='"; 
                            echo ($loggedUser['profileImage'] === 'defaultAvatar.png' ?'/public/images/content/defaultAvatar.png' : '/public/images/upload/' . $loggedUser["profileImage"]);
                            echo "' class='newAvatarImg' alt='profile picture'>";
                        ?>
                        <i class="material-icons right">arrow_drop_down</i>
                    </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>