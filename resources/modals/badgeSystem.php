<div id="badgeSystem" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="row">
                <h4 class="col s10">Badge System</h4>
                <button type="button" class="btn-flat modal-action modal-close col s2"><i class="material-icons">clear</i></button>
            </div>
        </div>
        <div class="modal-body">
            <div class="row noMargin">
                <?php

                //display the list of badge
                $badgeSystem = $userObj->getBadgeSystem();
                if($badgeSystem) {
                    
                    foreach($badgeSystem as $badge) {
                        echo '
                        <div class="col s6 badgeSystemDetails">
                            <div class="row noMargin">
                                <div class="badge center-align col s12">
                                    <i class="fas fa-circle dot"></i>' . $badge["badgeName"] . '
                                </div>
                            </div>
                            <div class="row noMargin">
                                <div class="col s12 noPadding">
                                    <p class="noTopMargin">' . $badge["badgeDescription"] . '</p>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                } else {
                    header("Location: /error");
                    exit;
                }

                ?>                
            </div>
        </div>
    </div>
</div>