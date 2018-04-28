$(document).ready(function() {

  var options = {
    types: ['(cities)']
   };
  var ac = new google.maps.places.Autocomplete(document.getElementById('location'), options);
google.maps.event.addListener(ac, 'place_changed', function() {
  var place = ac.getPlace();
  console.log(place.formatted_address);
  console.log(place.url);
  console.log(place.geometry.location);
});
})