<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JCronControllerJCronEdit extends JCronController
{
	
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'jcronedit' );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('jcronedit');
                $result = $model->store();
		if ($result[0]) {
			$msg = JText::_( 'Task Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Task' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_jcron&view=jcron';
		$this->setRedirect($link, $msg);
	}

        function apply()
	{
            $model = $this->getModel('jcronedit');
            $result = $model->store();
            if ($result[0]) {
		$msg = JText::_( 'Task Saved!' );
            } else {
		$msg = JText::_( 'Error Saving Task' );
            }

            // Check the table in so it can be edited.... we are done with it anyway
            $link = 'index.php?option=com_jcron&controller=jcronedit&task=edit&cid[]='. $result[1];
            $this->setRedirect($link, $msg);

	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('jcronedit');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Tasks Could not be Deleted' );
		} else {
			$msg = JText::_( 'Task(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_jcron&view=jcron', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_jcron&view=jcron', $msg );
	}
	
	
	
	
	function publish()
	{
		$model = $this->getModel('jcronedit');
		if(!$model->publish()) {
			$msg = JText::_( 'Error: One or More Tasks Could not be Published' );
		} else {
			$msg = JText::_( 'Task(s) Published' );
		}

		$this->setRedirect( 'index.php?option=com_jcron&view=jcron', $msg );
	}
	
	
	function unpublish()
	{
		$model = $this->getModel('jcronedit');
		if(!$model->unpublish()) {
			$msg = JText::_( 'Error: One or More Tasks Could not be Unpublished' );
		} else {
			$msg = JText::_( 'Task(s) Unpublished' );
		}

		$this->setRedirect( 'index.php?option=com_jcron&view=jcron', $msg );
	}
}