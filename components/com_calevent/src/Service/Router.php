<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Site\Service;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\Database\DatabaseInterface;


/**
 * Routing class of com_calevent
 *
 * @since  1.0.0
 */
class Router extends RouterView
{
	/**
	 * Flag to remove IDs
	 *
	 * @var    boolean
	 *
	 * @since  1.0.0
	 */
	protected $noIDs = false;

	/**
	 * The category cache
	 *
	 * @var  array
	 *
	 * @since  1.0.0
	 */
	private $categoryCache = [];

	/**
	 * The db
	 *
	 * @var DatabaseInterface
	 *
	 * @since  1.0.0
	 */
	private $db;

	/**
	 * Content Component router constructor
	 *
	 * @param   SiteApplication           $app              The application object
	 * @param   AbstractMenu              $menu             The menu object to work with
	 */
	public function __construct(SiteApplication $app, AbstractMenu $menu)
	{
		$this->db = Factory::getContainer()->get('DatabaseDriver');
		$this->queryBuilder = $this->db->getQuery(true);

		$params = ComponentHelper::getParams('com_calevent');
		$this->noIDs = (bool) $params->get('sef_ids', 1);

		$notes = new RouterViewConfiguration('notes');
		$this->registerView($notes);
		$note = new RouterViewConfiguration('note');
		$note->setKey('id')->setParent($notes);
		$this->registerView($note);

		/** Register your other other views here */

		
		$eventos = new RouterViewConfiguration('eventos');
		$this->registerView($eventos);
		$evento = new RouterViewConfiguration('evento');
		$evento->setKey('id')->setParent($eventos);
		$this->registerView($evento);

		//{{inject: register_router_view}}

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}

	/**
	 * Get missing alias from the provided ID.
	 *
	 * @param	string		$id		The ID with or without the alias.
	 * @param	string		$table	The table name.
	 *
	 * @return	string		The alias string.
	 *
	 * @since	1.0.0
	 */
	private function getAlias(string $id, string $table) : string
	{
		try
		{
			$this->queryBuilder->clear();
			$this->queryBuilder->select('alias')
				->from($this->db->quoteName($table))
				->where($this->db->quoteName('id') . ' = ' . (int) $id);
			$this->db->setQuery($this->queryBuilder);

			return (string) $this->db->loadResult();
		}
		catch (\Exception $e)
		{
			echo $e->getMessage();

			return '';
		}
	}

	/**
	 * Get id from the alias.
	 *
	 * @param	string		$alias		The alias string.
	 * @param	string		$table		The table name.
	 *
	 * @return	int			The id.
	 *
	 * @since	1.0.0
	 */
	private function getId(string $alias, string $table) : int
	{
		try
		{
			$this->queryBuilder->clear();
			$this->queryBuilder->select('id')
				->from($this->db->quoteName($table))
				->where($this->db->quoteName('alias') . ' = ' . $this->db->quote($alias));
			$this->db->setQuery($this->queryBuilder);

			return (int) $this->db->loadResult();
		}
		catch (\Exception $e)
		{
			echo $e->getMessage();

			return 0;
		}
	}

	/**
	 * Get the view segment for the common views.
	 *
	 * @param	string	$id		The ID with or without alias.
	 * @param	string	$table	The table name.
	 *
	 * @return	array	The segment array.
	 *
	 * @since	1.0.0
	 */
	private function getViewSegment(string $id, string $table) : array
	{
		if (strpos($id, ':') === false)
		{
			$id .= ':' . $this->getAlias($id, $table);
		}

		if ($this->noIDs)
		{
			list ($key, $alias) = explode(':', $id, 2);

			return [$key => $alias];
		}

		return [(int) $id => $id];
	}

	/**
	 * get the view ID for the common pattern view.
	 *
	 * @param	string	$segment	The segment string.
	 * @param	string	$table		The table name.
	 *
	 * @return	int		The id.
	 *
	 * @since	1.0.0
	 */
	private function getViewId(string $segment, string $table) : int
	{
		return $this->noIDs
			? $this->getId($segment, $table)
			: (int) $segment;
	}

	/**
	 * Method to get the segment(s) for a note
	 *
	 * @param   string  $id     ID of the category to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since  1.0.0
	 */
	public function getNoteSegment($id, $query)
	{
		return $this->getViewSegment($id, '#__calevent_notes');
	}

	/**
	 * Method to get the note Id
	 *
	 * @param   string  $segment  Segment of the note to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 *
	 * @since  1.0.0
	 */
	public function getNoteId($segment, $query)
	{
		return $this->getViewId($segment, '#__calevent_notes');
	}

		/**
	 * Method to get the segment(s) for a evento
	 *
	 * @param   string  $id     ID of the category to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since  1.0.0
	 */
	public function getEventoSegment($id, $query)
	{
		return $this->getViewSegment($id, '#__calevent_eventos');
	}

	/**
	 * Method to get the evento Id
	 *
	 * @param   string  $segment  Segment of the evento to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 *
	 * @since  1.0.0
	 */
	public function getEventoId($segment, $query)
	{
		return $this->getViewId($segment, '#__calevent_eventos');
	}

	//{{inject: router_methods}}
}
