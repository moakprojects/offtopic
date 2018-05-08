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
                            <div class="editorContainer">
                                <div id="editor"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row inputContainer">
                        <div class="col s6">
                            <label>Category</label>
                            <select class="browser-default">
                                <option value="" disabled selected>Choose a category</option>
                                <option value="1">Everyday life</option>
                                <option value="2">Web development</option>
                                <option value="3">Marketing</option>
                                <option value="4">Multimedia design</option>
                            </select> 
                        </div>
                        <div class="col s6">
                            <label>Semester</label>
                            <select class="browser-default">
                                <option value="" disabled selected>Choose a semester</option>
                                <option value="1">first</option>
                                <option value="2">second</option>
                                <option value="3">third</option>
                                <option value="4">fourth</option>
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
                            <div class="preloader-wrapper small active hide replySpinner">
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
                        <a class="btn waves-effect waves-light blue col s2">Create</a>
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