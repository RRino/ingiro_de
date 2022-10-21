<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Administrator\Service\HTML;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Factory;

/**
 * Note class for HTML Service class Icon.
 *
 * @since	1.0.0
 */
class Icon
{
	/**
	 * Application instance.
	 *
	 * @var		CMSApplication
	 *
	 * @since	1.0.0
	 */
	private $application = null;

	/**
	 * Constructor function for the Icon class.
	 *
	 * @since	1.0.0
	 */
	public function __construct()
	{
		$application = Factory::getApplication();
	}

	/**
	 * Render an icon.
	 *
	 * @param	string	$name	The icon name.
	 *
	 * @return	string	The HTML string for the icon.
	 *
	 * @since	1.0.0
	 */
	public function render(string $name, string $modifier = '') : string
	{
		return '<span class="icon-' . $name . ($modifier ? ' ' . $modifier : '') . '"></span>';
	}
}