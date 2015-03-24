<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php $year = ''; ?>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>

<?php foreach($this->publicationlist as $publication): ?>

<?php if ($year != $publication->year): ?>
<h2><?php echo $publication->year; 
$year = $publication->year; ?></h2>
<?php endif; ?>

<?php echo $publication->title; ?>
<?php if ($this->display_paper_type): ?>
[
<?php echo $publication->type; ?>
]
<?php endif; ?>
<br>
<?php echo $publication->authors; ?>
<br>
<em><?php echo $publication->journal; ?></em>
,
<strong><?php echo $publication->volume; ?></strong>
(
<?php echo $publication->issue; ?>
),
<?php echo $publication->pages; ?>
(
<?php echo $publication->year; ?>
)
<?php if ($publication->citations): ?>
[
<?php echo 'Citations/DOI:'.$publication->citations; ?>
]
<?php endif; ?>
<br>
<br>
<br>

<?php endforeach; ?>