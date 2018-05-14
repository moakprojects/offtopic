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
