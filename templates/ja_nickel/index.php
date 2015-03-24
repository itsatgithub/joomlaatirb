<?php
/*------------------------------------------------------------------------
# JA Nickel for Joomla 1.5.x - Version 1.0 - Licence Owner JA98870
# ------------------------------------------------------------------------
# Copyright (C) 2004-2008 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

include_once (dirname(__FILE__).DS.'ja_vars_1.5.php');

jimport('joomla.html.pane');
$myTabs = &JPane::getInstance('tabs', array('startOffset'=>0));
$mysliders = &JPane::getInstance('sliders', array('allowAllClose' => true));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>"><head>
<jdoc:include type="head" />
<?php JHTML::_('behavior.mootools'); ?>
<link rel="stylesheet" href="<?php echo $tmpTools->baseurl(); ?>templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->baseurl(); ?>templates/system/css/general.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/typo.css" type="text/css" />

<script language="javascript" type="text/javascript" src="<?php echo $tmpTools->templateurl(); ?>/js/ja.script.js"></script>
<?php if ($tmpTools->getParam('ja_cufon')) : ?>
<script language="javascript" type="text/javascript" src="<?php echo $tmpTools->templateurl(); ?>/js/cufon/cufon.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $tmpTools->templateurl(); ?>/js/cufon/Zrnic_400.font.js"></script>
<script type="text/javascript">
		Cufon.replace('.componentheading, .contentheading, div.moduletable h3, div.moduletable_menu h3, div.moduletable_text h3,div.moduletable_highlight h3', { fontFamily: 'Zrnic' });
</script>
<?php endif; ?>
<?php if ($tmpTools->getParam('usertool_modfunc')) : ?>
<script language="javascript" type="text/javascript">
	var siteurl = '<?php echo $tmpTools->baseurl();?>';
	var tmplurl = '<?php echo $tmpTools->templateurl();?>';
</script>

<?php endif; ?>

<!-- Menu head -->

<?php if ($jamenu) { $jamenu->genMenuHead(); } ?>
<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/addons.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/ja.bulletin.css" type="text/css" />
<script type="text/javascript">
	//<![CDATA[
	document.write('<link rel="stylesheet" type="text/css" href="<?php echo $tmpTools->templateurl();?>/css/css3.css" media="all" \/>');
	//]]>
</script>
<link href="<?php echo $tmpTools->templateurl(); ?>/css/colors/<?php echo strtolower ($tmpTools->getParam(JA_TOOL_COLOR)); ?>.css" rel="stylesheet" type="text/css" />
<?php if ($tmpTools->isIE()) { ?>
	<link href="<?php echo $tmpTools->templateurl(); ?>/css/ie.php" rel="stylesheet" type="text/css" />
    <link href="<?php echo $tmpTools->templateurl(); ?>/css/colors/<?php echo strtolower ($tmpTools->getParam(JA_TOOL_COLOR)); ?>-ie.php" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
		window.addEvent ('load', makeTransBG);
		function makeTransBG() {
			makeTransBg($$('img'));
		}
	</script>
	<script type="text/javascript">
		var siteurl = '<?php echo $tmpTools->baseurl();?>';
	</script>
<?php }?>

<?php if ($tmpTools->isOP()) { ?>
<link href="<?php echo $tmpTools->templateurl(); ?>/css/op.css" rel="stylesheet" type="text/css" />
<?php } ?>

</head>

<body id="bd" class="<?php echo $tmpTools->getParam(JA_TOOL_LAYOUT);?> <?php echo $tmpTools->getParam(JA_TOOL_SCREEN);?> fs<?php echo $tmpTools->getParam(JA_TOOL_FONT);?>">
<a name="Top" id="Top"></a>
<!-- HEADER -->
<div id="ja-header" class="wrap">
  <div class="main clearfix">
  
  	<?php 
  	$siteName = $tmpTools->sitename(); 
  	if ($tmpTools->getParam('logoType')=='image') { ?>
  	<h1 class="logo">
  		<a href="index.php" title="<?php echo $siteName; ?>"><span><?php echo $siteName; ?></span></a>
  	</h1>
  	<?php } else { 
  	$logoText = (trim($tmpTools->getParam('logoText'))=='') ? $config->sitename : $tmpTools->getParam('logoText');
  	$sloganText = (trim($tmpTools->getParam('sloganText'))=='') ? JText::_('SITE SLOGAN') : $tmpTools->getParam('sloganText');	?>
  	<div class="logo-text">
  		<p class="site-slogan"><?php echo $sloganText;?></p>
  		<h1>
  			<a href="index.php" title="<?php echo $siteName; ?>"><span><?php echo $logoText; ?></span></a>	
  		</h1>
  	</div>
  	<?php } ?>
    <?php if($this->countModules('ja-login')) : ?>
    	<div id="ja-headtools" class="ja-headtool">
	    <ul> 			
  			<jdoc:include type="modules" name="ja-login" />
  		</ul>
		</div>
  	<?php endif; ?>
  </div>
</div>
<!-- //HEADER -->

<!-- MAIN NAVIGATION -->
<div id="ja-mainnav" class="wrap">  	
  <div class="main clearfix">
  	<ul class="no-display">
  		<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-content" title="<?php echo JText::_("Skip to content");?>"><?php echo JText::_("Skip to content");?></a></li>
  	</ul>
  
		<?php if ($jamenu) $jamenu->genMenu (0); ?>
		<?php if ($this->countModules('user4')) { ?>
            <div id="ja-search">
              <jdoc:include type="modules" name="user4" style="raw" />
            </div>
        <?php } ?>
	 </div>
</div>
<?php if ($hasSubnav) : ?>
<div id="ja-subnav" class="warp">
	<div class="main clearfix">
      <?php if ($jamenu) $jamenu->genMenu (1,1);	?>
    </div>
</div>
<?php endif; ?>
<!-- //MAIN NAVIGATION -->

<div id="ja-container<?php echo $divid; ?>" class="wrap">
  <div class="main clearfix">
  	<?php if ( $ja_left) { ?>
  	<!-- Left COLUMN -->
  	<div id="ja-col1">
        <div class="ja-innerpad">
            <jdoc:include type="modules" name="left" style="jamodule" />
        </div>
    </div>
  	<!-- //Left COLUMN -->
  	<?php } ?>
  	<!-- CONTENT -->  
  	<div id="ja-content">
    	<div class="main clearfix">
            <jdoc:include type="message" />
            <?php if ($this->countModules('ja-slideshow') ) { ?>
            <!-- TOP SPOTLIGHT -->
            <div id="ja-topsl" class="wrap">
              <div class="main">
                <div class="inner clearfix">
                    <div id="ja-slideshow">
                      <jdoc:include type="modules" name="ja-slideshow" style="raw" />
                    </div>
                </div>
              </div>
            </div>
            <!-- //TOP SPOTLIGHT -->
            <?php } ?>
            <div id="ja-current-content" class="clearfix">
                <jdoc:include type="component" />
            </div>
            <?php if ( $ja_right): ?>
            <div id="ja-col2">
                <jdoc:include type="modules" name="right" style="jamodule" />
            </div>
            <?php endif; ?>
        </div>
  	<!-- //CONTENT -->
  </div></div>
</div>
<!-- PATHWAY -->
<div id="ja-pathway" class="wrap">
  <div class="main">
  	<div class="ja-pathway-text">
    <strong>You are here:</strong><jdoc:include type="module" name="breadcrumbs" />
    </div>
  </div>
</div>
<!-- //PATHWAY -->
	<?php
	$spotlight = array ('user6','user8','user9');
	$bots2 = $tmpTools->calSpotlight ($spotlight,$tmpTools->isOP()?100:99.9);
	if( $bots2 ) {
	?>
	<!-- BOTTOM SPOTLIGHT 2-->
	<div id="ja-botsl1" class="wrap">
	<div class="main clearfix">
	
	  <?php if( $this->countModules('user6') ) {?>
	  <div class="ja-box<?php echo $bots2['user6']['class']; ?>" style="width: <?php echo $bots2['user6']['width']; ?>;">
			<jdoc:include type="modules" name="user6" style="jamodule" />
	  </div>
	  <?php } ?>
	  
	  <?php if( $this->countModules('user8') ) {?>
	  <div class="ja-box<?php echo $bots2['user8']['class']; ?>" style="width: <?php echo $bots2['user8']['width']; ?>;">
			<jdoc:include type="modules" name="user8" style="jamodule" />
	  </div>
	  <?php } ?>
	  
	  <?php if( $this->countModules('user9') ) {?>
	  <div class="ja-box<?php echo $bots2['user9']['class']; ?>" style="width: <?php echo $bots2['user9']['width']; ?>;">
			<jdoc:include type="modules" name="user9" style="jamodule" />
	  </div>
	  <?php } ?>
	</div></div>
	<!-- //BOTTOM SPOTLIGHT 2 -->
	<?php } ?>
	<?php
	$spotlight = array ('user1','user2','user7');
	$botsl = $tmpTools->calSpotlight ($spotlight,$tmpTools->isOP()?100:99.9);
	if( $botsl ) {
	?>
	<!-- BOTTOM SPOTLIGHT 1-->
	<div id="ja-botsl2" class="wrap">
	<div class="main clearfix">
	
	  <?php if( $this->countModules('user1') ) {?>
	  <div class="ja-box<?php echo $botsl['user1']['class']; ?>" style="width: <?php echo $botsl['user1']['width']; ?>;">
			<jdoc:include type="modules" name="user1" style="jamodule" />
	  </div>
	  <?php } ?>
	  
	  <?php if( $this->countModules('user2') ) {?>
	  <div class="ja-box<?php echo $botsl['user2']['class']; ?>" style="width: <?php echo $botsl['user2']['width']; ?>;">
			<jdoc:include type="modules" name="user2" style="jamodule" />
	  </div>
	  <?php } ?>
	  
	  <?php if( $this->countModules('user7') ) {?>
	  <div class="ja-box<?php echo $botsl['user7']['class']; ?>" style="width: <?php echo $botsl['user7']['width']; ?>;">
			<jdoc:include type="modules" name="user7" style="jamodule" />
	  </div>
	  <?php } ?>
	</div></div>
	<!-- //BOTTOM SPOTLIGHT 1 -->
	<?php } ?>
<!-- FOOTER -->
<div id="ja-footer" class="wrap">
<div class="main clearfix">
	<!-- 
	<div class="sublogo">
    	<img src="<?php echo $tmpTools->templateurl(); ?>/images/<?php echo strtolower ($tmpTools->getParam(JA_TOOL_COLOR)); ?>/sub-logo.png" alt="Logo" />
	</div>
	-->
    <div class="ja-info">
        <jdoc:include type="modules" name="user3" />
        <jdoc:include type="modules" name="footer" />
    </div>
</div>
</div>
<!-- //FOOTER -->

<jdoc:include type="modules" name="debug" />
<script type="text/javascript">
	addSpanToTitle();
	jaAddFirstItemToTopmenu();
	//jaRemoveLastContentSeparator();
	//jaRemoveLastTrBg();
	//moveReadmore();
	//addIEHover();
	//slideshowOnWalk ();
	//apply png ie6 main background
</script>
</body>

</html>