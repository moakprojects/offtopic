<?php
if(isset($_SESSION["user"])) {
    $categoryObj = new Category();
    $topicObj = new Topic();
    $categories = $categoryObj->getCategoryData();
    $periods = $topicObj->getPeriodInfo();
?>
<div class="container contentContainer">
    <div class="row noBottomMargin">
        <div class="col s8"> 
            <h2>Create a new topic</h2>
            <div class="newTopicContainer">
                <form class="newTopicForm" action="" method="post" id="newTopicForm">  
                    <div class="row">
                        <div class="input-field newTopicInput">
                            <input id="newTopicName" type="text" class="noBottomMargin">
                            <label class="active" for="newTopicName">Topic name</label>
                        </div>
                    </div>
                    <div class="row textareaContainer">
                        <div class="input-field newTopicInput">
                            <p class="desciptrionLabel">Topic Description</p>
                            <div class="editorContainer"  id="newTopicDescription">
                                <div id="editor"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row inputContainer">
                        <div class="col s6">
                            <label>Category</label>
                            <select class="browser-default" id="newTopicCategory">
                                <option value="" disabled selected>Choose a category</option>
                                <?php 
                                    foreach($categories as $category) {
                                        echo "<option value='" . $category['categoryID'] . "'>" . $category['categoryName'] . "</option>";
                                    }
                                ?>
                            </select> 
                        </div>
                        <div class="col s6">
                            <label>Semester</label>
                            <select class="browser-default" id="newTopicPeriod">
                                <option value="" disabled selected>Choose a semester</option>
                                <?php 
                                    foreach($periods as $period) {
                                        echo "<option value='" . $period['periodID'] . "'>" . $period['periodName'] . "</option>";
                                    }
                                ?>
                            </select> 
                        </div>
                    </div>
                    <div class="row inputContainer createNewTopic">
                        <div class="col s6">
                            <p id="errorMsg"></p>
                            <hr id="errorMsgSeparator" class="hide">
                            <ul id="attachFiles"></ul>
                        </div>
                        <div class="col s1 offset-s3">
                            <div class="preloader-wrapper small active hide newtopicSpinner">
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
                        <a class="btn waves-effect waves-light blue col s2" id="newTopicSubmit">Create</a>
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
                    <p>If your question is about this website, please use the contact form. </p>
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