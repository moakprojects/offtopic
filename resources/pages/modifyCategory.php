<?php
if(isset($_SESSION["user"]) && isset($_SESSION['user']['isAdmin']) && isset($_SESSION["selectedCategoryID"])) {
    $categoryObj = new Category();
    $selectedCategoryData = $categoryObj -> getCategoryData($_SESSION["selectedCategoryID"]);
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Modify category</h2>
            <div class="modifyCategoryContainer">
                <form class="modifyCategoryForm" action="" method="post" id="modifyCategoryForm">  
                    <div class="row">
                        <div class="input-field modifyCategoryInput">
                            <input id="modifiedCategoryName" type="text" class="noBottomMargin" value="<?php echo $selectedCategoryData["categoryName"]; ?>"> 
                            <label class="active" for="modifiedCategoryName">Category name</label>
                        </div>
                    </div>
                    <div class="row textareaContainer">
                        <div class="input-field modifyCategoryInput">
                            <textarea id="modifiedCategoryDescription" class="materialize-textarea"><?php echo $selectedCategoryData["categoryDescription"]; ?></textarea>
                            <label for="modifiedCategoryDescription">Category description</label>
                        </div>
                    </div>
                    <div class="row fileInputContainer">
                        <div class="fileInputField">
                            <div class="fileInput">
                                <label class="btn-floating btn-large waves-effect waves-light red">
                                    <i class="material-icons">file_upload</i>
                                    <input id="fileInput" class="none" type="file"/>
                                </label>
                            </div>
                            <div id="fileInputTextDiv">
                                <input class="fileInputText" placeholder="<?php echo $selectedCategoryData["thumbnail"]; ?>" type="text" disabled readonly id="fileInputText" />
                                <label for="fileInputText"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row inputContainer createmodifyCategory">
                        <div class="col s7">
                            <p id="errorMsg"></p>
                        </div>
                        <div class="col s1 offset-s2">
                            <div class="preloader-wrapper small active hide modifyCategorySpinner">
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
                        <a class="btn waves-effect waves-light blue col s2" id="modifyCategorySubmit" onclick="submitModifiedCategoryData(<?php echo $selectedCategoryData['categoryID'] . ", '" . $selectedCategoryData['thumbnail']; ?>')">Save</a>
                    </div>  
                </form>
            </div>
        </div>
        <div class="col s4">
            <div class="helperSideBarContainer">
                <h4><i class="far fa-lightbulb"></i> Checklist</h4>
                <div class="line"></div>
                <div class="helperContent">
                    <p><strong>Before you modify a category, please check that its meaning does not change. </strong></p>
                    <p>If you change tha thumbnail, please upload a well-described image for the category</p>
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