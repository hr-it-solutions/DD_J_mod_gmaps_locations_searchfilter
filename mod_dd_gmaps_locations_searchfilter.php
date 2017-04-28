<?php
/**
 * @package    DD_GMaps_Locations_Searchfilter
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

// Include the functions only once
JLoader::register('ModDD_GMaps_Locations_SearchFilter_Helper', __DIR__ . '/helper.php');

$componentParams = JComponentHelper::getParams('com_dd_gmaps_locations');

$doc = JFactory::getDocument();

$google_PlacesAPI = 'js?&libraries=places&v=3';
$google_PlacesAPI_Key = '&key=' . $componentParams->get('google_api_key_js_places');

if (!ModDD_GMaps_Locations_SearchFilter_Helper::isset_Script($doc->_scripts, $google_PlacesAPI))
{
	$doc->addScript('https://maps.google.com/maps/api/' . $google_PlacesAPI . '&key=' . $google_PlacesAPI_Key);
}

$doc->addStyleSheet(JUri::base() . 'media/mod_dd_gmaps_locations_searchfilter/css/dd_gmaps_locations_searchfilter.min.css');
$doc->addScript(JUri::base() . 'media/mod_dd_gmaps_locations_searchfilter/js/dd_gmaps_locations_searchfilter.min.js');

require JModuleHelper::getLayoutPath('mod_dd_gmaps_locations_searchfilter', $params->get('layout', 'default'));
