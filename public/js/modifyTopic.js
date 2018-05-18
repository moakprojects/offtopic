
/* customization of quill.js for discussion page */
   var toolbarOptions = [
    [{ 'size': ['small', false, 'large', 'huge'] }, 'bold', 'italic', 'underline', 'strike'],
    [ 'link', 'blockquote'],
    [{ 'indent': '-1'}, { 'indent': '+1' }, { 'align': [] }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],
    ['clean'],
];

/* inicialize quill.js */
var quill = new Quill('#editor', {
    modules: {
    toolbar: {
        container: toolbarOptions
    }
    },
    placeholder: 'Write something about the description of the topic',
    theme: 'snow'
});

/* originalAttachment mean the original attachment files of the topic for backup */
var originalAttachment = [];
/* newAttachment is an array where we can store only those attachment files which the admin want to keep */
var newAttachment = [];
/* remove attachemnt is just a help array where we store the attachmentnames what the admin want to delete */
var removeAttachFiles = [];

/* get the topic description and attachment filenames when the page is ready */
$( document ).ready(function() {
    $.post('/resources/controllers/topicController.php', {getSelectedTopicDataFromJs: true}, function(returnData) {
        var obj = jQuery.parseJSON(returnData);
        if(obj.data_type != 0) {
            quill.insertText(0, obj.topicText);
            if(obj[0]) {
                for(i=0; i < obj[0].length; i++) {
                    originalAttachment.push(obj[0][i]);
                    newAttachment.push(obj[0][i].displayName);
                    $('#attachFiles').append("<li><span>" + obj[0][i].displayName + "</span><span onclick='removeAttachFile(" + i + ")'><i class='material-icons'>clear</i></span></li>");
                }
            }
        }
    });
});

/* remove selected attached file from the list of current files */ 
function removeAttachFile(index) {
    newAttachment.splice(index, 1);
    $('#attachFiles').html("");
    for(i=0; i<newAttachment.length;i++) {
        $('#attachFiles').append("<li><span>" + newAttachment + "</span><span onclick='removeAttachFile(" + i + ")'><i class='material-icons'>clear</i></span></li>");
    }
}

/* error message outside of the functions' scope */
var errorMsg = "";

function submitModifiedTopicData(id) {
    $('.modifyTopicSpinner').removeClass('hide');
    var modifiedTopicName = $('#modifiedTopicName')["0"].value;
    var modifiedTopicDescription = quill.getText().trim();
    var modifiedTopicCategory = $('#modifiedTopicCategory')["0"].value;
    var modifiedTopicPeriod = $('#modifiedTopicPeriod')["0"].value;

    if(modifiedTopicName !== "") {
        if(modifiedTopicDescription !== "") {
            if(modifiedTopicCategory !== "") {
                if(modifiedTopicPeriod !== "") {
                    $('#errorMsg').html("");
                    $('#errorMsg').addClass('hide');

                    if (typeof originalAttachment[0] !== 'undefined' && originalAttachment[0] !== null) {
                        for(i=0; i<originalAttachment.length;i++) {
                            if(jQuery.inArray(originalAttachment[i].displayName, newAttachment) == -1) {
                                removeAttachFiles.push(originalAttachment[i]);
                            }
                        }
                    }

                    $.post('/resources/controllers/topicController.php', {modifiedTopicData: true, modifiedTopicID: id, modifiedTopicName: modifiedTopicName, modifiedTopicDescription: modifiedTopicDescription, modifiedTopicCategory: modifiedTopicCategory, modifiedTopicPeriod: modifiedTopicPeriod, removeAttachedFiles: JSON.stringify(removeAttachFiles)}, function(returnData) {
                        var obj = jQuery.parseJSON(returnData);
            
                        if(obj.data_type == 1) {
                            window.location.assign("/home/#topics");
                        } else {
                            $('.modifyCategorySpinner').addClass('hide');
                            $('#errorMsg').html(obj.data_value);
                            $('#errorMsg').removeClass('hide');    
                        }
                    });
                    
                } else {
                    $('#errorMsg').html("Please select a period of the topic");
                    $('#errorMsg').removeClass('hide');  
                }
            } else {
                $('#errorMsg').html("Please select a category of the topic");
                $('#errorMsg').removeClass('hide');  
            }
        } else {
            $('#errorMsg').html("Please enter a valid description of the topic");
            $('#errorMsg').removeClass('hide');
        }
    } else {
        $('#errorMsg').html("Please enter a valid name of the topic");
        $('#errorMsg').removeClass('hide');
    }

    $('.modifyTopicSpinner').addClass('hide');
};