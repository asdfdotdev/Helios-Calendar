<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/filter.php');?>

	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
<?php
	$columns = (isset($hc_cols)) ? $hc_cols : 1;
	echo '<div class="filter">';
	echo '<form name="hcFilterUpdate" id="hcFilterUpdate" method="post" action="' . CalRoot . '/components/FilterAction.php">';
	echo '<input name="r" id="r" type="hidden" value="1" />';

	echo '<div style="text-align:center;padding:0px;">';
	echo '[ <a class="eventMain" href="javascript:;" onclick="checkAllArray(\'hcFilterUpdate\', \'catIDf[]\');document.getElementById(\'hcFilterUpdate\').submit();">' . $hc_lang_filter['All'] . '</a>';
	echo '&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray(\'hcFilterUpdate\', \'catIDf[]\');document.getElementById(\'hcFilterUpdate\').submit();">' . $hc_lang_filter['None'] . '</a> ]';
	echo '</div>';

	$catSet = (isset($_SESSION[$hc_cfg00 . 'hc_favCat'])) ? " AND c.PkID IN (" . $_SESSION[$hc_cfg00 . 'hc_favCat'] . ")" : '';
	$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, c2.PkID as Selected
			FROM " . HC_TblPrefix . "categories c
				LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID" . $catSet . ")
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
			WHERE c.ParentID = 0 AND c.IsActive = 1
			GROUP BY c.PkID
			UNION
			SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, c3.PkID as Selected
			FROM " . HC_TblPrefix . "categories c
				LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
				LEFT JOIN " . HC_TblPrefix . "categories c3 ON (c.PkID = c3.PkID" . $catSet . ")
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
			WHERE c.ParentID > 0 AND c.IsActive = 1
			GROUP BY c.PkID
			ORDER BY Sort, ParentID, CategoryName";

	$result = doQuery($query);
	$cnt = 1;
	$colWidth = number_format((100 / $columns), 0);
	echo '<br />';
	echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" width="' . $colWidth . '%">';
	while($row = mysql_fetch_row($result)){
		if($cnt > ceil(mysql_num_rows($result) / $columns) && $row[2] == 0){
			echo '</td><td valign="top" width="' . $colWidth . '%">';
			$cnt = 1;
		}//end if
		echo '<label for="catIDf_' . $row[0] . '" ';
		echo ($row[2] == 0) ? 'class="category"' : 'class="subcategory"';
		echo '><input ';
		echo ($row[4] != '') ? 'checked="checked" ' : '';
		echo 'name="catIDf[]" id="catIDf_' . $row[0] . '" type="checkbox" value="' . $row[0] . '" class="noBorderIE" onclick="document.getElementById(\'hcFilterUpdate\').submit();" />' . cOut($row[1]) . '</label>';
		++$cnt;
	}//end while
	echo '</td></tr></table></form></div>';
?>