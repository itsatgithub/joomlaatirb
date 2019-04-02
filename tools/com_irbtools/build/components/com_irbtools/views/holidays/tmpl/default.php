<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php 
JHTML::stylesheet('holidays.css', 'components/com_irbtools/css/');
?>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape( JText::_( 'HOLIDAYS_TOP') ); ?>
	</div>
<?php endif; ?>

<?php echo JText::_( 'HOLIDAYS_TOP_INTRO' ); ?>
<br>
<?php echo JText::_( 'HOLIDAYS_APOLOGIZE' ); ?>
