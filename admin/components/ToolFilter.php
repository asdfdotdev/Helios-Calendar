<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/tools.php');
	
	appInstructions(0, "Filter_Link", $hc_lang_tools['TitleFilter'], $hc_lang_tools['InstructFilter']);
	
	echo '
	<form name="frmToolLink" id="frmToolLink" method="post" action="" onsubmit="return false;">
	<fieldset>
		<legend>'.$hc_lang_tools['Link'].'</legend>
		<input name="filterLink" id="filterLink" type="text" size="95" maxlength="200" value="'.CalRoot.'/link.php" />
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['Cities'].'</legend>';
		
	$cities = getCities();
	$cnt = 1;
	$columns = 3;
	$colWidth = number_format((100 / $columns), 0);
	$colLimit = ceil(count($cities) / $columns);
	
	echo '
		<div class="catCol">';
	
	foreach($cities as $val){
		if($cnt > $colLimit){
			echo ($cnt > 1) ? '
		</div>' : '';
			echo '
		<div class="catCol">';
			$cnt = 1;}
		
		echo ($val != '') ? '
			<label for="cityName_'.str_replace(" ","_",$val).'"><input onclick="updateLink();" name="cityName[]" id="cityName_'.str_replace(" ","_",$val).'" type="checkbox" value="'.str_replace(" ","_",$val).'" />'.cOut($val).'</label>' : '';
		++$cnt;
	}
	
	echo '
		</div>	
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['CategoriesLabel'].'</legend>';
		
	$result = doQuery("SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
					WHERE c.ParentID = 0 AND c.IsActive = 1
					GROUP BY c.PkID, c.CategoryName, c.ParentID
					UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
					WHERE c.ParentID > 0 AND c.IsActive = 1
					GROUP BY c.PkID , c.CategoryName, c.ParentID, c2.CategoryName
					ORDER BY Sort, ParentID, CategoryName");
	$cnt = 1;
	echo '
		<div class="catCol">';
		
	while($row = mysql_fetch_row($result)){
		if($cnt > ceil(mysql_num_rows($result) / $columns) && $row[2] == 0){
			echo ($cnt > 1) ? '
		</div>' : '';
			echo '
		<div class="catCol">';
			$cnt = 1;}

		$sub = ($row[2] != 0) ? ' class="sub"' : '';
		echo '
			<label for="catID_' . $row[0] . '"'.$sub.'><input onclick="updateLink();" name="catID[]" id="catID_'.$row[0].'" type="checkbox" value="'.$row[0].'" />'.cOut($row[1]).'</label>';
		++$cnt;
	}
	echo '
		</div>
	</fieldset>
	<input name="reset" id="reset" type="reset" value="'.$hc_lang_tools['ResetLink'].'" />
	</form>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
		function updateLink(){
			var catStr = (validCheckArray("frmToolLink","catID[]",1,"error") == "") ? "l=" + checkUpdateString("frmToolLink", "catID[]") : "";
			var cityStr = (validCheckArray("frmToolLink","cityName[]",1,"error") == "") ? "c=" + checkUpdateString("frmToolLink", "cityName[]") : "";
			var both = (catStr != "" && cityStr != "") ? "&" : "";
			
			document.frmToolLink.filterLink.value = "'.CalRoot.'/link.php?" + catStr + both + cityStr;
		}
	//-->
	</script>';
?>