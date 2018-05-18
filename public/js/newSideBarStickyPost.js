/* error message outside of the functions' scope */
var errorMsg = "";

$(document).on('click', '#newStickySubmit', function() {
    $('.newStickySpinner').removeClass('hide');
    var newStickyName = $('#newStickyName')["0"].value;
    var newStickyDescription = $('#newStickyDescription')["0"].value;

    if(newStickyName !== "") {
        if(newStickyDescription !== "") {
        $('#errorMsg').html("");
        $('#errorMsg').addClass('hide');

        $.post('/resources/controllers/discussionController.php', {createNewSticky: true, newStickyName: newStickyName, newStickyDescription: newStickyDescription}, function(returnData) {
            var obj = jQuery.parseJSON(returnData);

            if(obj.data_type == 1) {
                
                $('#newStickyName')["0"].value = "";
                $('#newStickyDescription')["0"].value = "";
                
                $('.newStickySpinner').addClass('hide');
                $('#errorMsg').html(obj.data_value);
				$('#errorMsg').removeClass('hide');
				$('#errorMsg').css('color', '#34d034');
            
            } else {
            $('#errorMsg').html(obj.data_value);
            $('#errorMsg').removeClass('hide');    
            }
        });
        } else {
        $('#errorMsg').html("Please enter the description of your new sticky post");
        $('#errorMsg').removeClass('hide');  
        }
    } else {
        $('#errorMsg').html("Please enter the name of you new sticky post");
        $('#errorMsg').removeClass('hide');  
    }
  
    $('.newStickySpinner').addClass('hide');
  });