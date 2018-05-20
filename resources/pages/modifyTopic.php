<?php
if(isset($_SESSION["user"]) && isset($_SESSION['user']['isAdmin']) && isset($_SESSION["modifyTopicID"])) {
    $categoryObj = new Category();
    $topicObj = new Topic();
    $categories = $categoryObj->getAllCategoryData();
    $periods = $topicObj->getPeriodInfo();
    $selectedTopic = $topicObj->getSelectedTopicBasicData($_SESSION["modifyTopicID"]);
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Modify a topic</h2>
            <div class="modifyTopicContainer">
                <form class="modifyTopicForm" action="" method="post" id="modifyTopicForm">  
                    <div class="row">
                        <div class="input-field modifyTopicInput">
                            <input id="modifiedTopicName" type="text" class="noBottomMargin" value="<?php echo $selectedTopic["topicName"]; ?>">
                            <label class="active" for="modifiedTopicName">Topic name</label>
                        </div>
                    </div>
                    <div class="row textareaContainer">
                        <div class="input-field modifyTopicInput">
                            <p class="desciptrionLabel">Topic Description</p>
                            <div class="editorContainer" id="modifiedTopicDescription">
                                <div id="editor"><?php echo $selectedTopic["topicText"]; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row inputContainer">
                        <div class="col s6">
                            <label>Category</label>
                            <select class="browser-default" id="modifiedTopicCategory">
                                <option value="" disabled selected>Choose a category</option>
                                <?php 
                                    foreach($categories as $category) {
                                        if($category["categoryID"] == $selectedTopic["categoryID"]) {
                                            echo "<option selected value='" . $category['categoryID'] . "'>" . $category['categoryName'] . "</option>";
                                        } else {
                                            echo "<option value='" . $category['categoryID'] . "'>" . $category['categoryName'] . "</option>";
                                        }
                                    }
                                ?>
                            </select> 
                        </div>
                        <div class="col s6">
                            <label>Semester</label>
                            <select class="browser-default" id="modifiedTopicPeriod">
                                <option value="" disabled selected>Choose a semester</option>
                                <?php 
                                    foreach($periods as $period) {
                                        if($period["periodID"] == $selectedTopic["semester"]) {
                                            echo "<option selected value='" . $period['periodID'] . "'>" . $period['periodName'] . "</option>";
                                        } else {
                                            echo "<option value='" . $period['periodID'] . "'>" . $period['periodName'] . "</option>";
                                        }
                                    }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="row inputContainer createmodifyTopic">
                        <div class="col s6">
                            <p id="errorMsg"></p>
                            <hr id="errorMsgSeparator" class="hide">
                            <ul id="attachFiles"></ul>
                        </div>
                        <div class="col s1 offset-s3">
                            <div class="preloader-wrapper small active hide modifyTopicSpinner">
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
                        <a class="btn waves-effect waves-light blue col s2" id="modifyTopicSubmit" onclick="submitModifiedTopicData(<?php echo $selectedTopic["topicID"]; ?>)">Save</a>
                    </div>  
                </form>
            </div>
        </div>
        <div class="col s4">
            <div class="helperSideBarContainer">
                <h4><i class="far fa-lightbulb"></i> How to ask  </h4>
                <div class="line"></div>
                <div class="helperContent">
                    <p style="font-weight: 900">We prefer questions that can be answered or discussed.</p>
                    <p>Provide details and share things that support your question. </p>
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