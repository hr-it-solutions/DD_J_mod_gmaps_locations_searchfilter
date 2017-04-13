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
                <label for="dd_input_location_search" class="element-invisible">
                    <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_LOCATIONSEARCH'); ?>
                </label>
                <input type="text" name="location_search" id="dd_input_location_search" size="50"
                       value="<?php echo htmlspecialchars($input->get("location_search", "", "STRING"), ENT_QUOTES, 'UTF-8'); ?>"
                       placeholder="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_LOCATIONSEARCH'); ?>"
                       autocomplete="off">
                <input type="hidden" name="locationLatLng" id="locationLatLng"
                       value="<?php echo htmlspecialchars($input->get("locationLatLng", 0, "STRING"), ENT_QUOTES, 'UTF-8'); ?>">
                <a class="dd_geolocate" onclick="useGeocode()" href="javascript:void(0)" rel="nofollow"
                   title="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_GEOLOCATE'); ?>"
                   data-original-title="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_GEOLOCATE'); ?>"></a>
                <input id="dd_input_geolocate" type="hidden" name="geolocate"
                       value="<?php echo htmlspecialchars($input->get("geolocate", "", "STRING"), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="dd_input_fulltext_search" class="element-invisible">
                    <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_FULLTEXTSEARCH'); ?>
                </label>
                <input type="text" name="fulltext_search" id="dd_input_fulltext_search"
                       placeholder="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_FULLTEXTSEARCH'); ?>"
                       value="<?php echo htmlspecialchars($input->get("fulltext_search","","STRING"),ENT_QUOTES,'UTF-8'); ?>"
                       title="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_FULLTEXTSEARCH'); ?>">
            </div>
            <div class="btn-group pull-left">
                <button class="btn hasTooltip" type="submit" id="dd_gmaps_submit" data-original-title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"></button>
                <button class="btn hasTooltip" type="button" id="dd_gmaps_reset" data-original-title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"></button>
            </div>
            <div class="btn-group pull-left">
                <label for="dd_input_category_filter" class="element-invisible">
                    <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_CATEGORYFILTER'); ?>
                </label>
                <select name="category_filter" id="dd_input_category_filter" class="input-medium">
                        <option value="">
                            <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_SELECT_CATEGORY'); ?>
                        </option>
                    <?php foreach (ModDD_GMaps_Locations_SearchFilter_Helper::getCategories() as $category): ?>
                        <option value="<?php echo $category['catid']; ?>"
                            <?php echo $category['selected'] == 'selected' ? 'selected' : ''; ?>>
                            <?php echo $category['category_title']?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
	</form>
    <div id="dd_searchfilter-ajaxloader" style="display:none;">
        <div class="inner-loading"></div>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        initAutoCompleteListener();
    });

    jQuery('#dd_gmaps_submit ').bind('click', function(){
        submitDD_GMaps_Form();
    });
    jQuery('#dd_input_category_filter').bind('change', function () {
        submitDD_GMaps_Form();
    });
    jQuery('#dd_gmaps_reset').bind('click', function () {
        clearDD_GMaps_Form();
    });

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

    function clearDD_GMaps_Form() {
        jQuery(':input','#dd_gmaps_locations_searchfilter_form')
            .not(':button, :submit, :reset')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');

        submitDD_GMaps_Form();
    }

    function initAutoCompleteListener() {
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
    }

    jQuery('#dd_input_location_search').bind('keypress keydown keyup paste', function(e){
        var submit = false;
        if(e.keyCode == 13) {
            if(submit == false){
                e.preventDefault();
                submit = true;
            }
            submitDD_GMaps_Form();
        }
    });

    // Empty value on focus
    jQuery('#dd_input_location_search').focus(function(){
        var dd_input_location_search = jQuery('#dd_input_location_search');
        dd_input_location_search.val("");
        dd_input_location_search.attr("placeholder","<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_LOCATIONSEARCH'); ?>");
        jQuery('#locationLatLng').val("");
    });

    // Geolocate function and associated events
    function useGeocode(){
        document.getElementById("dd_input_location_search").value = "";
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_GEOLOCATION_IS_NOT_SUPPORTED'); ?>";
        }
    }
    function showPosition(position) {
        document.getElementById("dd_input_geolocate").value="locate";
        document.getElementById("locationLatLng").value=position.coords.latitude+','+position.coords.longitude;
        document.getElementById("dd_gmaps_locations_searchfilter_form").submit();
    }

    // Auto Select on Enter Function (Great feature, but if it makes problems, script could also run without this block)
    var pac_input = document.getElementById('dd_input_location_search');
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

    // Geolocate Fix! (Set location only if gelocate (Delay needed!)
	<?php
	if($input->get("locationLatLng", 0, "STRING")): ?>
    setTimeout(function(){ // Show delay till location is set
        jQuery('#dd_searchfilter-ajaxloader').show();
    }, 100);
    setTimeout(function(){ // Set location
        jQuery("#locationLatLng").val('<?php echo htmlspecialchars($input->get("locationLatLng",0,"STRING"),ENT_QUOTES,'UTF-8'); ?>') ;
    }, 200);
    setTimeout(function(){ // Remove delay
        jQuery('#dd_searchfilter-ajaxloader').hide();
    }, 1000);
	<?php endif; ?>

</script>
