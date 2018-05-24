$(document).ready(function() {

    /* initialize category card hover animation */
    $('.post-module').hover(function() {
        $(this).find('.description').stop().animate({
          height: "toggle",
          opacity: "toggle"
        }, 300);
      });
});