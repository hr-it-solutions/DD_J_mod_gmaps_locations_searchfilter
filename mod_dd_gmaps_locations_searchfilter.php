<?php
/**
 * @package    DD_GMaps_Locations_Searchfilter
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2018 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

// Include the functions only once
JLoader::register('ModDD_GMaps_Locations_SearchFilter_Helper', __DIR__ . '/helper.php');

$componentParams = JComponentHelper::getParams('com_dd_gmaps_locations');

$doc = JFactory::getDocument();

$Places_API = 'js?&libraries=places&v=3';
$API_Key = '&key=' . $componentParams->get('google_api_key_js_places');

if (!ModDD_GMaps_Locations_SearchFilter_Helper::isset_Script($doc->_scripts, $Places_API))
{
	JHTML::_('script', 'https://maps.google.com/maps/api/' . $Places_API . '&key=' . $API_Key, array('relative' => false));
}

JHTML::_('script', 'mod_dd_gmaps_locations_searchfilter/dd_gmaps_locations_searchfilter.min.js', array('version' => 'auto', 'relative' => true));

// Check for a custom CSS file
JHtml::_('stylesheet', 'mod_dd_gmaps_module/user.css', array('version' => 'auto', 'relative' => true));

require JModuleHelper::getLayoutPath('mod_dd_gmaps_locations_searchfilter', $params->get('layout', 'default'));
