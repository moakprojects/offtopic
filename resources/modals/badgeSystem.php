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
                $badgeSystem = $userObj->getBadgeSystem($selectedUsername);
                if($badgeSystem) {
                    
                    foreach($badgeSystem as $badge) {
                ?>      
                    <div class="col s6 badgeSystemDetails">
                        <div class="row noMargin">
                            <div class="badge <?php echo ($badge["earned"] ? "ownBadge" : "") ?> center-align col s12">
                                <i class="fas fa-circle dot"></i> <?php echo $badge["badgeName"] ?>
                            </div>
                        </div>
                        <div class="row noMargin">
                            <div class="col s12 noPadding">
                                <p class="noTopMargin middleElement"><?php echo $badge["badgeDescription"] ?></p>
                            </div>
                        </div>
                        <?php if($badge["earned"]) {
                        ?>
                        <div class="row noMargin">
                            <div class="col s12 noPadding right-align">
                                <span class="earnedLabel"><em>- earned -</em></span>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                <?php
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