
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