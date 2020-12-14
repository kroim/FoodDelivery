$(function () {
    initialize();
});
var autocomplete;
var place;
var component_form = {
    'street_number': 'short_name',
    'route': 'long_name',
    'locality': 'long_name',
    'administrative_area_level_1': 'short_name',
    'country': 'long_name',
    'postal_code': 'short_name'
};
function initialize() {
    var options = {
        types: ['(cities)']
    };
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('search_location'), options);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        place = autocomplete.getPlace();
        fillInAddress();
    });
}

function fillInAddress() {
    var search_city, search_state, search_country;
    for (var j = 0; j < place.address_components.length; j++) {
        var att = place.address_components[j].types[0];
        if (att === 'country') search_country = place.address_components[j]['long_name'];
        if (att === 'administrative_area_level_1') search_state = place.address_components[j]['short_name'];
        if (att === 'locality') search_city = place.address_components[j]['long_name'];
    }
    if (!search_city || !search_state || !search_country) return;
    localStorage.setItem('search_location', document.getElementById('search_location').value);
    setTimeout(function () {
        location.href = '/search/' + search_city + '_' + search_state + '_' + search_country
    }, 100)
}
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
            autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
        });
    }
}
function onSearch() {
    fillInAddress();
}