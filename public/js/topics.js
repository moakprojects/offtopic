$(document).ready(function(){
    $('.tooltipped').tooltip({delay: 50});

    $(document).on('click', '.categoryLikeButton', function() {
        $.post('/resources/controllers/categoryController.php', {}, function(returnData) {

        });
    });
});