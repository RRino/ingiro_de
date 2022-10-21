<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Administrator\View\Evento;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Calevent\Administrator\Helper\CaleventHelper;

class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  	\JForm
	 *
	 * @since   1.0.0
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  	object
	 *
	 * @since   1.0.0
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  	\JObject
	 *
	 * @since   1.0.0
	 */
	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
		// Initialize the variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if ((count($errors = $this->get('Errors'))))
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
		$app = Factory::getApplication();
		$app->getInput()->set('hidemainmenu', true);

		$user       = $app->getIdentity();
		$userId     = $user->get('id');
		$isNew      = (int) $this->item->id === 0;
		$checkedOut = !(is_null($this->item->checked_out) || $this->item->checked_out == $userId);

		// Since we don't track these assets at the item level, use the category id.
		$canDo = CaleventHelper::getActions('com_calevent');

		ToolbarHelper::title($isNew ? Text::_('COM_CALEVENT_EVENTO_NEW') : Text::_('COM_CALEVENT_EVENTO_EDIT'), 'book edit-evento');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($isNew)
			{
				ToolbarHelper::apply('evento.apply');

				ToolbarHelper::saveGroup(
					[
						['save', 'evento.save'],
						['save2new', 'evento.save2new']
					],
					'btn-success'
				);
			}

			ToolbarHelper::cancel('evento.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by === $userId);

			$toolbarButtons = [];

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				ToolbarHelper::apply('evento.apply');

				$toolbarButtons[] = ['save', 'evento.save'];

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create'))
				{
					$toolbarButtons[] = ['save2new', 'evento.save2new'];
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				$toolbarButtons[] = ['save2copy', 'evento.save2copy'];
			}

			ToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			ToolbarHelper::cancel('evento.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
