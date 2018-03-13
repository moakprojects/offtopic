var toolbarOptions = [
    [{ 'size': ['small', false, 'large', 'huge'] }, 'bold', 'italic', 'underline', 'strike'],
    [ 'blockquote', 'link'],
    [{ 'indent': '-1'}, { 'indent': '+1' }, { 'align': [] }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],
    ['clean'],
    ['omega']
  ];
  
  var quill = new Quill('#editor', {
    modules: {
      toolbar: toolbarOptions
    },
    placeholder: 'Post a Reply',
    theme: 'snow'
  });

  var file;
var customButton = document.querySelector('.ql-omega');
  customButton.addEventListener('click', function() {
      $('#errorMsg').addClass('hide');
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.click();

      input.onchange = () => {
        file = input.files[0];
        
        var fileName = file.name;
        var fileExtension = fileName.split('.').pop().toLowerCase();
        var fileSize = file.size;

        if(jQuery.inArray(fileExtension, ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'mp4', 'mpeg', 'txt', 'ppt', 'pptx', 'xls', 'xlsx', 'epub']) == -1) {
          $('#errorMsg').removeClass('hide');
          $('#errorMsg').html("Invalid file type");
        } else if(fileSize > 4000000) {
          $('#errorMsg').removeClass('hide');
          $('#errorMsg').html("The size is too big");
        }
      }
});

function like(postId) {
  console.log("like id, ", postId);

  $.post('database/discussionFeatures.php', {userId: 10, postId: postId, mood: "like"}, function(returnData) {
    if(returnData) {
      $.post('database/discussionFeatures.php', {reCount: postId, mood: "like"}, function(data) {
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

  $.post('database/discussionFeatures.php', {userId: 10, postId: postId, mood: "dislike"}, function(returnData) {
    if(returnData) {
      $.post('database/discussionFeatures.php', {reCount: postId, mood: "dislike"}, function(data) {
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
  if(file) {
    var form_data = new FormData();
    form_data.append("file", file);
    $.ajax({
      url: 'database/discussionFeatures.php',
      method: 'POST',
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        $('.preloader-wrapper').addClass('hide');
        var obj = jQuery.parseJSON(data);
        
        if(obj.data_type == 0) {
          $('#errorMsg').removeClass('hide');
          $('#errorMsg').html(obj.data_value);
        } else {
          uploadPost();
        }
        
      }
    });
  } else {
    uploadPost();
  }
});

function uploadPost() {
  var replyContent = quill.root.innerHTML;
  $.post('database/discussionFeatures.php', {replyContent: replyContent, userId: 1, topicId: 1, }, function(returnData) {
    quill.deleteText(0, quill.getLength());
    $('#postContainer').load('/discussion #postContainer', function() {
      $('.replySpinner').addClass('hide');
    });
  });
}