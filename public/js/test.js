$(document).on('change', '#avatarImg', function() {
  var property = document.getElementById("avatarImg").files[0];

  if(!property) {
    alert("nagy fájl");
  } else {
    var form_data = new FormData();
    form_data.append("file", property);
    $.ajax({
      url: 'database/upload.php',
      method: 'POST',
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function() {
        $('.preloader-wrapper').removeClass('hide');
      },
      success: function(data) {
      }
    });
  }
});