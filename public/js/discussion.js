var toolbarOptions = [
  [{ 'size': ['small', false, 'large', 'huge'] }, 'bold', 'italic', 'underline', 'strike'],
  [ 'link', 'blockquote', 'attachment'],
  [{ 'indent': '-1'}, { 'indent': '+1' }, { 'align': [] }],
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],
  ['clean'],
];

/*$('.ql-bold').children().addClass('tooltipped');
$('.ql-bold').children().addAttr('data-position','bottom');
$('.ql-bold').children().addAttr('data-delay','50');
$('.ql-bold').children().addAttr('data-tooltip','I am a tooltip');*/

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

      console.log('upload: ', currentFiles);      
      
      validateAttachFile();
      console.log("error a futás végén: ", errorMsg);
      if(errorMsg != "") {
        $('#errorMsg').removeClass('hide');
        if(currentFiles.length > 0) {
          $('#errorMsgSeparator').removeClass('hide');
        }
        $('#errorMsg').html(errorMsg);
      }
    }
});

$(document).on('change', '.ql-editor', function() {
  console.log("változok, változok, egész nap csak változok");
});

quill.on("text-change", function(delta, oldDelta, source) {
  if(quill.getLength() > 1) {
    $('.postReplyBtn').removeClass('disabled');
  } else {
    $('.postReplyBtn').addClass('disabled');
  }
})

function validateAttachFile() {
  $('#attachFiles').html("");
  
  for(var i = 0; i < currentFiles.length; i++) {
    var fileName = currentFiles[i].name;
    var fileExtension = fileName.split('.').pop().toLowerCase();
    var displayedFileName = fileName;
    console.log("filename", fileName);
    console.log("displayed", displayedFileName);
    console.log("length", fileName.length);
    console.log("extlength", fileExtension.length);
    console.log("össz", 26 - fileExtension.length);
    if(fileName.length > 27 + fileExtension.length) {
      var cuttedFileName = fileName.substring(0, 27);
      displayedFileName = cuttedFileName + '....' + fileExtension;
    }
    console.log('aktuális fájl: ', currentFiles[i]);
    var fileSize = currentFiles[i].size;

    if(jQuery.inArray(fileExtension, ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'mp4', 'mpeg', 'txt', 'ppt', 'pptx', 'xls', 'xlsx', 'epub']) == -1) {
      errorMsg += "<i>" + fileName + "</i> has invalid file type<br>";
      console.log("rossz kiterjesztés");
      console.log("error a rossz kiterjesztésben", errorMsg);
      removeAttachFile(i);
    } else if(fileSize > 4000000) {
      errorMsg += "The size of <i>" + fileName + "</i> is too big<br>";
      console.log("nagy méret");
      console.log("error a nagy méretben", errorMsg);
      removeAttachFile(i);
    } else {
      $('#attachFiles').append("<li><span>" + displayedFileName + "</span><span onclick='removeAttachFile(" + i + ")'><i class='material-icons'>clear</i></span></li>");
    }
  }
}

function removeAttachFile(index) {
  console.log('index: ', index);
  console.log('törlés előtt: ', currentFiles);
  
  currentFiles.splice(index, 1);

  console.log('törlés után: ', currentFiles);
  
  validateAttachFile();
}

function like(postId) {
  console.log("like id, ", postId);

  $.post('/database/discussionFeatures.php', {userId: 10, postId: postId, mood: "like"}, function(returnData) {
    if(returnData) {
      $.post('/database/discussionFeatures.php', {reCount: postId, mood: "like"}, function(data) {
        var aux = '.likeValue' + postId;
        $(aux).html(data);
        var likeBtn = '.likeFloatBtn' + postId;
        var dislikeBtn = '.dislikeFloatBtn' + postId;
        $(likeBtn).addClass('disabled');
        $(likeBtn).children().css("color", "rgb(245, 247, 250)");
        $(dislikeBtn).addClass('disabled');
        $(dislikeBtn).children().css("color", "rgb(245, 247, 250)");
      });
    }
  });
}

function dislike(postId) {
  console.log("dislike id, ", postId);

  $.post('/database/discussionFeatures.php', {userId: 10, postId: postId, mood: "dislike"}, function(returnData) {
    if(returnData) {
      $.post('/database/discussionFeatures.php', {reCount: postId, mood: "dislike"}, function(data) {
        var aux = '.dislikeValue' + postId;
        $(aux).html(data);
        var likeBtn = '.likeFloatBtn' + postId;
        var dislikeBtn = '.dislikeFloatBtn' + postId;
        $(likeBtn).addClass('disabled');
        $(likeBtn).children().css("color", "rgb(245, 247, 250)");
        $(dislikeBtn).addClass('disabled');
        $(dislikeBtn).children().css("color", "rgb(245, 247, 250)");
      });
    }
  });
}

$(document).on('click', '.postReplyBtn', function() {

  $('.replySpinner').removeClass('hide');

  uploadPost();
});

function uploadPost() {
  var replyContent = quill.root.innerHTML;
  $.post('/database/discussionFeatures.php', {replyContent: replyContent, userId: 1, topicId: 1, }, function(returnData) {
    if(currentFiles.length > 0) {
    
      var attachedFiles = new FormData();
      $.each(currentFiles, function(i, file) {
          attachedFiles.append('file-'+i, file);
      });
      
      $.ajax({
        url: '/database/discussionFeatures.php',
        method: 'POST',
        type: 'POST',
        data: attachedFiles,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          var obj = jQuery.parseJSON(data);
          
          if(obj.data_type == 0) {
            $('#errorMsg').removeClass('hide');
            $('#errorMsg').html(obj.data_value);
          } else {
            $('#attachFiles').html("");
            refreshPostContent();
          }
        }
      });
    } else {
      refreshPostContent();
    }
  });
}

function refreshPostContent() {
  quill.deleteText(0, quill.getLength());
  $('#postContainer').load('/discussion #postContainer', function() {
    currentFiles = [];
    $('#errorMsg').addClass('hide');    
    errorMsg = "";    
    $('#errorMsgSeparator').addClass('hide');
    $('.replySpinner').addClass('hide');
  });
}