<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php jimport('joomla.html.pane'); ?>

<script
	type="text/javascript" src="modules/mod_avuisearch/tabber.js"></script>
<link
	rel="stylesheet" href="modules/mod_avuisearch/tabber.css"
	TYPE="text/css" MEDIA="screen">

<!--
<script type="text/javascript">

/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>
-->

<dl class="tabs" id="pane">
	<dt id="tab1">primera</dt>
	<dd>
	<p>This is the first tab</p>
	</dd>
	<dt id="tab2"><span>2nd</span></dt>
	<dd>
	<p>This is the secondt tab</p>
	</dd>
	<dt id="tab3"><span>3rd</span></dt>
	<dd>
	<p>This is the third tab</p>
	</dd>
</dl>

<?php
$pane =& JPane::getInstance('tabs', array('startOffset'=>2));
echo $pane->startPane( 'pane' );
echo $pane->startPanel( 'Panel1', 'panel1' );
echo "This is panel1";
echo $pane->endPanel();
echo $pane->startPanel( 'Panel2', 'panel2' );
echo "This is panel2";
echo $pane->endPanel();
echo $pane->startPanel( 'Panel3', 'panel3' );
echo "This is panel3";
echo $pane->endPanel();
echo $pane->endPane();
?>

<div class="tabber">

<div class="tabbertab" title="Content"><br />
<form action="index.php" method="post">
<div class="search<?php echo $params->get('moduleclass_sfx') ?>"><?php
$output = '<input name="searchword" id="mod_search_searchword" maxlength="'.$maxlength.'" alt="'.$button_text.'" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="'.$text.'"  style="width:140px;margin:5px 0 5px 0;" onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />';

if ($button) :
if ($imagebutton) :
$button = '<input type="image" value="'.$button_text.'" class="button'.$moduleclass_sfx.'" src="'.$img.'" onclick="this.form.searchword.focus();"/>';
else :
$button = '<input type="submit" value="'.$button_text.'" class="button'.$moduleclass_sfx.'" onclick="this.form.searchword.focus();"/>';
endif;
endif;

switch ($button_pos) :
case 'top' :
	$button = $button.'<br />';
	$output = $button.$output;
	break;

case 'bottom' :
	$button = '<br />'.$button;
	$output = $output.$button;
	break;

case 'right' :
	$output = $output.$button;
	break;

case 'left' :
default :
	$output = $button.$output;
	break;
	endswitch;

	echo $output;
	?></div>
<input type="hidden" name="task" value="search" /> <input type="hidden"
	name="option" value="com_search" /></form>
</div>
<div class="tabbertab" title="People"><br />
<script type="text/javascript">
function resettoggle() {
	var e = document.getElementById('advanced_search');
	e.style.display = 'none';
}

function toggle_visibility(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'none')
		e.style.display = 'block';
	else
		e.style.display = 'none';
}
</script> <!-- 
body onLoad="resettoggle()" to the template index.php
And to get the Item id showhow
Modificar el whitepages
-->

<form
	action="<?php echo JRoute::_( 'index.php?option=com_whitepages&Itemid=' . $GLOBALS['Itemid'] . '&task=search' ) ?>"
	method="post"><input type="text" name="search" id="search" size="20"
	value="<?php echo $text; ?>" style="width: 140px; margin: 5px 0 5px 0;"
	onblur="if(this.value=='') this.value='<?php echo $text; ?>';"
	onfocus="if(this.value=='<?php echo $text; ?>') this.value='';" /> <input
	type="submit" value="<?php echo JText::_( 'Search' ); ?>"
	class="button" /> <input type="button" name="task_button"
	class="button" value="Advanced"
	onclick="toggle_visibility('advanced_search');" />
<div id="advanced_search">
<table cellpadding="3">
	<tr>
		<td width="60"><input type="checkbox" name="name" value="name"
			checked="true" /> name</td>
		<td width="80"><input type="checkbox" name="group" value="group" />
		unit</td>
	</tr>
	<tr>
		<td width="60"><input type="checkbox" name="position" value="position" />
		title</td>
		<td width="80"><input type="checkbox" name="location" value="location" />
		location</td>
	</tr>
	<tr>
		<td width="60"><input type="checkbox" name="rg" value="rg" /> RG</td>
		<td></td>
	</tr>
</table>
</div>
</form>
	<?php echo JHTML::link( 'index.php?option=com_whitepages&Itemid=' . $GLOBALS['Itemid'] . '&task=all', JText::_('Whole List') ); ?>
</div>
</div>
