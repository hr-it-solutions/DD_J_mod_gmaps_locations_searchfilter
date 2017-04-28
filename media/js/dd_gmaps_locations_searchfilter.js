/**
 * @package    DD_GMaps_Locations_Searchfilter
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

function submitDD_GMaps_Form() {
    setTimeout(function(){
        if(document.getElementById("dd_input_location_search").value != ""){
            geocoder = new google.maps.Geocoder();
            var address = document.getElementById("dd_input_location_search").value;
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById("dd_input_geolocate").value = "";
                    document.getElementById("locationLatLng").value = results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
                }
            });
        }
    }, 300);

    jQuery('#dd_searchfilter-ajaxloader').css({"display":"inline"});
    setTimeout(function(){
        jQuery('#dd_gmaps_locations_searchfilter_form ').submit();
    }, 800);
}

var initAutoCompleteListener = function initAutoCompleteListener() {
    // Adds auto complete input and input to LatLng function after google suggest list selected
    var input = document.getElementById("dd_input_location_search");
    var options = {types:[]};
    var autocomplete = new google.maps.places.Autocomplete(input, options);

    google.maps.event.addListener(autocomplete, "place_changed", function() {
        var pl = autocomplete.getPlace();
        jQuery("#locationLatLng").val(pl.geometry.location.lat() + "," + pl.geometry.location.lng()) ;

        document.getElementById("dd_input_geolocate").value = "";

        jQuery('#dd_searchfilter-ajaxloader').css({"display":"inline"});
        setTimeout(function(){
            jQuery('#dd_gmaps_locations_searchfilter_form ').submit();
        }, 400);
    });
};

function clearDD_GMaps_Form() {
    jQuery(':input','#dd_gmaps_locations_searchfilter_form')
        .not(':button, :submit, :reset')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

    submitDD_GMaps_Form();
}

function showPosition(position) {
    document.getElementById("dd_input_geolocate").value="locate";
    document.getElementById("locationLatLng").value=position.coords.latitude+','+position.coords.longitude;
    document.getElementById("dd_gmaps_locations_searchfilter_form").submit();
}
