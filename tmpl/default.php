<?php
/**
 * @package    DD_GMaps_Locations_Searchfilter
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2018 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

JHtml::_('stylesheet', 'mod_dd_gmaps_locations_searchfilter/dd_gmaps_locations_searchfilter.min.css', array('version' => 'auto', 'relative' => true));

JHtml::_('jQuery.Framework');

$app = JFactory::getApplication();
$input = $app->input;
?>
<div class="dd_gmaps_locations_searchfilter well">
	<form id="dd_gmaps_locations_searchfilter_form" action="<?php echo JRoute::_('index.php?option=com_dd_gmaps_locations&view=locations'); ?>" method="post" role="search">
        <div id="filter-bar">
            <div class="filter-search btn-group pull-left">
                <label for="dd_input_location_search" class="element-invisible">
                    <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_LOCATIONSEARCH'); ?>
                </label>
                <input type="<?php echo $params->get('show_locationsearch') ? 'text' : 'hidden'; ?>" name="location_search" id="dd_input_location_search" size="50"
                       value="<?php echo htmlspecialchars($input->get("location_search", "", "STRING"), ENT_QUOTES, 'UTF-8'); ?>"
                       placeholder="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_LOCATIONSEARCH'); ?>"
                       autocomplete="off">
                <input type="hidden" name="locationLatLng" id="locationLatLng"
                       value="<?php echo htmlspecialchars($input->get("locationLatLng", 0, "STRING"), ENT_QUOTES, 'UTF-8'); ?>">
	            <?php
                // Show geolocate optione
                if($params->get('show_locationsearch') && $params->get('show_geolocalisation')): ?>
                <a class="dd_geolocate" onclick="useGeocode()" href="javascript:void(0)" rel="nofollow"
                   title="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_GEOLOCATE'); ?>"
                   data-original-title="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_GEOLOCATE'); ?>"></a>
	            <?php endif; ?>
                <input id="dd_input_geolocate" type="hidden" name="geolocate"
                       value="<?php echo htmlspecialchars($input->get("geolocate", "", "STRING"), ENT_QUOTES, 'UTF-8') ?>">
            </div>
	        <?php
	        // Show fulltext search
	        if($params->get('show_fulltextsearch')): ?>
            <div class="filter-search btn-group pull-left">
                <label for="dd_input_fulltext_search" class="element-invisible">
                    <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_FULLTEXTSEARCH'); ?>
                </label>
                <input type="text" name="fulltext_search" id="dd_input_fulltext_search"
                       placeholder="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_FULLTEXTSEARCH'); ?>"
                       value="<?php echo htmlspecialchars($input->get("fulltext_search","","STRING"),ENT_QUOTES,'UTF-8'); ?>"
                       title="<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_FULLTEXTSEARCH'); ?>">
            </div>
	        <?php endif; ?>
            <div class="btn-group pull-left">
                <button class="btn hasTooltip" type="submit" id="dd_gmaps_submit" data-original-title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"></button>
                <button class="btn hasTooltip" type="button" id="dd_gmaps_reset" data-original-title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"></button>
            </div>
	        <?php
	        // Show category_filter
	        if($params->get('show_categoryfilter')): ?>
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
	        <?php endif; ?>
	        <?php
	        // Show federalstate_filter
	        if($params->get('show_federalstatefilter')): ?>
                <div class="btn-group pull-left">
                    <label for="dd_input_federalstate_filter" class="element-invisible">
				        <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_FEDERALSTATEFILTER'); ?>
                    </label>
                    <select name="federalstate_filter" id="dd_input_federalstate_filter" class="input-medium">
                        <option value="">
					        <?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_SELECT_FEDERALSTATEFILTER'); ?>
                        </option>
				        <?php foreach (ModDD_GMaps_Locations_SearchFilter_Helper::getFederalStates() as $federalstate): ?>
                            <option value="<?php echo $federalstate['federalstate']; ?>"
						        <?php echo $federalstate['selected'] == 'selected' ? 'selected' : ''; ?>>
						        <?php echo $federalstate['federalstate']?>
                            </option>
				        <?php endforeach;?>
                    </select>
                </div>
	        <?php endif; ?>
        </div>
	</form>
    <div id="dd_searchfilter-ajaxloader" style="display:none;">
        <div class="inner-loading"></div>
    </div>
</div>
<script>

var dd_input_location_search = jQuery('#dd_input_location_search');

jQuery(function () {
    initAutoCompleteListener();
});

jQuery('#dd_gmaps_submit ').bind('click', function(){
    submitDD_GMaps_Form();
});
jQuery('#dd_input_category_filter').bind('change', function () {
    submitDD_GMaps_Form();
});
jQuery('#dd_input_federalstate_filter').bind('change', function () {
    submitDD_GMaps_Form();
});
jQuery('#dd_gmaps_reset').bind('click', function () {
    clearDD_GMaps_Form();
});

dd_input_location_search.bind('keypress keydown keyup paste', function(e){
    var submit = false;
    if(e.keyCode === 13) {
        if(submit === false){
            e.preventDefault();
            submit = true;
        }
        submitDD_GMaps_Form();
    }
});

// Empty value on focus
dd_input_location_search.focus(function(){
    var dd_input_location_search = jQuery('#dd_input_location_search');
    dd_input_location_search.val('');
    dd_input_location_search.attr('placeholder','<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_LOCATIONSEARCH'); ?>');
    jQuery('#locationLatLng').val('');
});

// Geolocate function and associated events
function useGeocode(){

    if (window.location.protocol !== 'https:') {
        var jmsgsHTTP = ['<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_GEOLOCATION_HTTPS_REQUIRED'); ?>'];
        Joomla.renderMessages({'info': jmsgsHTTP });
    }

    jQuery('#dd_input_location_search').val('');

    if (navigator.geolocation){
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        var jmsgsBrowser = ['<?php echo JText::_('MOD_DD_GMAPS_LOCATIONS_SEARCHFILTER_GEOLOCATION_IS_NOT_SUPPORTED'); ?>'];
        Joomla.renderMessages({'info': jmsgsBrowser });
    }
}

initAutoCompleteListener();

// Auto Select on Enter Function (Great feature, but if it makes problems, script could also run without this block)
var pac_input = document.getElementById('dd_input_location_search');
(function pacSelectFirst(input) {
    // store the original event binding function
    var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

    function addEventListenerWrapper(type, listener) {
        // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
        // and then trigger the original listener.
        if (type === 'keydown') {
            var orig_listener = listener;
            listener = function(event) {
                var suggestion_selected = jQuery('.pac-item-selected').length > 0;
                if (event.which === 13 && !suggestion_selected) {
                    var simulated_downarrow = jQuery.Event('keydown', {
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
if ($input->get('locationLatLng', 0, 'STRING')): ?>
setTimeout(function(){ // Show delay till location is set
    jQuery('#dd_searchfilter-ajaxloader').show();
}, 100);
setTimeout(function(){ // Set location
    jQuery('#locationLatLng').val('<?php echo htmlspecialchars($input->get('locationLatLng', 0, 'STRING'), ENT_QUOTES, 'UTF-8'); ?>') ;
}, 200);
setTimeout(function(){ // Remove delay
    jQuery('#dd_searchfilter-ajaxloader').hide();
}, 1000);
<?php endif; ?>
</script>
