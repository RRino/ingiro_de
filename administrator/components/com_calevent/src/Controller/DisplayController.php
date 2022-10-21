<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;

/**
 * Component Display Controller
 *
 * @since  1.0.0
 */
class DisplayController extends BaseController
{
	/**
	 * The default view.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $default_view = 'notes';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  BaseController|boolean  This object to support chaining.
	 *
	 * @since   1.0.0
	 */
	public function display($cachable = false, $urlparams = [])
	{
		$view   = $this->input->get('view', 'notes');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id', 0);	

		// Check for edit form.
		if ($view === 'note' && $layout === 'edit' && !$this->checkEditId('com_calevent.edit.note', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			$this->setRedirect(Route::_('index.php?option=com_calevent&view=notes', false));

			return false;
		}

		return parent::display();
	}
}
