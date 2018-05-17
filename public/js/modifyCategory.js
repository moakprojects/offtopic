/* it is just for display the name of the attachment image */
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
        fileInputText.value = "";
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
function submitModifiedCategoryData(categoryID, oldImage) {
    $('.modifyCategorySpinner').removeClass('hide');

    var modifiedCategoryName = $('#modifiedCategoryName')["0"].value;
    var modifiedCategoryDescription = $('#modifiedCategoryDescription')["0"].value;
    if (attachment == "") {
        attachment = fileInputText.placeholder;
    } else {
        attachment = attachment;
    }

    if(modifiedCategoryName !== "") {
        if(modifiedCategoryDescription !== "") {
                $('#errorMsg').html("");
                $('#errorMsg').addClass('hide');
                
                var form_data = new FormData();
                form_data.append("file", attachment);
                form_data.append('oldImage', oldImage);
                form_data.append('modifiedCategoryData', true);
                form_data.append('modifiedCategoryID', categoryID);
                form_data.append('modifiedCategoryName', modifiedCategoryName);
                form_data.append('modifiedCategoryDescription', modifiedCategoryDescription);

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
                            
                            window.location.href = "/categories";
                        
                        } else {
                            $('.modifyCategorySpinner').addClass('hide');
                            $('#errorMsg').html(obj.data_value);
                            $('#errorMsg').removeClass('hide');    
                        }
                        
                    }
                });
        } else {
        $('#errorMsg').html("Please enter the description of the category topic");
        $('#errorMsg').removeClass('hide');  
        }
    } else {
        $('#errorMsg').html("Please enter a valid name of the category");
        $('#errorMsg').removeClass('hide');  
    }
  
    $('.modifyCategorySpinner').addClass('hide');
}