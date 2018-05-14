

    //if the admin wants to suspend the user we send the selected username for the business layer
    function suspendUser(userID) {

        $.post("/resources/controllers/userController.php", {suspendUser: true, userID: userID}, function() {
            console.log("suspend done");
        });
    }
