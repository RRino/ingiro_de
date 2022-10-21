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
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Database\ParameterType;

/**
 * Model class for Evento view.
 *
 * @since  1.0.0
 */
class EventoModel extends ItemModel
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_calevent.evento';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Evento. Calling getState in this method will result in recursion.
	 *
	 * @since   1.0.0
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('evento.id', $pk);

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Method to get evento data.
	 *
	 * @param   integer  $pk  The id of the evento.
	 *
	 * @return  object|boolean  Menu item data object on success, boolean false
	 */
	public function getItem($pk = null)
	{
		$user = Factory::getApplication()->getIdentity();

		$pk = (int) ($pk ?: $this->getState('evento.id'));

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
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
								'a.id, a.title, a.alias, a.published, a.access, a.created, a.created_by, a.ordering, a.language, ' .
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
				
				$query->where($db->quoteName('a.id') . ' = :pk')
					->bind(':pk', $pk, ParameterType::INTEGER);

				// Filter by access level.
				if ($access = $this->getState('filter.access'))
				{
					$query->where($db->quoteName('a.access') . ' = :access');
					$query->bind(':access', $access, ParameterType::INTEGER);
				}

				$query->select($db->quoteName('ua.name', 'author_name'))
				->join(
					'LEFT',
					$db->quoteName('#__users', 'ua') . ' ON ' . $db->quoteName('ua.id') . ' = ' . $db->quoteName('a.created_by')
				);

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

				// Filter on the language.
				if ($language = $this->getState('filter.language') && Multilanguage::isEnabled())
				{
					$query->where($db->quoteName('a.language') . ' = :language');
					$query->bind(':language', $language);
				}

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new \Exception('');
				}

				$this->_item[$pk] = $data;
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go through the error handler to allow Redirect to work.
					throw $e;
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}
}
