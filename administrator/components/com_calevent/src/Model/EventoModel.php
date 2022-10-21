<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */


namespace Joomla\Component\Calevent\Administrator\Model;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\String\StringHelper;

/**
 * Model class for Category View
 *
 * @since	1.0.0
 */
class EventoModel extends AdminModel
{
	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 *
	 * @since  1.0.0
	 */
	public $typeAlias = 'com_calevent.evento';

	/**
	 * Name of the form
	 *
	 * @var string
	 *
	 * @since  1.0.0
	 */
	protected $formName = 'evento';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.0.0
	 */
	protected function canDelete($record)
	{
		if (empty($record->id) || $record->published !== -2)
		{
			return false;
		}

		return Factory::getApplication()->getIdentity()->authorise('core.delete', 'com_calevent.evento.' . (int) $record->id);
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.0.0
	 */
	protected function canEditState($record)
	{
		// Check against the category.
		if (!empty($record->id))
		{
			return Factory::getApplication()->getIdentity()->authorise('core.edit.state', 'com_calevent.evento.' . (int) $record->id);
		}

		// Default to component settings if category not known.
		return parent::canEditState($record);
	}

	/**
	 * Method to check if you can save a record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	protected function canSave($data = array(), $key = 'id')
	{
		if (empty($data[$key]))
		{
			return false;
		}

		return Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_calevent.evento.' . $data[$key]);
	}

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 *
	 * @since   1.0.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_calevent.' . $this->formName, $this->formName, array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.0.0
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_calevent.edit.evento.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('evento.id') === 0)
			{
				$data->set('id', $app->input->get('id', $app->getUserState('com_calevent.eventos.filter.id'), 'int'));
			}
		}

		$this->preprocessData('com_calevent.evento', $data);

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function save($data)
	{
		$input = Factory::getApplication()->getInput();

		// Alter the name for save as copy
		if ($input->get('task') == 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['title'] === $origTable->title)
			{
				list($title, $alias) = $this->generateUniqueTitleAlias($data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] === $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['published'] = 0;
		}

		return parent::save($data);
	}

	/**
	 * Method to change the published state of one or more records.
	 * Your can perform your own logic before publish/unpublish the record item.
	 *
	 * @param   array    $pks    A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function publish(&$pks, $value = 1)
	{
		return parent::publish($pks, $value);
	}

	/**
	 * Generate new alias & title values if duplicate alias found.
	 *
	 * @param	string	$alias	The alias string.
	 * @param	string	$title	The title string.
	 *
	 * @return	array	The updated title, alias array.
	 *
	 * @since	1.0.0
	 */
	private function generateUniqueTitleAlias(string $alias, string $title) : array
	{
		$table = $this->getTable();

		while ($table->load(['alias' => $alias]))
		{
			if ($title === $table->title)
			{
				$title = StringHelper::increment($title);
			}

			$alias = StringHelper::increment($alias, 'dash');
		}

		return [$title, $alias];
	}

	/**
	 * Prepare and sanitize the table prior to saving.
	 *
	 * @param   \Joomla\CMS\Table\Table  $table  The Table object
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function prepareTable($table)
	{
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   Table  $table  A Table object.
	 *
	 * @return  array  An array of conditions to add to ordering queries.
	 *
	 * @since   1.0.0
	 */
	protected function getReorderConditions($table)
	{
		return [];
	}

	/**
	 * Preprocess the form.
	 *
	 * @param   Form    $form   Form object.
	 * @param   object  $data   Data object.
	 * @param   string  $group  Group name.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function preprocessForm(Form $form, $data, $group = 'calevent')
	{
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.0.0
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}
}