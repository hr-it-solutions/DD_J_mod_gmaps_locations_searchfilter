<?php
/**
 * @package    DD_GMaps_Locations_Searchfilter
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Helper for mod_dd_gmaps_locations_searchfilter
 *
 * @since  Version 1.0.0.0
 */
class ModDD_GMaps_Locations_SearchFilter_Helper
{
	/**
	 * getCategories
	 *
	 * @return  array of categories
	 *
	 * @since   Version 1.1.0.0
	 */
	public static function getCategories()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$app = JFactory::getApplication();

		// filter_catid from active menu params
		$filter_catid = $app->getMenu()->getActive()->getParams()->get('filter_catid');

		$input              = $app->input;
		$category_filter    = $input->get('category_filter', $filter_catid, 'INT');

		$select = (
			$db->qn('id') . ' AS ' . $db->qn('catid') . ', ' .
			$db->qn('title') . ' AS ' . $db->qn('category_title')
		);

		$query->select($select)
			->from($db->qn('#__categories'))
			->where($db->qn('published') . ' = 1 AND ' . $db->qn('extension') . ' = ' . $db->q('com_dd_gmaps_locations'));

		$items = $db->setQuery($query, true)->loadAssocList();

		// Set selected key
		foreach ($items as $i => $item)
		{
			$items[$i]['selected'] = '';

			if (isset($category_filter)
				&& is_numeric($category_filter)
				&& $item['catid'] == $category_filter)
			{
				$items[$i]['selected'] = 'selected';
			}
		}

		return $items;
	}


	/**
	 * getFederalStates
	 *
	 * @return  array of categories
	 *
	 * @since   Version 1.1.0.0
	 */
	public static function getFederalStates()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$app = JFactory::getApplication();

		// filter_federalstate from active menu params
		$filter_federalstate = $app->getMenu()->getActive()->getParams()->get('filter_federalstate');

		$input               = JFactory::getApplication()->input;
		$federalstate_filter = $input->get('federalstate_filter', $filter_federalstate, 'STRING');

		$query->select($db->qn('federalstate'))
			->from($db->qn('#__dd_gmaps_locations'))
			->where($db->qn('federalstate') . " <>''");

		// Filter state
		$query->where('state = 1');

		$items = $db->setQuery($query, true)->loadAssocList();

		// Set selected key
		foreach ($items as $i => $item)
		{
			$items[$i]['selected'] = '';

			if (isset($federalstate_filter)
				&& is_string($federalstate_filter)
				&& $item['federalstate'] == $federalstate_filter)
			{
				$items[$i]['selected'] = 'selected';
			}
		}

		return $items;
	}

	/**
	 * isset_Script checks if a subString src exists in script header
	 *
	 * @param   array   $doc_scripts  JFactory Document $doc->_scripts
	 * @param   string  $subString    Substring to check
	 *
	 * @return  boolean
	 *
	 * @since   Version 1.1.0.0
	 */
	public static function isset_Script($doc_scripts, $subString)
	{
		$return = false;

		foreach ($doc_scripts as $key => $value)
		{
			$pos = strpos($key, $subString);

			if ($pos === false)
			{
				$return = false;
			}
			else
			{
				// String found in key
				$return = true;
				break;
			}
		}

		return $return;
	}
}
