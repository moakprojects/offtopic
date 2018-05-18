<?php
if(isset($_SESSION["user"]) && isset($_SESSION['user']['isAdmin']) && isset($_SESSION["modifySidebarStickyID"])) {
    $postObj = new Post();
    $selectedSidebarStickyData = $postObj -> getSidebarSticky($_SESSION["modifySidebarStickyID"]);
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Modify a sidebar sticky post</h2>
            <div class="modifyStickyContainer">
                <form class="modifyStickyForm" action="" method="post" id="modifyStickyForm">  
                    <div class="row">
                        <div class="input-field modifyStickyInput">
                            <input id="modifiedStickyName" type="text" class="noBottomMargin" value="<?php echo $selectedSidebarStickyData["stickyPostTitle"]; ?>">
                            <label class="active" for="modifiedStickyName">Sticky title</label>
                        </div>
                    </div>
                    <div class="row textareaContainer">
                        <div class="input-field modifyStickyInput">
                            <textarea id="modifiedStickyDescription" class="materialize-textarea"><?php echo $selectedSidebarStickyData["stickyPostText"]; ?></textarea>
                            <label for="modifiedStickyDescription">Sticky Description</label>
                        </div>
                    </div>
                    <div class="row inputContainer createmodifySticky">
                        <div class="col s6">
                            <p id="errorMsg"></p>
                        </div>
                        <div class="col s1 offset-s3">
                            <div class="preloader-wrapper small active hide modifyStickySpinner">
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
                        <a class="btn waves-effect waves-light blue col s2" id="modifyStickySubmit" onclick="submitModifiedCategoryData(<?php echo $selectedSidebarStickyData["stickyPostID"]; ?>)">Save</a>
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