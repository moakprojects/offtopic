<?php
    $generalObj = new General();
    $contactInformation = $generalObj -> getContactInformation();
?>
    <div class="container contentContainer">
        <div class="row">
            <div class="col s7 offset-s1"> 
                <h2>Contact</h2>
                <p class="generalTxt"><?php echo $contactInformation[0]["generalText"]; ?></p>
            </div>             
            <div class="col s3 contactBoxContainer"> 
                <h3>Our office</h3>
                <div class="row contactBox">
                    <div class="col s2 offset-s1 noRightPadding valign-wrapper">
                        <i class="far fa-building fa-3x"></i>
                    </div>
                    <div class="col s8 noMargin officeInfo">
                        <p>Location: <?php echo $contactInformation[0]["location"]; ?></p>
                        <p>Phone: +<?php echo $contactInformation[0]["phoneNumber"]; ?></p>
                    </div>
                </div>
            </div> 
        </div>
        <div class="row contactFormContainer">
            <div class="col s8 offset-s2  contactForm">
                <form class="contactForm" action="" method="post" id="contactForm">  
                    <div class="row">
                        <div class="input-field contactInput">
                            <input id="email" type="text" class="noBottomMargin">
                            <label class="active" for="email">Your name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field contactInput">
                            <input id="email" type="text" class="noBottomMargin">
                            <label class="active" for="email">Your email</label>
                        </div>
                    </div>
                    <div class="row textareaContainer">
                        <div class="input-field contactInput">
                            <textarea id="problemDescription" class="materialize-textarea"></textarea>
                            <label for="problemDescription">Problem Description</label>
                        </div>
                    </div>
                    <div class="row inputContainer">
                        <div class="col s6">
                            <p id="errorMsg"></p>
                        </div>
                        <div class="col s1 offset-s3">
                            <div class="preloader-wrapper small active hide contactSpinner">
                                <div class="spinner-layer spinner-blue-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <a class="btn waves-effect waves-light blue col s2" id="contactSubmit">Send</a>
                    </div>  
                </form>
            </div>
        </div>
    </div>