
/* it is just for display in the input field the name of the attachment image */
var fileInput = document.getElementById('fileInput');
var fileInputText = document.getElementById('fileInputText');

$(document).on('change', '#fileInput', function(){
    validateAttachFile();
    if(errorMsg != "") {
        $('#errorMsg').removeClass('hide');
        $('#errorMsg').html(errorMsg);
    } else {
        $('#errorMsg').addClass('hide');
    }   
})

/* error message outside of the functions' scope */
var errorMsg = "";

/* selected file for attachment */
var attachment = ""; 

/* validate the attached files */
function validateAttachFile() {
      var tempName = "";
      var fileName = fileInput.files[0].name;
      var fileExtension = fileName.split('.').pop().toLowerCase();
      var fileSize = fileInput.files[0].size;
  
      if(jQuery.inArray(fileExtension, ['png', 'jpg', 'jpeg', 'PNG', 'JPG', 'JPEG']) == -1) {
        errorMsg = "<i>" + fileName + "</i> has invalid file type<br>";
        attachment = ""
      } else if(fileSize > 4000000) {
        errorMsg = "The size of <i>" + fileName + "</i> is too big<br>";
        attachment = "";
      } else {
        errorMsg = "";
        if (fileName.lastIndexOf('\\')) {
            tempName = fileName.lastIndexOf('\\') + 1;
        } else if (fileName.lastIndexOf('/')) {
            tempName = fileName.lastIndexOf('/') + 1;
        }
        fileInputText.value = fileName.slice(tempName, fileName.length);
        attachment = fileInput.files[0];
      }
}

// send new category data 
$(document).on('click', '#newCategorySubmit', function() {
    $('.newCategorySpinner').removeClass('hide');

    var newCategoryName = $('#newCategoryName')["0"].value;
    var newCategoryDescription = $('#newCategoryDescription')["0"].value;

    if(newCategoryName !== "") {
        if(newCategoryDescription !== "") {
            if(attachment != "") {
                $('#errorMsg').html("");
                $('#errorMsg').addClass('hide');
                
                var form_data = new FormData();
                form_data.append("file", attachment);
                form_data.append('createNewCategory', true);
                form_data.append('newCategoryName', newCategoryName);
                form_data.append('newCategoryDescription', newCategoryDescription);

                $.ajax({
                    url: '/resources/controllers/categoryController.php',
                    method: 'POST',
                    type: 'POST',
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        
                        var obj = jQuery.parseJSON(data);

                        if(obj.data_type == 1) {
                            
                            fileInputText.value = "Category thumbnail"
                            $('#newCategoryName')["0"].value = "";
                            $('#newCategoryDescription')["0"].value = "";
                            
                            $('.newCategorySpinner').addClass('hide');
                            $('#errorMsg').html(obj.data_value);
                            $('#errorMsg').removeClass('hide');
                            $('#errorMsg').css('color', '#34d034');
                        
                        } else {
                            $('.newCategorySpinner').addClass('hide');
                            $('#errorMsg').html(obj.data_value);
                            $('#errorMsg').removeClass('hide');    
                        }
                        
                    }
                });
            } else {
                $('#errorMsg').html("Please upload a thumbnail image");
                $('#errorMsg').removeClass('hide');  
            }
        } else {
        $('#errorMsg').html("Please enter the description of your new topic");
        $('#errorMsg').removeClass('hide');  
        }
    } else {
        $('#errorMsg').html("Please enter the name of you new topic");
        $('#errorMsg').removeClass('hide');  
    }
  
    $('.newCategorySpinner').addClass('hide');
});