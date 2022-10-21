<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */


namespace Joomla\Component\Calevent\Administrator\Extension;

\defined('JPATH_PLATFORM') or die('Platform not defined!');

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\Component\Calevent\Administrator\Service\HTML\Icon;
use Psr\Container\ContainerInterface;

/**
 * Component class for com_calevent
 *
 * @since  1.0.0
 */
class CaleventComponent extends MVCComponent implements
	BootableExtensionInterface, RouterServiceInterface
{
	use HTMLRegistryAwareTrait;
	use RouterServiceTrait;

	/**
	 * Booting the extension. This is the function to set up the environment of the extension like
	 * registering new class loaders, etc.
	 *
	 * If required, some initial set up can be done from services of the container, eg.
	 * registering HTML services.
	 *
	 * @param   ContainerInterface  $container  The container
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function boot(ContainerInterface $container)
	{
		// Registering your HTML services at the booting time of the extension.
		$this->getRegistry()->register('icomoon', new Icon);
	}
}