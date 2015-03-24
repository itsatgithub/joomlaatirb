<?php

if ($cols > 0) {
	echo "<div class=\"ja-newscatwrap\">";
	$modwidth = round(100 / $cols, 2) ;
	$l = 0;
	$modStyle = JA_News::calModStyle ($cols);
	$isrowopen = false;

	$rows = JA_News::getNews ($catid, $numbercontent);
	for ($k=0;$k<count($rows);++$k){
		$row = $rows[$k];

		$bs 	= $mainframe->getBlogSectionCount();
		$bc 	= $mainframe->getBlogCategoryCount();
		$gbs 	= $mainframe->getGlobalBlogSectionCount();

		// Output
		if ($l == 0){
			//Begin a row
			echo "<div class=\"ja-newsblock clearfix\">\n";
			$isrowopen = true;
		}
		echo "<div class=\"ja-newsitem{$modStyle[$l]['class']}\" style=\"width: {$modStyle[$l]['width']};\">";
		echo "<div class=\"ja-newsitem-inner\">\n";

		
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));

		$row->text 	= $row->introtext;
		$mainframe->triggerEvent( 'onPrepareContent', array( &$row, &$params, 0 ), true );
		$row->introtext = $row->text;

		$image = JA_News::replaceImage ($row, $maxchars, $showimage, $width, $height);

		//Show the latest news
		echo "<div class=\"ja-newscontent\">\n";
		echo $image. "\n";
		echo "<a href=\"$link\" class=\"ja-newstitle\" title=\"".($showintro ? $row->title : $row->introtext1)."\">{$row->title}</a>\n";
		if ($showintro){
			if ($maxchars)
				echo "{$row->introtext1}\n";
			  else
				echo "{$row->introtext}\n";
			if ($showreadmore) echo "<a href=\"$link\" class=\"readon\">"._READ_MORE."</a>";
		}
		echo "</div>\n";
		echo "</div></div>\n";
		$l++;
		if ($isrowopen && ($l == $cols || $k == (count ($rows)-1))){
			//End a row
			echo "</div>\n";
			echo "<span class=\"article_seperator\">&nbsp;</span>";
			$l = 0;
			$isrowopen = false;
		}
	}
	if($showreadall){
		$readAll = JURI::_cleanPath('index.php?option=com_content&amp;task=blogcategory&amp;id='.$catid);
		echo "<a href=\"$readAll\" title=\"View all news in this category\" class=\"readon\"> View all news ... </a><br />";
	}
	echo "</div>";
}