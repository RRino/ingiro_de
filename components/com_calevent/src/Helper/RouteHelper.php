<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Site\Helper;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\RouteHelper as CMSRouteHelper;
use Joomla\CMS\Language\Multilanguage;


/**
 * Tags Component Route Helper.
 *
 * @since  1.0.0
 */
class RouteHelper extends CMSRouteHelper
{
	/**
	 * Get note route.
	 *
	 * @param	string	$view		The singular view name.
	 * @param	int		$id			The note id.
	 * @param	string	$alias		The note alias.
	 * @param	string	$language	The language string.
	 *
	 * @return	string	The router link.
	 *
	 * @since	1.0.0
	 */
	public static function generateUrl(string $view, int $id, string $alias, string $language = '*') : string
	{
		$link = '';

		if ($id < 1)
		{
			return $link;
		}

		// Create the link
		$link = 'index.php?option=com_calevent&view=' . $view . '&id=' . $id . ':' . $alias;

		if ($language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		return $link;
	}
}
