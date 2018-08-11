<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/manage.php');
	
	$cID = $parentID = 0;
	$category = $catOptions = '';
	$whereAmI = $hc_lang_manage['AddCategory'];
	$token = set_form_token(1);
	
	if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
		$cID = cIn(strip_tags($_GET['cID']));
		$whereAmI = $hc_lang_manage['EditCategory'];
	}
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_manage['Feed03']);
				break;
			case "2" :
				feedback(1, $hc_lang_manage['Feed04']);
				break;
			case "3" :
				feedback(1, $hc_lang_manage['Feed05']);
				break;
		}
	}
	
	$result = doQuery("SELECT PkID, CategoryName, ParentID FROM " . HC_TblPrefix . "categories WHERE PkID = '" . $cID . "'");
	if(hasRows($result)){
		$category = mysql_result($result,0,1);
		$parentID = mysql_result($result,0,2);}
	
	$result = doQuery("SELECT PkID, CategoryName FROM " . HC_TblPrefix . "categories WHERE PkID != '" . $cID . "' AND IsActive = 1 AND ParentID = 0 ORDER BY CategoryName");
	while($row = mysql_fetch_row($result)){
		$catOptions .= ($parentID == $row[0]) ? '<option selected="selected" value="'.$row[0].'">'.$row[1].'</option>' : '<option value="'.$row[0].'">'.$row[1].'</option>';
	}
	
	appInstructions(0, "Category_Management", $hc_lang_manage['TitleCategory'], $hc_lang_manage['InstructCategory']);
	
	echo '
	<div class="catMgtForm">
	<form name="frm" id="frm" method="post" action="'.AdminRoot.'/components/CategoryManageAction.php" onsubmit="return validate();">
	<input type="hidden" name="token" id="token" value="'.$token.'" />
	<input type="hidden" name="cID" id="cID" value="'.$cID.'" />
	<fieldset>
		<legend>'.$whereAmI.(($cID > 0) ? '&nbsp;( <a href="'.AdminRoot.'/index.php?com=categorymanage">'.$hc_lang_manage['AddNew'].'</a> )':'').'</legend>
		<label>'.$hc_lang_manage['Name'].'</label>
		<input name="catName" id="catName" type="text" size="35" maxlength="40" required="required" value="'.$category.'" />
		<label for="parentID">'.$hc_lang_manage['Parent'].'</label>
		<select name="parentID" id="parentID">
			<option value="0">'.$hc_lang_manage['NoParent'].'</option>
			<option value="0">------------------</option>
			'.$catOptions.'
		</select>
	</fieldset>
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_manage['SaveCat'].'" />
	</form>
	</div>
	<div class="catMgtList">
		<ul class="data">';

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
					GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName
					ORDER BY Sort, ParentID, CategoryName");
	$rowCnt = mysql_num_rows($result);
	if(hasRows($result)){	
		$cnt = 0;
		$curCat = "";
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl' : '';
			$indent = ($row[2] != 0) ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '';
			
			echo '
			<li class="row'.$hl.'">
				<div style="width:85%;">'.$indent.(($cID == $row[0] && $cID > 0) ? '<b>' . cOut($row[1]) . '</b>' : cOut($row[1])).'</div>
				<div style="width:15%;">
					<a href="'.AdminRoot.'/index.php?com=categorymanage&amp;cID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
					'.(($rowCnt > 1) ? '<a href="javascript:;" onclick="doDelete(\''.$row[0].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>' : '').'
				</div>
			</li>';
			$cnt++;
		}
		
		echo '
			</ul>
		<script>
		//<!--
		function doDelete(dID){
			if(confirm("'.$hc_lang_manage['Valid08'].'\\n\\n          '.$hc_lang_manage['Valid09'].'\\n          '.$hc_lang_manage['Valid10'].'"))
				window.location.href = "'.AdminRoot.'/components/CategoryManageAction.php?dID=" + dID + "&tkn='.$token.'";
		}
		function validate(){
			var err = "";
			
			err +=reqField(document.getElementById("catName"),"'.$hc_lang_manage['Valid12'].'\n");
			
			if(err != ""){
				alert(err);
				return false;
			} else {
				valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
				return true;
			}
		}
		//-->
		</script>';
	} else {
		echo '<p>'.$hc_lang_manage['NoCategory'].'</p>';
	}
	echo '</div>';
?>