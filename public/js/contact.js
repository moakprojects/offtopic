/* error message outside of the functions' scope */
var errorMsg = "";

/* when the user send the contact form we check data and provide them to the controller */
$(document).on('click', '#contactSubmit', function() {
    $('.contactSpinner').removeClass('hide');

    var senderName = $('#senderName')["0"].value;
    var senderEmail = $('#senderEmail')["0"].value;
    // the subject field is a honypot, if it has value then we will know not a human filled out the form
    var subject = $('#subjectFroDescription')["0"].value;
    var problemDescription = $('#problemDescription')["0"].value;

    if(senderName !== "") {
        if(senderEmail !== "") {
            if(problemDescription !== "") {
                $('#errorMsg').html("");
                $('#errorMsg').addClass('hide');

                $.post('/resources/controllers/generalController.php', {contactFormData: true, senderName: senderName, senderEmail: senderEmail, subject: subject, problemDescription: problemDescription}, function(returnData) {
                    var obj = jQuery.parseJSON(returnData);

                    if(obj.data_type == 1) {
                        MaterialDialog.dialog(
                            "Thank you for getting in touch!",
                            {
                                title:"Hey " + senderName,
                                modalType:"modal", 
                                buttons:{
                                    close:{
                                        className:"waves-effect waves-blue btn-flat",
                                        text:"Stay here"
                                    },
                                    confirm:{
                                        className:"modal-close waves-effect waves-blue btn-flat",
                                        text:"Go home",
                                        callback:function(){
                                            window.location.href = "/home";
                                        }
                                    }
                                    
                                }
                            }
                        );
                    } else {
                        $('#errorMsg').html(obj.data_value);
                        $('#errorMsg').removeClass('hide');
                    }
                });
            } else {
                $('#errorMsg').html("Please enter a detailed description of your problem");
                $('#errorMsg').removeClass('hide');  
            }
        } else {
            $('#errorMsg').html("Please enter your email address");
            $('#errorMsg').removeClass('hide');  
        }
    } else {
        $('#errorMsg').html("Please enter your name");
        $('#errorMsg').removeClass('hide');  
    }

    $('.contactSpinner').addClass('hide');
});