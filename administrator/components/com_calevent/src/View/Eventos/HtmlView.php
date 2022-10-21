<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Administrator\View\Eventos;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarFactoryInterface;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Calevent\Administrator\Helper\CaleventHelper;


class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  	array
	 *
	 * @since	1.0.0
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  	\JPagination
	 *
	 * @since	1.0.0
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  	\JObject
	 *
	 * @since	1.0.0
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  	\JForm
	 *
	 * @since	1.0.0
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  	array
	 *
	 * @since	1.0.0
	 */
	public $activeFilters;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since	1.0.0
	 */
	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		$errors = $this->get('Errors');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		$canDo = CaleventHelper::getActions('com_calevent');
		$user  = Factory::getApplication()->getIdentity();

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		ToolbarHelper::title(Text::_('COM_CALEVENT_EVENTOS_TOOLBAR_LABEL'), 'book');

		if ($canDo->get('core.create'))
		{
			$toolbar->addNew('evento.add');
		}

		if ($canDo->get('core.edit.state'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('icon-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			$childBar->publish('eventos.publish')->listCheck(true);

			$childBar->unpublish('eventos.unpublish')->listCheck(true);

			$childBar->archive('eventos.archive')->listCheck(true);

			if ($user->authorise('core.admin'))
			{
				$childBar->checkin('eventos.checkin')->listCheck(true);
			}

			if ((int) $this->state->get('filter.published') !== -2)
			{
				$childBar->trash('eventos.trash')->listCheck(true);
			}
		}

		if ((int) $this->state->get('filter.published') === -2 && $canDo->get('core.delete'))
		{
			$toolbar->delete('eventos.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}

		if ($user->authorise('core.admin', 'com_calevent') || $user->authorise('core.options', 'com_calevent'))
		{
			$toolbar->preferences('com_calevent');
		}
	}
}