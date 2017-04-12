<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-06
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
**/

defined ('_JEXEC') or die;

$app = JFactory::getApplication();
$input = $app->input;

// SEF form action URL
$sef_rewrite = JFactory::getConfig()->get('sef_rewrite');
$component_alias = $input->get('dd_gmaps_locations_component_alias','','STRING');
if(!$sef_rewrite)
{
	$component_alias = 'index.php?' . $component_alias;
}

?>
<div class="dd_gmaps_locations_searchfilter well">
	<form id="dd_gmaps_locations_searchfilter_form" action="<?php echo JUri::base() . $component_alias; ?>" method="post" role="search">
        <div id="filter-bar">

            <div class="filter-search btn-group pull-left">
                <label for="location_search" class="element-invisible">Location Search...</label>
                <input id="location_search" class="location_search mapsearchbox" name="location_search" type="text" size="50" value="<?php echo htmlspecialchars($input->get("location_search","","STRING"),ENT_QUOTES,'UTF-8'); ?>" placeholder="<?php if($input->get("location_search","","STRING") != ""){echo htmlspecialchars($input->get("location_search","","STRING"),ENT_QUOTES,'UTF-8');} else {echo "Location Search...";}?>" autocomplete="off" <?php if(false){ ?>autofocus<?php } ?> >
                <input id="locationLatLng" type="hidden" name="locationLatLng" value="<?php echo htmlspecialchars($input->get("locationLatLng",0,"STRING"),ENT_QUOTES,'UTF-8'); ?>">

                <a class="dd_geolocate" onclick="javascript:useGeocode()" href="javascript:void(0)" rel="nofollow" title="Standortbestimmung"></a>
                <input id="geolocate" type="hidden" name="geolocate" value="<?php echo htmlspecialchars($input->get("geolocate","STRING"),ENT_QUOTES,'UTF-8') ?>">
            </div>

            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible">Full-Text Search...</label>
                <input name="filter_search" id="filter_search" placeholder="Full-Text Search..." value="" title="Full-Text Search..." type="text">
            </div>

            <div class="btn-group pull-left">
                <button class="btn hasTooltip" type="submit" id="gmaps_submit"  title="" data-original-title="Search"><i class="icon-search"></i></button>
                <button class="btn hasTooltip" type="button" title="" onclick="document.id('filter_search').value='';this.form.submit();" data-original-title="Clear"><i class="icon-remove"></i></button>
            </div>

            <div class="btn-group pull-left hidden-phone">
                <label for="limit" class="element-invisible">Sets the maximum number of results to return.</label>
                <select id="limit" name="limit" class="inputbox input-mini" size="1" onchange="Joomla.submitform();">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20" selected="selected">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="0">All</option>
                </select>
            </div>

            <div class="btn-group pull-left">
                <label for="sortTable" class="element-invisible">Category Filter</label>
                <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value="">Category Filter</option>
                </select>
            </div>
        </div>
	</form>
</div>

<script>
    jQuery(document).ready(function () {
        initAutoCompleteListener();
    });

    function submitDD_GMaps_Form() {
        setTimeout(function(){
            if(document.getElementById("location_search").value != ""){
                geocoder = new google.maps.Geocoder();
                var address = document.getElementById("location_search").value;
                geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        document.getElementById("geolocate").value = "";
                        document.getElementById("locationLatLng").value = results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
                    }
                });
            }
        }, 300);
        
        jQuery('#dd-fancybox').css({"display":"inline"});
        setTimeout(function(){
            jQuery('#dd_gmaps_locations_searchfilter_form ').submit();
        }, 800);
    }

    function initAutoCompleteListener() {
        // Adds auto complete input and input to LatLng function after google suggest list selected
        var input = document.getElementById("location_search");
        var options = {types:[]};
        var autocomplete = new google.maps.places.Autocomplete(input, options);

        google.maps.event.addListener(autocomplete, "place_changed", function() {
            var pl = autocomplete.getPlace();
            jQuery("#locationLatLng").val(pl.geometry.location.lat() + "," + pl.geometry.location.lng()) ;

            document.getElementById("geolocate").value = "";
            
            jQuery('#dd-fancybox').css({"display":"inline"});
            setTimeout(function(){
                jQuery('#dd_gmaps_locations_searchfilter_form ').submit();
            }, 400);
        });
    }

    jQuery('#location_search').bind('keypress keydown keyup paste', function(e){
        var submit = false;
        if(e.keyCode == 13) {
            if(submit == false){
                e.preventDefault();
                submit = true;
            }
            submitDD_GMaps_Form();
        }
    });
    jQuery('#gmaps_submit ').bind('click', function(){
        submitDD_GMaps_Form();
    });

    // Empty value on focus
    jQuery('.location_search').focus(function(){
        var location_search = jQuery('#location_search');
        location_search.val("");
        location_search.attr("placeholder","Wo suchen Sie?");
        jQuery('#locationLatLng').val("");
    });

    // Geolocate function and associated events
    function useGeocode(){
        document.getElementById("location_search").value = "";
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML="Geolocation is not supported by this browser.";
        }
    }
    function showPosition(position) {
        document.getElementById("geolocate").value="locate";
        document.getElementById("locationLatLng").value=position.coords.latitude+','+position.coords.longitude;
        document.getElementById("dd_gmaps_locations_searchfilter_form ").submit();
    }
</script>
<script>
    // Auto Select on Enter Function (Great feature, but if it makes problems, script could also run without this block)
    var pac_input = document.getElementById('location_search');
    (function pacSelectFirst(input) {
        // store the original event binding function
        var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

        function addEventListenerWrapper(type, listener) {
            // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
            // and then trigger the original listener.
            if (type == "keydown") {
                var orig_listener = listener;
                listener = function(event) {
                    var suggestion_selected = jQuery(".pac-item-selected").length > 0;
                    if (event.which == 13 && !suggestion_selected) {
                        var simulated_downarrow = jQuery.Event("keydown", {
                            keyCode: 40,
                            which: 40
                        });
                        orig_listener.apply(input, [simulated_downarrow]);
                    }

                    orig_listener.apply(input, [event]);
                };
            }
            _addEventListener.apply(input, [type, listener]);
        }
        input.addEventListener = addEventListenerWrapper;
        input.attachEvent = addEventListenerWrapper;

        var autocomplete = new google.maps.places.Autocomplete(input);

    })(pac_input);
</script>

<script>
    // Geolocate Fix! (Set location only if gelocate (Delay needed!)
	<?php
	if($input->get("locationLatLng",0,"STRING")){ ?>

    setTimeout(function(){ // Show delay till location is set
        jQuery('#dd-fancybox').show();
    }, 100);
    setTimeout(function(){ // Set location
        jQuery("#locationLatLng").val('<?php echo htmlspecialchars($input->get("locationLatLng",0,"STRING"),ENT_QUOTES,'UTF-8'); ?>') ;
    }, 200);
    setTimeout(function(){ // Remove delay
        jQuery('#dd-fancybox').hide();
    }, 1000);

	<?php }	?>
</script>
