$(document).ready(function(){
    /* modal trigger */
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
    
    $(document).on('click', '#regBtn', function() {
        var email = $('#regForm').find('.email');
        var username = $('#regForm').find('.username');
        var password = $('#regForm').find('.password');
        var passwordConfirm = $('#regForm').find('.passwordConfirm');

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
        } else if ((username["0"].value.length > 16)) {
            $('.modalErrorMsg').removeClass('hide');
            $('.modalErrorMsg').find('p').html('Username must be lower than 16 character');
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

            $.post('resources/controllers/userController.php', {regEmail: email["0"].value, regUsername: username["0"].value, regPassword: password["0"].value}, function(returnData) {

                var obj = jQuery.parseJSON(returnData);
                if(obj.data_type == 0) {
                    $('.modalErrorMsg').removeClass('hide');
                    $('.modalErrorMsg').find('p').html(obj.data_value);
                } else {
                    $('#signup').modal('close');
                    $('#successfulRegistrationModal').modal('open');
                }
            });
        }
    });

    $(document).on('click', '#logBtn', function() {
        var loginID = $('#logForm').find('.loginID');
        var password = $('#logForm').find('.password');
        var rememberMe = $('#logForm').find('#modalRememberMe');

        userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.modalErrorMsg', 'modal');
    });

    $(document).on('click', '#sideBarLogBtn', function() {
        var loginID = $('#sideBarLogForm').find('.loginID');
        var password = $('#sideBarLogForm').find('.password');
        var rememberMe = $('#sideBarLogForm').find('#rememberMe');

        userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.sideBarErrorMsg', 'sideBar');
    });

    $(document).on('click', '#verifyLogBtn', function() {
        var loginID = $('#verifyLogForm').find('.loginID');
        var password = $('#verifyLogForm').find('.password');
        var rememberMe = $('#verifyLogForm').find('#rememberMe');

        userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.verifyErrorMsg', 'verifyPage');
    });

    /* login function what we call if user click on Login button in the sidebar or in the login modal */
    function userLogin(loginID, password, rememberMe, errorMsg, location) {

        if (!loginID) {
            $(errorMsg).removeClass('hide');
            $(errorMsg).find('p').html('Please enter your email or username');
        } else if (!password) {
            $(errorMsg).removeClass('hide');
            $(errorMsg).find('p').html('Please fill the password field');
        } else {
            $(errorMsg).find('p').html('');
            $(errorMsg).addClass('hide');
            $.post('/resources/controllers/userController.php', {logID: loginID, logPassword: password, rememberMe: rememberMe}, function(returnData) {

                var obj = jQuery.parseJSON(returnData);

                if(obj.data_type == 0) {
                    $(errorMsg).removeClass('hide');
                    $(errorMsg).find('p').html(obj.data_value);
                } else {
                    if(location === 'modal') {
                        $('#login').modal('close');
                        window.location.assign(window.location.href);
                    } else if(location === 'sideBar') {
                        window.location.assign(window.location.href);
                    } else {
                        window.location.assign('/home');
                    }
                }
            });
        }
    }

    $(document).on('click', '#logOutBtn', function() {
        $.post('/resources/controllers/logoutController.php', {logout: true}, function(returnData) {
            window.location.assign('/home');
        });
    });
});