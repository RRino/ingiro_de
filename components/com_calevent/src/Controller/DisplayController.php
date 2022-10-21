<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Site\Controller;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Content Component Controller
 *
 * @since  1.0.0
 */
class DisplayController extends BaseController
{
	/**
	 * Default view.
	 *
	 * @var		string	$default_view	The default view.
	 *
	 * @since	1.0.0
	 */
	protected $default_view = 'notes';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   boolean  $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  \Joomla\CMS\MVC\Controller\BaseController  This object to support chaining.
	 *
	 * @since   1.0.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		/** Set the default view. If no view found then set it `notes`. */
		$this->input->set('view', $this->input->get('view', 'notes'));

		parent::display($cachable, $urlparams);

		return $this;
	}
}
