$(document).ready(function(){
    /* modal trigger */
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();

    $(document).on('click', '#regBtn', function() {
        var email = $('#regForm').find('.email');
        var username = $('#regForm').find('.username');
        var password = $('#regForm').find('.password');
        var passwordConfirm = $('#regForm').find('.passwordConfirm');

        console.log("password", password);
        console.log("passwordHossz", password["0"].value.length);

        //var emailRegex = new RegExp("/^[a-zA-Z0-9_-]+([.][a-zA-Z0-9]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,4}$/");
        var EMAIL_REGEXP = new RegExp('^[a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,15})$', 'i');

        if(!email["0"].value) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('Please fill the email field');
        } else if (!EMAIL_REGEXP.test(email["0"].value)) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('Please enter valid email');
        } else if (!username["0"].value) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('Please fill the username field');
        } else if (!password["0"].value) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('Please fill the password field');
        } else if (!(password["0"].value.length > 8)) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('Password must be at least 9 character');
        } else if (!passwordConfirm["0"].value) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('Please fill the password confirmation field');
        } else if (password["0"].value !== passwordConfirm["0"].value) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('The two password field must be match');
        } else {
            $('.modalErrorMsg').find('p').html('');
            $('.modalErrorMsg').addClass('hide');
            $.post('database/userAccountFeatures.php', {email: email["0"].value, username: username["0"].value, password: password["0"].value}, function(returnData) {
                var obj = jQuery.parseJSON(returnData);
					
                if(obj.data_type == 0) {
                    $('.modalErrorMsg').removeClass('hide');
                    $('.modalErrorMsg').find('p').html(obj.data_value);
                } else {
                   
                }
            });
        }
    });
});