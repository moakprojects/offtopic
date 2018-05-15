<?php
    $generalObj = new General();
    $descriptionOfTheSite = $generalObj -> getDescriptionOfTheSite();
?>
    <div class="container contentContainer">
        <div class="row noBottomMargin aboutUsTopWrapper">
            <div class="col s5 offset-s1"> 
                <h2>What is Off-topic?</h2>
                <p><?php echo $descriptionOfTheSite[0]["aboutUs"]; ?></p>
            </div>            
        </div>
        <div class="row noBottomMargin meetTheTeamWrapper">
            <div class="col s12 center-align"> 
                <h2>Meet the Team</h2>
                <div class="row imageContainer">
                    <div class="col s4 offset-s2">
                        <img src="/public/images/content/erika.png" alt="Erika developer">
                    </div>
                    <div class="col s4">
                        <img src="/public/images/content/akos.jpg" alt="Akos developer">
                    </div>
                </div>
                <div class="row">
                    <div class="col s4 offset-s2">
                        <p class="teamMember noMargin">Erika</p>
                    </div>
                    <div class="col s4">
                        <p class="teamMember noMargin">Akos</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col s4 offset-s2">
                        <p class="teamMemberTitle noMargin">Co-founder / Developer</p>
                    </div>
                    <div class="col s4">
                        <p class="teamMemberTitle noMargin">Co-founder / Developer</p>
                    </div>
                </div>
            </div>            
        </div>
    </div>  