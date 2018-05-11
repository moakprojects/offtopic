/* customization of quill.js for discussion page */
var toolbarOptions = [
  [{ 'size': ['small', false, 'large', 'huge'] }, 'bold', 'italic', 'underline', 'strike'],
  [ 'link', 'blockquote', 'attachment'],
  [{ 'indent': '-1'}, { 'indent': '+1' }, { 'align': [] }],
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],
  ['clean'],
];

/* inicialize quill.js */
var quill = new Quill('#editor', {
  modules: {
    toolbar: {
      container: toolbarOptions,
      handlers: {
        'attachment': () => {console.log('attachment control was clicked')}
      }
    }
  },
  placeholder: 'Post a Reply',
  theme: 'snow'
});

/* currentFile mean the current selected files for attachment */
var currentFiles = [];

/* error message outside of the functions' scope */
var errorMsg = "";

/* create a custom button inside quill to attach files */
var customButton = document.querySelector('.ql-attachment');
customButton.addEventListener('click', function() {
    
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('multiple', '');
    input.click();

    input.onchange = () => {
      
      $('#errorMsg').addClass('hide');    
      errorMsg = "";    
      $('#errorMsgSeparator').addClass('hide');
      Array.prototype.push.apply(currentFiles, input.files);      
      
      validateAttachFile();
      if(errorMsg != "") {
        $('#errorMsg').removeClass('hide');
        if(currentFiles.length > 0) {
          $('#errorMsgSeparator').removeClass('hide');
        }
        $('#errorMsg').html(errorMsg);
      }
    }
});

/* set the reply button availability */
quill.on("text-change", function(delta, oldDelta, source) {
  if(quill.getLength() > 1) {
    $('.postReplyBtn').removeClass('disabled');
  } else {
    $('.postReplyBtn').addClass('disabled');
  }
})

/* validate the attached files */
function validateAttachFile() {
  $('#attachFiles').html("");
  
  for(var i = 0; i < currentFiles.length; i++) {
    var fileName = currentFiles[i].name;
    var fileExtension = fileName.split('.').pop().toLowerCase();
    var displayedFileName = fileName;
    if(fileName.length > 27 + fileExtension.length) {
      var cuttedFileName = fileName.substring(0, 27);
      displayedFileName = cuttedFileName + '....' + fileExtension;
    }

    var fileSize = currentFiles[i].size;

    if(jQuery.inArray(fileExtension, ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'mp4', 'mpeg', 'txt', 'ppt', 'pptx', 'xls', 'xlsx', 'epub']) == -1) {
      errorMsg += "<i>" + fileName + "</i> has invalid file type<br>";

      removeAttachFile(i);
    } else if(fileSize > 4000000) {
      errorMsg += "The size of <i>" + fileName + "</i> is too big<br>";

      removeAttachFile(i);
    } else {
      $('#attachFiles').append("<li><span>" + displayedFileName + "</span><span onclick='removeAttachFile(" + i + ")'><i class='material-icons'>clear</i></span></li>");
    }
  }
}

/* remove selected attached file from the list of current files */ 
function removeAttachFile(index) {
  
  currentFiles.splice(index, 1);
  
  validateAttachFile();
}

/* send data to backend to increase number of likes of selected post */
function likePost(postId) {
  $.post('/resources/controllers/discussionController.php', {postId: postId, mood: "like"}, function(returnData) {

    var obj = jQuery.parseJSON(returnData);
    if(obj.data_type === 1) {

      $.post('/resources/controllers/discussionController.php', {reCountID: postId}, function(data) {
        var objRecount = jQuery.parseJSON(data);
        if(objRecount.data_type === 1) {
          var aux = '.likeValue' + postId;
          $(aux).html(objRecount.data_value.numberOfLikes);
          var likeBtn = '.likeFloatBtn' + postId;
          var dislikeBtn = '.dislikeFloatBtn' + postId;
          $(likeBtn).addClass('disabled');
          $(likeBtn).children().css("color", "rgb(245, 247, 250)");
          $(dislikeBtn).addClass('disabled');
          $(dislikeBtn).children().css("color", "rgb(245, 247, 250)");
        }
      });
    }
  });
}

/* send data to backend to increase number of dislikes of selected post */
function dislikePost(postId) {
  $.post('/resources/controllers/discussionController.php', {postId: postId, mood: "dislike"}, function(returnData) {
    
    var obj = jQuery.parseJSON(returnData);
    if(obj.data_type === 1) {
      $.post('/resources/controllers/discussionController.php', {reCountID: postId}, function(data) {
        var objRecount = jQuery.parseJSON(data);
        if(objRecount.data_type === 1) {

          var aux = '.dislikeValue' + postId;
          $(aux).html(objRecount.data_value.numberOfDislikes);
          var likeBtn = '.likeFloatBtn' + postId;
          var dislikeBtn = '.dislikeFloatBtn' + postId;
          $(likeBtn).addClass('disabled');
          $(likeBtn).children().css("color", "rgb(245, 247, 250)");
          $(dislikeBtn).addClass('disabled');
          $(dislikeBtn).children().css("color", "rgb(245, 247, 250)");
        }
      });
    }
  });
}

var replyId = null;

/* start a spinner during backend call and call uploadPost function */
$(document).on('click', '.postReplyBtn', function() {

  $('.replySpinner').removeClass('hide');

  uploadPost();
});

/* upload a post and attached files into the database and storage */ 
function uploadPost() {
  var replyContent = quill.root.innerHTML;
  $.post('/resources/controllers/discussionController.php', {replyContent: replyContent, replyID: replyId}, function(returnData) {
    var obj = jQuery.parseJSON(returnData);
          
    if(obj.data_type == 0) {
      $('#errorMsg').removeClass('hide');
      $('#errorMsg').html(obj.data_value);
    } else {
      if(currentFiles.length > 0) {
    
        var attachedFiles = new FormData();
        $.each(currentFiles, function(i, file) {
            attachedFiles.append('file-'+i, file);
        });
        
        $.ajax({
          url: '/resources/controllers/discussionController.php',
          method: 'POST',
          type: 'POST',
          data: attachedFiles,
          contentType: false,
          cache: false,
          processData: false,
          success: function(data) {
            var fileObj = jQuery.parseJSON(data);
            
            if(fileObj.data_type == 0) {
              $('#errorMsg').removeClass('hide');
              $('#errorMsg').html(fileObj.data_value);
            } else {
              $('#attachFiles').html("");
              refreshPostContent(obj.data_value);
            }
          }
        });
      } else {
        refreshPostContent(obj.data_value);
      } 
      replyId = null;
    }
  });
}

/* refresh posts after backend response to display the new post immediately */
function refreshPostContent(selectedTopicID) {
  
  quill.deleteText(0, quill.getLength());
  
  $('#originalPostID').eq(0).html("");
  $('#replyTo').eq(0).html("");
  $('.replyLabel').eq(0).addClass('hide');
  
  $('#postContainer').load('/topics/' + selectedTopicID + ' #postContainer', function() {
    currentFiles = [];
    $('#errorMsg').addClass('hide');    
    errorMsg = "";    
    $('#errorMsgSeparator').addClass('hide');
    $('.replySpinner').addClass('hide');
  });
}

/* display original post for what the user answer */ 
function replyPost(postId, username) {

  $('#originalPostID').eq(0).html("#" + postId);
  $('#replyTo').eq(0).html(username);
  $('.replyLabel').eq(0).removeClass('hide');

  replyId = postId;
  scrollToEditor();

}

/*when the user reply for the discussion starter then we don't send replyID */
function replyForTheTopic() {
  replyId = null;

  $('#originalPostID').eq(0).html("");
  $('#replyTo').eq(0).html("");
  $('.replyLabel').eq(0).addClass('hide');

  scrollToEditor();
}

/* scroll down to quill editor */
function scrollToEditor() {
  $('html, body').animate({
      scrollTop: $(".editorTop").offset().top
  }, 1000);
}

/* backend request after user's topic like */
function likeTopic(userId, topicId, action) {
  $.post('/resources/controllers/topicController.php', {userId: userId, favouriteSelectedTopic: topicId, action: action}, function(data) {
    
    var obj = jQuery.parseJSON(data);
    if(obj.data_type === 1) {
      if(obj.data_value === "added") {
        $('#topicLikeButtonContainer').load('/topics/' + topicId + ' #topicLikeButtonContainer', function() {
          $('.topicLikeButton').children().removeClass('far').addClass('fas');
        });
      } else {
        $('#topicLikeButtonContainer').load('/topics/' + topicId + ' #topicLikeButtonContainer', function() {
          $('.topicLikeButton').children().removeClass('fas').addClass('far');
        });
      }

      
    }
  });
}

(function ($) {
  console.log("elmegy a cucc");
  $.post('/resources/controllers/topicController.php', {selectedTopic: true}, function(data) {
    console.log("visszajön a cucc");
    var obj = jQuery.parseJSON(data);
    if(obj.data_type === 1) {
      $('#selectedTopicContainer').load('/topics/' + obj.data_value.topicID + ' #selectedTopicContainer', {selectedTopic: obj.data_value}, '');
    }
  });
}(jQuery));