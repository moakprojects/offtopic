<?php
if(isset($_SESSION["user"]) && isset($_SESSION['user']['isAdmin']) && isset($_SESSION["modifyPostID"])) {
    $postObj = new Post();
    $selectedPost = $postObj->getSelectedPostBasicData($_SESSION["modifyPostID"]);
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Modify a post</h2>
            <div class="modifyPostContainer">
                <form class="modifyPostForm" action="" method="Post" id="modifyPostForm">  
                    <div class="row textareaContainer">
                        <div class="input-field modifyPostInput">
                            <p class="desciptrionLabel">Post Text</p>
                            <div class="editorContainer" id="modifiedPostDescription">
                                <div id="editor"><?php echo $selectedPost["text"]; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row inputContainer createmodifyPost">
                        <div class="col s6">
                            <p id="errorMsg"></p>
                            <hr id="errorMsgSeparator" class="hide">
                            <ul id="attachFiles"></ul>
                        </div>
                        <div class="col s1 offset-s3">
                            <div class="preloader-wrapper small active hide modifyPostSpinner">
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
                        <a class="btn waves-effect waves-light blue col s2" id="modifyPostSubmit" onclick="submitModifiedPostData(<?php echo $selectedPost["postID"]; ?>)">Save</a>
                    </div>  
                </form>
            </div>
        </div>
        <div class="col s4">
            <div class="helperSideBarContainer">
                <h4><i class="far fa-lightbulb"></i> How to modify</h4>
                <div class="line"></div>
                <div class="helperContent">
                    <p>Please keep in mind that only the not right words are deleted, the context of the post should be meaningful. </p>
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