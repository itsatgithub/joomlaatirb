<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'report.css', 'components/com_science/assets/'); ?>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>

<form
	action="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_science&view=report&format=raw"
	method="post" name="adminForm">
	<input type="hidden" name="start_date" value="<?php echo $this->start_date; ?>" />
	<input type="hidden" name="end_date" value="<?php echo $this->end_date; ?>" />
	<input type="hidden" name="groups" value="<?php echo base64_encode( serialize( $this->groups ) ); ?>" />
	<input type="hidden" name="reports"	value="<?php echo base64_encode( serialize( $this->reports ) ); ?>" />
	<fieldset class="input">
	<input type="submit" name="Submit" value="<?php echo JText::_('TO_EXCEL') ?>" />
	</fieldset>
<br>

<?php

foreach( $this->lines as $year=>$year_array )
{
	?>
<h2><?php echo $year; ?></h2>
	<?php

	foreach( $year_array as $research_programme_desc=>$research_programme_array )
	{
		?>
<h2><?php echo $research_programme_desc; ?></h2>
		<?php

		foreach( $research_programme_array as $gl=>$gl_array )
		{
			?>
<h2><?php echo $gl; ?></h2>
			<?php

			// publications
			if ( array_key_exists( 'publications', $gl_array ) ) // if the user selected this one...
			{
				?>
<h3><?php echo JText::_('PUBLICATION_HEADER') ?></h3>
				<?php

				if (count($gl_array['publications']))
				{
					$items =& $gl_array['publications'];
					for ($i=0, $n=count( $items ); $i < $n; $i++)
					{
						$item =& $items[$i];

						?>
<p><?php echo $item->authors; ?> <?php echo $item->title; ?> <em><?php echo $item->journal; ?></em>,
<strong><?php echo $item->volume; ?></strong> (<?php echo $item->issue; ?>),
<?php echo $item->pages; ?> (<?php echo $item->year; ?>)</p>
<?php
					}
				} else {
					echo JText::_('NO_DATA');
				}
			}

			// collaborations
			if ( array_key_exists( 'collaborations', $gl_array ) ) // if the user selected this one...
			{
				?>
<h3><?php echo JText::_('COLLABORATION_HEADER') ?></h3>
				<?php

				if (count($gl_array['collaborations']))
				{
					$items =& $gl_array['collaborations'];
					$old_id = 0; // working with more than one row per collaboration

					$internal_collaborations = '';
					$external_collaborations = '';

					// first element. IMP some elements could have been deleted, so we use current() and next()
					$item = current($items);
					for ($i=0, $n=count( $items ); $i < $n; $i++) // estas arreglando esto
					{
						if (($item->collaboration_id != $old_id) || (($item->collaboration_id == $old_id) && ($item->type_id != $old_type_id))) {
							$entry = "<br><br><em>".$item->title."</em><br>".$item->research_name.", ".$item->institution." (".$item->city.", ".$item->country.")";
						} else {
							$entry = "; ".$item->research_name.", ".$item->institution." (".$item->city.", ".$item->country.")";
						}

						if ($item->type_id == '1') {
							$internal_collaborations .= $entry;
						} else {
							$external_collaborations .= $entry;
						}

						$old_id = $item->collaboration_id;
						$old_type_id = $item->type_id;

						$item = next($items); // next element
					}

					if ($internal_collaborations)
					{
						?><br>
<strong>Internal Collaborations</strong><br>
<br>
						<?php echo substr($internal_collaborations,8); ?> <br>
<br>
						<?php
					}

					if ($external_collaborations)
					{
						?><br>
<strong>External Collaborations</strong><br>
<br>
						<?php echo substr($external_collaborations,8); ?> <br>
<br>
						<?php
					}
				} else {
					echo JText::_('NO_DATA');
				}
			}

			// projects
			if ( array_key_exists( 'projects', $gl_array ) ) // if the user selected this one...
			{
				?>
<h3><?php echo JText::_('PROJECT_HEADER') ?></h3>
				<?php

				if (count($gl_array['projects']))
				{
					$items =& $gl_array['projects'];

					for ($i=0, $n=count( $items ); $i < $n; $i++)
					{
						$item =& $items[$i];
						$funding_entity = explode(" - ",$item->gt_description);

						// code updated - 2010-11-17 - See tickets
						if (date('Y',strtotime($item->start_date)) == date('Y',strtotime($item->end_date))) {
							$aux_str = date('Y',strtotime($item->start_date));
						} else {
							$aux_str = date('Y',strtotime($item->start_date)) . '-' . date('Y',strtotime($item->end_date));
						}
							
						?><span class='body_report'>
<p><em><?php echo $item->title; ?></em><br>
<?php echo $item->beneficiary; ?> <?php echo $funding_entity[(count($funding_entity)-1)] ?>,
<?php echo $item->reference; ?> (<?php echo $aux_str; ?>)<br>
<!--strong>Group leader: </strong><?php //echo $item->gl_name; ?><br-->
<strong>Principal investigator: </strong><?php echo $item->principal_investigator; ?></p>
</span><?php
					}
				} else {
					echo JText::_('NO_DATA');
				}
			}

			// research contracts
			if ( array_key_exists( 'researchcontracts', $gl_array ) ) // if the user selected this one...
			{
				?>
<h3><?php echo JText::_('RESEARCHCONTRACT_HEADER') ?></h3>
				<?php

				if (count($gl_array['researchcontracts']))
				{
					$items =& $gl_array['researchcontracts'];

					for ($i=0, $n=count( $items ); $i < $n; $i++)
					{
						$item =& $items[$i];

						?><span class='body_report'>
<p><em><?php echo $item->irb_code; ?></em><br>
Acronym: <?php echo $item->acronym; ?><br>
Company: <?php echo $item->company; ?><br>
Principal investigator: <?php echo $item->principal_investigator; ?></p>
</span><?php
					}
				} else {
					echo JText::_('NO_DATA');
				}
			}
			
			// theses
			if ( array_key_exists( 'theses', $gl_array ) ) // if the user selected this one...
			{
				?>
<h3><?php echo JText::_('THESIS_HEADER') ?></h3>
				<?php

				if (count($gl_array['theses']))
				{
					$items =& $gl_array['theses'];

					for ($i=0, $n=count( $items ); $i < $n; $i++)
					{
						$item =& $items[$i];

						?><span class='body_report'>
<p><em><?php echo $item->title; ?></em><br>
						<?php echo $item->author; ?>, <?php echo $item->university; ?> (<?php echo $item->year; ?>)<br>
<strong>Thesis director:</strong> <?php echo $item->director; ?><br>
<strong>Honors: </strong><?php echo $item->th_description; ?></p>
</span><?php
					}
				} else {
					echo JText::_('NO_DATA');
				}
			}

			// patents
			if ( array_key_exists( 'patents', $gl_array ) ) // if the user selected this one...
			{
				?>
<h3><?php echo JText::_('PATENT_HEADER') ?></h3>
				<?php

				if (count($gl_array['patents']))
				{
					$items =& $gl_array['patents'];

					for ($i=0, $n=count( $items ); $i < $n; $i++)
					{
						$item =& $items[$i];

						?><span class='body_report'>
<p><em><?php echo $item->title; ?></em><br>
						<?php echo $item->inventors; ?><br>
Publication number/date: <?php echo $item->publication_number; ?> (<?php echo date('d/m/Y',strtotime($item->publication_date)); ?>)<br>
Status: <?php echo $item->ps_description; ?></p>
</span><?php
					}
				} else {
					echo JText::_('NO_DATA');
				}
			}

			// awards
			if ( array_key_exists( 'awards', $gl_array ) ) // if the user selected this one...
			{
				?>
<h3><?php echo JText::_('AWARD_HEADER') ?></h3>
				<?php

				if (count($gl_array['awards']))
				{
					$items =& $gl_array['awards'];

					for ($i=0, $n=count( $items ); $i < $n; $i++)
					{
						$item =& $items[$i];

						// formating the year string
						if ($item->year == $item->end_year) {
							$yearString = $item->year;
						} else {
							$yearString = $item->year . '-' . $item->end_year;
						}
						?><span class='body_report'>
<p><em><?php echo $item->title; ?></em><br>
<?php echo $item->awarding_body; ?> (<?php echo $yearString; ?>)<br>
<strong>Awardee:</strong> <?php echo $item->awardee; ?></p>
</span><?php
					}
				} else {
					echo JText::_('NO_DATA');
				}
			}
			?><br />
<br />
			<?php
		}
		?><br />
<br />
		<?php
	}
	?><br />
<br />
	<?php
}
?>
<fieldset class="input"><input type="submit" name="Submit"
	value="<?php echo JText::_('TO_EXCEL') ?>" /></fieldset>
</form>
