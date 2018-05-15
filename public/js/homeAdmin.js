//if the admin wants to suspend the user we send the selected username for the business layer
function suspendUser(userID) {

    $.post("/resources/controllers/userController.php", {suspendUser: true, userID: userID}, function(data) {
        var obj = jQuery.parseJSON(data);
        Materialize.toast('You suspend ' + obj.data_value + ' user', 4000);
    });
}

function setSticky(postID) {
    $.post('/resources/controllers/discussionController.php', {stickyID: postID, type: "unsticky"}, function(data) {

      var obj = jQuery.parseJSON(data);
      if(obj.data_type === 1) {
        $('.topicRelatedStickies').load('/home/ .topicRelatedStickies', function() {
            Materialize.toast('You removed the sticky mark of this post', 4000);
        });
      }
    }); 
}

// if the admin change something about description of the site
$(document).on('click', '#modifyDescriptionSubmit', function() {
    $('.modifyDescriptionSpinner').removeClass('hide');
    var newDescription = $('#newDescription')["0"].value;
    
    if(newDescription !== "") {
    $('#descriptionError').html("");
    $('#descriptionError').addClass('hide');

    $.post('/resources/controllers/generalController.php', {changeDescription: true, newDescription: newDescription}, function(returnData) {
        var obj = jQuery.parseJSON(returnData);

        if(obj.data_type == 1) {
            
            $('.modifyDescriptionSpinner').addClass('hide');
            $('#descriptionError').html(obj.data_value);
            $('#descriptionError').removeClass('hide');
            $('#descriptionError').css('color', '#34d034');
        
        } else {
        $('#descriptionError').css('color', 'red');
        $('#descriptionError').html(obj.data_value);
        $('#descriptionError').removeClass('hide');  
        }
    });
    } else {
    $('#descriptionError').css('color', 'red');
    $('#descriptionError').html("Please enter something about us");
    $('#descriptionError').removeClass('hide');
    }
  
    $('.modifyDescriptionSpinner').addClass('hide');
  });

  // if the admin change something about the contact information
$(document).on('click', '#modifyContactSubmit', function() {
    $('.modifyContactSpinner').removeClass('hide');
    var newGeneralContactText = $('#newGeneralContactText')["0"].value;
    var newPhoneNumber = $('#newPhoneNumber')["0"].value;
    var newLocation = $('#newLocation')["0"].value;

    if(newGeneralContactText !== "") {
        if(newPhoneNumber !== "") {
            if(newLocation !== "") {
                $('#contactErrorMsg').html("");
                $('#contactErrorMsg').addClass('hide');

                $.post('/resources/controllers/generalController.php', {changeContact: true, newGeneralContactText: newGeneralContactText, newPhoneNumber: newPhoneNumber, newLocation: newLocation}, function(returnData) {
                    var obj = jQuery.parseJSON(returnData);

                    if(obj.data_type == 1) {                        
                        $('.modifyContactSpinner').addClass('hide');
                        $('#contactErrorMsg').html(obj.data_value);
                        $('#contactErrorMsg').removeClass('hide');
                        $('#contactErrorMsg').css('color', '#34d034');
                    
                    } else {
                    $('#contactErrorMsg').css('color', 'red');
                    $('#contactErrorMsg').html(obj.data_value);
                    $('#contactErrorMsg').removeClass('hide'); 
                    }
                });
            } else {
                $('#contactErrorMsg').css('color', 'red');
                $('#contactErrorMsg').html("Please enter a right location of the office");
                $('#contactErrorMsg').removeClass('hide'); 
            }
        } else {
            $('#contactErrorMsg').css('color', 'red');
            $('#contactErrorMsg').html("Please enter a right phone number");
            $('#contactErrorMsg').removeClass('hide'); 
        }
    } else {
        $('#contactErrorMsg').css('color', 'red');
        $('#contactErrorMsg').html("Please enter the general contact guide");
        $('#contactErrorMsg').removeClass('hide'); 
    }
  
    $('.modifyContactSpinner').addClass('hide');
  });

  // if the admin change the part of the rules and regulations
$(document).on('click', '#modifyRulesSubmit', function() {
    $('.modifyRulesSpinner').removeClass('hide');
    var newGeneralRules = $('#newGeneralRules')["0"].value;
    var newAcceptanceOfTerms = $('#newAcceptanceOfTerms')["0"].value;
    var newModificationOfTerms = $('#newModificationOfTerms')["0"].value;
    var newRulesAndConduct = $('#newRulesAndConduct')["0"].value;
    var newTermination = $('#newTermination')["0"].value;
    var newIntegration = $('#newIntegration')["0"].value;

    if(newGeneralRules !== "") {
        if(newAcceptanceOfTerms !== "") {
            if(newModificationOfTerms !== "") {
                if(newRulesAndConduct !== "") {
                    if(newTermination !== "") {
                        if(newIntegration !== "") {
                            $('#rulesErrorMsg').html("");
                            $('#rulesErrorMsg').addClass('hide');

                            $.post('/resources/controllers/generalController.php', {changeRules: true, newGeneralRules: newGeneralRules, newAcceptanceOfTerms: newAcceptanceOfTerms, newModificationOfTerms:newModificationOfTerms, newRulesAndConduct: newRulesAndConduct,  newTermination: newTermination, newIntegration: newIntegration }, function(returnData) {
                                var obj = jQuery.parseJSON(returnData);

                                if(obj.data_type == 1) {
                                    
                                    $('.modifyRulesSpinner').addClass('hide');
                                    $('#rulesErrorMsg').html(obj.data_value);
                                    $('#rulesErrorMsg').removeClass('hide');
                                    $('#rulesErrorMsg').css('color', '#34d034');
                                
                                } else {
                                $('#rulesErrorMsg').css('color', 'red');
                                $('#rulesErrorMsg').html(obj.data_value);
                                $('#rulesErrorMsg').removeClass('hide');  
                                }
                            });
                        } else {
                            $('#rulesErrorMsg').css('color', 'red');
                            $('#rulesErrorMsg').html("Please enter something about Integration and Severability");
                            $('#rulesErrorMsg').removeClass('hide'); 
                        }
                    } else {
                        $('#rulesErrorMsg').css('color', 'red');
                        $('#rulesErrorMsg').html("Please enter something about Termination");
                        $('#rulesErrorMsg').removeClass('hide'); 
                    }
                } else {
                    $('#rulesErrorMsg').css('color', 'red');
                    $('#rulesErrorMsg').html("Please enter something about Rules and Conduct");
                    $('#rulesErrorMsg').removeClass('hide'); 
                }
            } else {
                $('#rulesErrorMsg').css('color', 'red');
                $('#rulesErrorMsg').html("Please enter something about Modification of Terms of Use");
                $('#rulesErrorMsg').removeClass('hide'); 
            }
        } else {
            $('#rulesErrorMsg').css('color', 'red');
            $('#rulesErrorMsg').html("Please enter some Acceptance of Terms");
            $('#rulesErrorMsg').removeClass('hide'); 
        }
    } else {
        $('#rulesErrorMsg').css('color', 'red');
        $('#rulesErrorMsg').html("Please enter the general rules guide");
        $('#rulesErrorMsg').removeClass('hide');  
    }
  
    $('.modifyRulesSpinner').addClass('hide');
  });