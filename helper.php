<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-06
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
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
