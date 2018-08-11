<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/tools.php');
	
	appInstructions(0, "Filter_Link", $hc_lang_tools['TitleFilter'], $hc_lang_tools['InstructFilter']);	?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
		function updateLink(){
			var catStr = "";
			var cityStr = "";
			var doBoth = false;
			if(!validateCheckArray('frmToolLink','catID[]',1) > 0){
				catStr = 'l=' + checkUpdateString('frmToolLink', 'catID[]');
				doBoth = true;
			}//end if
			if(!validateCheckArray('frmToolLink','cityName[]',1) > 0){
				cityStr = doBoth == true ? '&' : '';
				cityStr += 'c=' + checkUpdateString('frmToolLink', 'cityName[]');
			}//end if
			document.frmToolLink.filterLink.value = '<?php echo CalRoot;?>/?' + catStr + cityStr;
		}//end updateLink()
	//-->
	</script>
	<form name="frmToolLink" id="frmToolLink" method="post" action="" onsubmit="return false;">
	<fieldset>
		<legend><?php echo $hc_lang_tools['Link'];?></legend>
		<div class="frmReq">
			<input name="filterLink" id="filterLink" type="text" size="95" maxlength="200" value="<?php echo CalRoot;?>/" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Cities'];?></legend>
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
		<legend><?php echo $hc_lang_tools['Categories'];?></legend>
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
	<input name="reset" id="reset" type="reset" value=" <?php echo $hc_lang_tools['ResetLink'];?> " class="button" />
	</form>
