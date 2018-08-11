<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	include($hc_langPath . $_SESSION['LangSet'] . '/public/rss.php');
	include($hc_langPath . $_SESSION['LangSet'] . '/public/links.php');?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
		function updateLink(){
			var catStr = "";
			var cityStr = "";
			var doBoth = false;
			if(!validateCheckArray('frmEventRSS','catID[]',1) > 0){
				catStr = 'l=' + checkUpdateString('frmEventRSS', 'catID[]');
				doBoth = true;
			}//end if
			if(!validateCheckArray('frmEventRSS','cityName[]',1) > 0){
				cityStr = doBoth == true ? '&' : '';
				cityStr += 'c=' + checkUpdateString('frmEventRSS', 'cityName[]');
			}//end if
			document.frmEventRSS.rssLink.value = '<?php echo CalRoot;?>/rss/custom.php?' + catStr + cityStr;
		}//end updateLink()
	//-->
	</script>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_rss['RSSWhat'];?></legend>
		<div><a href="http://en.wikipedia.org/wiki/RSS_%28file_format%29" class="eventMain" target="_blank" rel="nofollow"><?php echo $hc_lang_rss['RSSLink'];?></a></div>
	</fieldset>
	<form name="frmEventRSS" id="frmEventRSS" method="post" action="<?php echo CalRoot;?>/index.php?com=rss" target="_blank" onsubmit="return false;">
	<div class="rssTitle"><?php echo $hc_lang_rss['CreateCustom'];?></div>
	<?php echo $hc_lang_rss['CreateInstruct'];?><br /><br />
	<fieldset>
		<legend><?php echo $hc_lang_rss['Cities'];?></legend>
		<div class="frmOpt">
	<?php 	$cities = getCities();
			$cnt = 1;
			$columns = 3;
			$colWidth = number_format((100 / $columns), 0);
			$colLimit = ceil(count($cities) / $columns);
			echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" width="' . $colWidth . '%">';
			foreach($cities as $val){
				if($cnt > $colLimit){
					echo '</td><td valign="top" width="' . $colWidth . '%">';
					$cnt = 1;
				}//end if
				if($val != ''){	
					//$findMe = "'" . $val . "'";
					echo '<label for="cityName_' . str_replace(" ","_",$val) . '" class="category"><input onclick="updateLink();" name="cityName[]" id="cityName_' . str_replace(" ","_",$val) . '" type="checkbox" value="' . urlencode($val) . '" class="noBorderIE" />' . $val . '</label>';
				}//end if
				++$cnt;
			}//end foreach
			echo '</td></tr></table>';	?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_rss['Categories'];?></legend>
		<div class="frmOpt">
	<?php	$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						WHERE c.ParentID = 0 AND c.IsActive = 1
						GROUP BY c.PkID
						UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, NULL as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
						WHERE c.ParentID > 0 AND c.IsActive = 1
						GROUP BY c.PkID 
						ORDER BY Sort, ParentID, CategoryName";
			$result = doQuery($query);
			$cnt = 1;
			$colLimit = ceil(mysql_num_rows($result) / $columns);
			echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" width="' . $colWidth . '%">';
			while($row = mysql_fetch_row($result)){
				if($cnt > $colLimit && $row[2] == 0){
					echo '</td><td valign="top" width="' . $colWidth . '%">';
					$cnt = 1;
				}//end if
				echo '<label for="catID_' . $row[0] . '" ';
				echo ($row[2] == 0) ? 'class="category"' : 'class="subcategory"';
				echo '><input onclick="updateLink();" ';
				echo ($row[4] != '') ? 'checked="checked" ' : '';
				echo 'name="catID[]" id="catID_' . $row[0] . '" type="checkbox" value="' . $row[0] . '" class="noBorderIE" />' . cOut($row[1]) . '</label>';
				++$cnt;
			}//end while
			echo '</td></tr></table>';?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_rss['PasteInstruct'];?></legend>
		<div class="frmOpt">
			<input readonly="readonly" name="rssLink" id="rssLink" type="text" style="width:95%;" maxlength="200" value="<?php echo CalRoot;?>/rss/custom.php" />
		</div>
	</fieldset>
	<br />
	<input name="reset" id="reset" type="reset" value="<?php echo $hc_lang_rss['StartOver'];?>" />
	</form>
	<br />
	<div class="rssTitle"><?php echo $hc_lang_rss['Guidelines'];?></div>
	<?php echo $hc_lang_rss['GuidelineText'];?>