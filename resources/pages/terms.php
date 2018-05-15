<?php
    $generalObj = new General();
    $rulesAndRegulations = $generalObj -> getRulesAndRegulationsData();
?>
    <div class="container contentContainer">
        <div class="row noBottomMargin">
            <div class="col s12"> 
                <h2>Rules and regulations</h2>
            </div>            
        </div>
        <div class="row noBottomMargin">
            <p class="col s10 offset-s1 generalTxt"><?php echo $rulesAndRegulations[0]["generalTxt"]; ?></p>
        </div>
        <div class="row titleSection">
            <h3>Acceptance of Terms</h3>
        </div>
        <div class="row noBottomMargin ruleTxt">
            <p><?php echo $rulesAndRegulations[0]["acceptanceOfTerms"]; ?></p>
        </div>
        <div class="row titleSection">
            <h3>Modification of Terms of Use</h3>
        </div>
        <div class="row noBottomMargin ruleTxt">
            <p><?php echo $rulesAndRegulations[0]["modificationOfTerms"]; ?></p>
        </div>
        <div class="row titleSection">
            <h3>Rules and Conduct</h3>
        </div>
        <div class="row noBottomMargin ruleTxt">
            <p><?php echo $rulesAndRegulations[0]["rulesAndConduct"]; ?></p>
        </div>
        <div class="row titleSection">
            <h3>Termination</h3>
        </div>
        <div class="row noBottomMargin ruleTxt">
            <p><?php echo $rulesAndRegulations[0]["termination"]; ?></p>
        </div>
        <div class="row titleSection">
            <h3>Integration</h3>
        </div>
        <div class="row noBottomMargin ruleTxt">
            <p><?php echo $rulesAndRegulations[0]["integration"]; ?></p>
        </div>
        <div class="row titleSection">
            <h3>Contact</h3>
        </div>
        <div class="row noBottomMargin ruleTxt">
            <p>If you have any question about it, please <a href="/contact">contact us</a>.</p>
        </div>
    </div>