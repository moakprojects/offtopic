// send data to modify category table when user add it to favourite
function likeCategory(userId, categoryId, action) {
    $.post('/resources/controllers/categoryController.php', {userId: userId, favouriteSelectedCategory: categoryId, action: action}, function(data) {
      var obj = jQuery.parseJSON(data);
      if(obj.data_type === 1) {
        if(obj.data_value === "added") {
          $('#categoryLikeButtonContainer').load('/categories/' + categoryId + ' #categoryLikeButtonContainer', function() {
            $('.categoryLikeButton').children().removeClass('far').addClass('fas');
            $('#categorySideBarContainer').load('/categories/' + categoryId + ' #categorySideBarContainer', '');
          });
          
        } else {
          $('#categoryLikeButtonContainer').load('/categories/' + categoryId + ' #categoryLikeButtonContainer', function() {
            $('.categoryLikeButton').children().removeClass('fas').addClass('far');
            $('#categorySideBarContainer').load('/categories/' + categoryId + ' #categorySideBarContainer', '');
          });
          
        }
  
        
      }
    });
}