/* error message outside of the functions' scope */
var errorMsg = "";

// if the admin change something in the sticky we save it
function submitModifiedSideBarStickyData(modifiedStickyID) {
    $('.modifyStickySpinner').removeClass('hide');
    var modifiedStickyName = $('#modifiedStickyName')["0"].value;
    var modifiedStickyDescription = $('#modifiedStickyDescription')["0"].value;

    if(modifiedStickyName !== "") {
        if(modifiedStickyDescription !== "") {
        $('#errorMsg').html("");
        $('#errorMsg').addClass('hide');

        $.post('/resources/controllers/discussionController.php', {modifiedStickyData: true, modifiedStickyID: modifiedStickyID, modifiedStickyName: modifiedStickyName, modifiedStickyDescription: modifiedStickyDescription}, function(returnData) {
            var obj = jQuery.parseJSON(returnData);

            if(obj.data_type == 1) {
                
                window.location.href = "/home#stickyPosts";
            
            } else {
            $('#errorMsg').html(obj.data_value);
            $('#errorMsg').removeClass('hide');    
            }
        });
        } else {
        $('#errorMsg').html("Please enter valid description of the sticky");
        $('#errorMsg').removeClass('hide');  
        }
    } else {
        $('#errorMsg').html("Please enter valid name of the sticky");
        $('#errorMsg').removeClass('hide');  
    }
  
    $('.modifyStickySpinner').addClass('hide');
  }