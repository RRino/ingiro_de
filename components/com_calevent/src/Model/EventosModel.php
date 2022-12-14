<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Site\Model;

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

/**
 * This models supports retrieving lists of eventos.
 *
 * @since  1.0.0
 */
class EventosModel extends ListModel
{
	/**
	 * Constructor method.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     \JController
	 *
	 * @since   1.0.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'language', 'a.language',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Evento. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		$app = Factory::getApplication();

		// List state information
		$value = $app->input->get('limit', $app->get('list_limit', 0), 'uint');
		$this->setState('list.limit', $value);

		$offset = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $offset);

		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.published', 1);
		$this->setState('filter.access', true);

		$orderCol = $app->input->get('filter_order', 'a.ordering');

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'a.ordering';
		}

		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->input->get('filter_order_Dir', 'ASC');

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		$this->setState('list.direction', $listOrder);

		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.0.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.language');
		$id .= ':' . $this->getState('filter.level');

		return parent::getStoreId($id);
	}

	/**
	 * Get the master query for retrieving a list of eventos.
	 *
	 * @return  \JDatabaseQuery
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		$container = Factory::getContainer();
		$app = Factory::getApplication();
		$db = $container->get('DatabaseDriver');
		$query = $db->getQuery(true);
		$user = $app->getIdentity();

		$query->select(
			$db->quoteName(
				explode(
					', ',
					$this->getState(
						'list.select',
						'a.id, a.title, a.alias, a.description, a.published, a.access, a.created, a.created_by, a.ordering, a.language, ' .
						'a.checked_out, a.checked_out_time'
					)
				)
			)
		);

		$query->from($db->quoteName('#__calevent_eventos', 'a'));

		$query->select($db->quoteName('l.title', 'language_title'))
			->select($db->quoteName('l.image', 'language_image'))
			->join(
				'LEFT',
				$db->quoteName('#__languages', 'l') . ' ON ' . $db->quoteName('l.lang_code') . ' = ' . $db->quoteName('a.language')
			);

		// Join over the users for the checked out user.
		$query->select($db->quoteName('uc.name', 'editor'))
			->join(
				'LEFT',
				$db->quoteName('#__users', 'uc') . ' ON ' . $db->quoteName('uc.id') . ' = ' . $db->quoteName('a.checked_out')
			);

		// Join over the asset groups.
		$query->select($db->quoteName('ag.title', 'access_level'))
			->join(
				'LEFT',
				$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
			);

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where($db->quoteName('a.access') . ' = :access');
			$query->bind(':access', $access, ParameterType::INTEGER);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$query->whereIn($db->quoteName('a.access'), $user->getAuthorisedViewLevels());
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where($db->quoteName('a.published') . ' = :published');
			$query->bind(':published', $published, ParameterType::INTEGER);
		}
		elseif ($published === '')
		{
			$query->where('(' . $db->quoteName('a.published') . ' = 0 OR ' . $db->quoteName('a.published') . ' = 1)');
		}

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$search = substr($search, 3);
				$query->where($db->quoteName('a.id') . ' = :id');
				$query->bind(':id', $search, ParameterType::INTEGER);
			}
			else
			{
				$search = '%' . trim($search) . '%';
				$query->where(
					'(' . $db->quoteName('a.name') . ' LIKE :name OR ' . $db->quoteName('a.alias') . ' LIKE :alias)'
				);
				$query->bind(':name', $search);
				$query->bind(':alias', $search);
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where($db->quoteName('a.language') . ' = :language');
			$query->bind(':language', $language);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.title');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol === 'a.ordering')
		{
			$orderCol = $db->quoteName('a.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to get a list of eventos.
	 *
	 * Overridden to inject convert the attribs field into a Registry object.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 * @since   1.0.0
	 */
	public function getItems()
	{
		return parent::getItems();
	}
}
