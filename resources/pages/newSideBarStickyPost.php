<?php
if(isset($_SESSION["user"]) && isset($_SESSION['user']['isAdmin'])) {
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Create a new sidebar sticky post</h2>
            <div class="newStickyContainer">
                <form class="newStickyForm" action="" method="post" id="newStickyForm">  
                    <div class="row">
                        <div class="input-field newStickyInput">
                            <input id="newStickyName" type="text" class="noBottomMargin">
                            <label class="active" for="newStickyName">Sticky title</label>
                        </div>
                    </div>
                    <div class="row textareaContainer">
                        <div class="input-field newStickyInput">
                            <textarea id="newStickyDescription" class="materialize-textarea"></textarea>
                            <label for="newStickyDescription">Sticky Description</label>
                        </div>
                    </div>
                    <div class="row inputContainer createNewSticky">
                        <div class="col s6">
                            <p id="errorMsg"></p>
                        </div>
                        <div class="col s1 offset-s3">
                            <div class="preloader-wrapper small active hide newStickySpinner">
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
                        <a class="btn waves-effect waves-light blue col s2" id="newStickySubmit">Create</a>
                    </div>  
                </form>
            </div>
        </div>
        <div class="col s4">
            <div class="helperSideBarContainer">
                <h4><i class="far fa-lightbulb"></i> What is it about?</h4>
                <div class="line"></div>
                <div class="helperContent">
                    <p>You can write anything here that you want to announce, as this fixed post will be pinned in the sidebar to be visible to everyone. </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    } else {
        header("Location: /error");
        exit;
    }
?>