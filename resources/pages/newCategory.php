<?php
if(isset($_SESSION["user"]) && isset($_SESSION['user']['isAdmin'])) {
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Create a new category</h2>
            <div class="newCategoryContainer">
                <form class="newCategoryForm" action="" method="post" id="newCategoryForm">  
                    <div class="row">
                        <div class="input-field newCategoryInput">
                            <input id="newCategoryName" type="text" class="noBottomMargin">
                            <label class="active" for="newCategoryName">Category name</label>
                        </div>
                    </div>
                    <div class="row textareaContainer">
                        <div class="input-field newCategoryInput">
                            <textarea id="newCategoryDescription" class="materialize-textarea"></textarea>
                            <label for="newCategoryDescription">Category description</label>
                        </div>
                    </div>
                    <div class="row fileInputContainer">
                        <div class="fileInputField">
                            <div class="fileInput">
                                <label class="btn-floating btn-large waves-effect waves-light red">
                                    <i class="material-icons">file_upload</i>
                                    <input id="fileInput" class="none" type="file" />
                                </label>
                            </div>
                            <div id="fileInputTextDiv">
                                <input class="fileInputText" placeholder="Category thumbnail" type="text" disabled readonly id="fileInputText" />
                                <label for="fileInputText"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row inputContainer createNewCategory">
                        <div class="col s7">
                            <p id="errorMsg"></p>
                        </div>
                        <div class="col s1 offset-s2">
                            <div class="preloader-wrapper small active hide newCategorySpinner">
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
                        <a class="btn waves-effect waves-light blue col s2" id="newCategorySubmit">Create</a>
                    </div>  
                </form>
            </div>
        </div>
        <div class="col s4">
            <div class="helperSideBarContainer">
                <h4><i class="far fa-lightbulb"></i> Checklist</h4>
                <div class="line"></div>
                <div class="helperContent">
                    <p><strong>Before you create a category, please check that it is sure that it does not exist already any similar. </strong></p>
                    <p>Please upload a well-described thumbnail picture for the category</p>
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