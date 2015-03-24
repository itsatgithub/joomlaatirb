<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Science Component Publications Model
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class ScienceModelPublications extends JModel
{
	/**
	 * Data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Total number of data
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	/**
	 * Method to get the total number of items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the weblinks
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		// 2011-05-05 New condition on LEFT JOIN 
		$query = ' SELECT DISTINCT a.id, a.title, a.authors, a.journal, a.issue, a.volume, a.pages, a.pubmed_id'
		. ' FROM #__sci_publications AS a'
		. ' LEFT JOIN `#__sci_publications` AS b ON (b.pubmed_id = a.pubmed_id)'
		. $where
		. $orderby
		;
		/*
		$query = ' SELECT DISTINCT a.id, a.title, a.authors, a.journal, a.issue, a.volume, a.pages'
		. ' FROM #__sci_publications AS a'
		. ' LEFT JOIN `#__sci_publications` AS b ON (b.issue = a.issue AND b.volume = a.volume AND b.pages = a.pages)'
		. $where
		. $orderby
		;
		*/
		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'a.title', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );

		// sanitize $filter_order
		if (!in_array($filter_order, array('a.title', 'a.authors', 'a.journal', 'a.pubmed_id'))) {
			$filter_order = 'a.title';
		}

		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
			$filter_order_Dir = '';
		}
		
		$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	function _buildContentWhere()
	{
		global $mainframe, $option;

		$db =& JFactory::getDBO();

		$search = $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
		$search = JString::strtolower( $search );

		$where = array();

		// taking out duplicates
		$where[] = 'a.id != b.id';
		// not zero
		// Roberto 2013-07-24 Según peticion de Luca en ticket HXZ-584668
		// Roberto 2014-03-03 Según petición de Luca en ticket QGK-718968
		$where[] = '(a.pubmed_id IS NOT NULL AND a.pubmed_id != \'\' AND b.pubmed_id IS NOT NULL AND b.pubmed_id != \'\')';
		//$where[] = '(a.joint_publication = 1 OR b.joint_publication = 1)';
		
		
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}
}
