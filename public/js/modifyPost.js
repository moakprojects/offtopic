
/* customization of quill.js for discussion page */
var toolbarOptions = [
    [{ 'size': ['small', false, 'large', 'huge'] }, 'bold', 'italic', 'underline', 'strike'],
    [ 'link', 'blockquote'],
    [{ 'indent': '-1'}, { 'indent': '+1' }, { 'align': [] }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],
    ['clean'],
];

/* initialize quill.js */
var quill = new Quill('#editor', {
    modules: {
    toolbar: {
        container: toolbarOptions
    }
    },
    placeholder: 'Write something meaningful',
    theme: 'snow'
});

/* originalAttachment mean the original attachment files of the post for backup */
var originalAttachment = [];
/* currentAttachment mean the current attachment files of the post for backup */
var currentAttachment = [];
/* newAttachment is an array where we can store only those attachment file names which the admin want to keep */
var newAttachment = [];
/* remove attachment is just a help array where we store the attachmentnames what the admin want to delete */
var removeAttachFiles = [];

/* get the topic description and attachment filenames when the page is ready */
$( document ).ready(function() {
    $.post('/resources/controllers/discussionController.php', {getSelectedPostData: true}, function(returnData) {
        var obj = jQuery.parseJSON(returnData);
        if(obj.data_type != 0) {
            if(obj[0]) {
                for(i=0; i < obj[0].length; i++) {
                    originalAttachment.push(obj[0][i]);
                    currentAttachment.push(obj[0][i]);                    
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
    currentAttachment.splice(index, 1);
    console.log("newAttachment", newAttachment);
    $('#attachFiles').html("");
    for(i=0; i<currentAttachment.length;i++) {
        $('#attachFiles').append("<li><span>" + currentAttachment[i].displayName + "</span><span onclick='removeAttachFile(" + i + ")'><i class='material-icons'>clear</i></span></li>");
    }
}

/* error message outside of the functions' scope */
var errorMsg = "";

function submitModifiedPostData(id) {
    $('.modifyPostSpinner').removeClass('hide');
    
    if(quill.getText().trim() !== '') {
        var modifiedPostText = quill.root.innerHTML;
        modifiedPostText = modifiedPostText + '<p class="right-align moderated">- moderated by Admin</p>';
        
        $('#errorMsg').html("");
        $('#errorMsg').addClass('hide');

        if (typeof originalAttachment[0] !== 'undefined' && originalAttachment[0] !== null) {
            for(i=0; i<originalAttachment.length;i++) {
                if(jQuery.inArray(originalAttachment[i].displayName, newAttachment) == -1) {
                    removeAttachFiles.push(originalAttachment[i]);
                }
            }
        }
        
       $.post('/resources/controllers/discussionController.php', {modifiedPostData: true, modifiedPostID: id, modifiedPostText: modifiedPostText, removeAttachedFiles: JSON.stringify(removeAttachFiles)}, function(returnData) {
            var obj = jQuery.parseJSON(returnData);

            if(obj.data_type == 1) {
                window.location.assign("/home/#posts");
            } else {
                $('.modifyCategorySpinner').addClass('hide');
                $('#errorMsg').html(obj.data_value);
                $('#errorMsg').removeClass('hide');    
            }
        });
    } else {
        $('#errorMsg').html("Please enter a valid post");
        $('#errorMsg').removeClass('hide');  
    }

    $('.modifyPostSpinner').addClass('hide');
};