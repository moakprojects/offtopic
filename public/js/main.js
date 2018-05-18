$(document).ready(function(){
    /* initialize modals */
    $('.modal').modal();
    
    /* call registration process when user click on registration button */
    $(document).on('click', '#regBtn', function() {
        registration();
    });

    /* call registration process when user press enter button on password confirm field */
    $('#registrationRePassword').keypress(function(e) {
        if(e.which == 13) {
            registration();
        }
    });

    /* call registration process when user press enter button on password field */
    $('#registrationPassword').keypress(function(e) {
        if(e.which == 13) {
            registration();
        }
    });

    function registration() {
        
        var email = $('#regForm').find('.email');
        var username = $('#regForm').find('.username');
        var password = $('#regForm').find('.password');
        var passwordConfirm = $('#regForm').find('.passwordConfirm');

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
    }

    /* ask data from modal for the login */
    $(document).on('click', '#logBtn', function() {
        var loginID = $('#logForm').find('.loginID');
        var password = $('#logForm').find('.password');
        var rememberMe = $('#logForm').find('#modalRememberMe');

        userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.modalErrorMsg', 'modal');
    });

    //try login when user press enter button
    $('#modalPassword').keypress(function(e) {
        if(e.which == 13) {
            var loginID = $('#logForm').find('.loginID');
            var password = $('#logForm').find('.password');
            var rememberMe = $('#logForm').find('#modalRememberMe');

            userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.modalErrorMsg', 'modal');
        }
    });

    /* ask data from sidebar login form for the login */
    $(document).on('click', '#sideBarLogBtn', function() {
        var loginID = $('#sideBarLogForm').find('.loginID');
        var password = $('#sideBarLogForm').find('.password');
        var rememberMe = $('#sideBarLogForm').find('#rememberMe');

        userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.sideBarErrorMsg', 'sideBar');
    });

    //try login when user press enter button
    $('#sideBarPassword').keypress(function(e) {
        if(e.which == 13) {
            var loginID = $('#sideBarLogForm').find('.loginID');
            var password = $('#sideBarLogForm').find('.password');
            var rememberMe = $('#sideBarLogForm').find('#rememberMe');

            userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.sideBarErrorMsg', 'sideBar');
        }
    });

    /* ask data from verify page login form for the login */
    $(document).on('click', '#verifyLogBtn', function() {
        var loginID = $('#verifyLogForm').find('.loginID');
        var password = $('#verifyLogForm').find('.password');
        var rememberMe = $('#verifyLogForm').find('#rememberMe');

        userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.verifyErrorMsg', 'verifyPage');
    });

    //try login when user press enter button
    $('#verifyPagePassword').keypress(function(e) {
        if(e.which == 13) {
            var loginID = $('#verifyLogForm').find('.loginID');
            var password = $('#verifyLogForm').find('.password');
            var rememberMe = $('#verifyLogForm').find('#rememberMe');

            userLogin(loginID["0"].value, password["0"].value, rememberMe["0"].checked, '.verifyErrorMsg', 'verifyPage');
        }
    });

    /* login function what we call if user click on Login button in the sidebar or in the login modal or in the verify page */
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

    /* send request for logout */
    $(document).on('click', '#logOutBtn', function() {
        $.post('/resources/controllers/logoutController.php', {logout: true}, function(returnData) {
            window.location.assign('/home');
        });
    });
});

function adminDelition(page, section, type, ID) {
    var reloadSection = '/' + page + ' ' + section;
    MaterialDialog.dialog(
      "Are you sure you want to delete this " + type + "?",
      {
          title:"Delete " + type,
          modalType:"modal", 
          buttons:{
              close:{
                  className:"waves-effect waves-red btn-flat",
                  text:"No"
              },
              confirm:{
                  className:"modal-close waves-effect waves-green btn-flat",
                  text:"Yes",
                  callback:function(){
                        if(type === "category") {
                            $.post('/resources/controllers/categoryController.php', {deleteCategory: true, categoryID: ID}, function(data) {

                                var obj = jQuery.parseJSON(data);
                                if(obj.data_type === 1) {
                                    if(page=="home") {
                                        $(section).load(reloadSection, function() {
                                            Materialize.toast(obj.data_value, 4000);
                                            $('#topics').load('/home/ #topics', function() {
                                                $('#stickyPosts').load('/home/ #stickyPosts', function() {
                                                    $('#posts').load('/home/ #posts', '');
                                                });
                                            });
                                        });
                                    } else {
                                        $(section).load(reloadSection, function() {
                                            Materialize.toast(obj.data_value, 4000);
                                        });
                                    }
                                } else {
                                    Materialize.toast(obj.data_value, 4000);
                                }
                            });
                        } else if (type === "topic") {
                            $.post('/resources/controllers/topicController.php', {deleteTopic: true, topicID: ID}, function(data) {

                                var obj = jQuery.parseJSON(data);
                                if(obj.data_type === 1) {
                                    if(page=="home") {
                                        $(section).load(reloadSection, function() {
                                            Materialize.toast(obj.data_value, 4000);
                                            $("#posts").load('/home/ #posts', function() {
                                                $("#stickyPosts").load('/home/ #stickyPosts', '');
                                            });
                                        });
                                    } else {
                                        $(section).load(reloadSection, function() {
                                            Materialize.toast(obj.data_value, 4000);
                                        });
                                    }
                                } else {
                                    Materialize.toast(obj.data_value, 4000);
                                }
                            });
                        } else if (type === "post" || type === "sticky") {
                            $.post('/resources/controllers/discussionController.php', {deletePost: true, type: type, postID: ID}, function(data) {

                                var obj = jQuery.parseJSON(data);
                                if(obj.data_type === 1) {
                                  $(section).load(reloadSection, function() {
                                      Materialize.toast(obj.data_value, 4000);
                                  });
                                } else {
                                    Materialize.toast(obj.data_value, 4000);
                                }
                            }); 
                        }
                  }
              }
              
          }
      }
  );
}

function adminModification(type, ID) {
    if(type === "category") {
        $.post('/resources/controllers/categoryController.php', {modifyCategory: true, categoryID: ID}, function(data) {

            var obj = jQuery.parseJSON(data);
            if(obj.data_type === 1) {
                window.location.href = "/modify-category";
            } else {
                Materialize.toast(obj.data_value, 4000);
            }
        }); 
    } else if(type === "topic") {

    } else if (type === "post" || type === "sticky") {
        $.post('/resources/controllers/discussionController.php', {modifyPost: true, type: type, postID: ID}, function(data) {

            var obj = jQuery.parseJSON(data);
            if(obj.data_type === 1) {
                if(type === "sticky") {
                    window.location.href = "/modify-sticky-post";
                }
            } else {
                Materialize.toast(obj.data_value, 4000);
            }
        });
    }
}