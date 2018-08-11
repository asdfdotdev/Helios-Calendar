<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/manage.php');
	
	$cID = 0;
	$whereAmI = $hc_lang_manage['AddCategory'];
	if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
		$cID = $_GET['cID'];
		$whereAmI = $hc_lang_manage['EditCategory'];
	}//end if

	if (isset($_GET['msg'])){
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
		}//end switch
	}//end if
	
	$category = '';
	$parentID = 0;
	$result = doQuery("SELECT PkID, CategoryName, ParentID FROM " . HC_TblPrefix . "categories WHERE PkID = " . cIn($cID));
	if(hasRows($result)){
		$category = mysql_result($result,0,1);
		$parentID = mysql_result($result,0,2);
	}//end if
	
	appInstructions(0, "Category_Management", $hc_lang_manage['TitleCategory'], $hc_lang_manage['InstructCategory']);?>
	
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('<?php echo $hc_lang_manage['Valid08'] . "\\n\\n          " . $hc_lang_manage['Valid09'] . "\\n          " . $hc_lang_manage['Valid10'];?>')){
			window.location.href='<?php echo CalAdminRoot . "/components/CategoryManageAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	
	function chkFrm(){
		var dirty = 0;
		var warn = '<?php echo $hc_lang_manage['Valid11'];?>\n';
		
		if(document.frm.name.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_manage['Valid12'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_manage['Valid13'];?>');
			return false;
		}//end if
	}//end chkFrm()
	//-->
	</script>
	<br />
	<div class="catMgtForm">
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/CategoryManageAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="cID" id="cID" value="<?php echo $cID;?>" />	
	<fieldset style="padding-top:5px;">
		<legend><?php echo $whereAmI; if($cID > 0){ echo '&nbsp;&nbsp;( <a href="' . CalAdminRoot . '/index.php?com=categorymanage" class="legend">' . $hc_lang_manage['AddNew'] . '</a> )';}?></legend>
		<div class="frmReq">
			<label><?php echo $hc_lang_manage['Name'];?></label>
			<input type="text" name="name" id="name" value="<?php echo $category;?>" />
		</div>
		<div class="frmOpt">
			<label for="parentID"><?php echo $hc_lang_manage['Parent'];?></label>
			<select name="parentID" id="parentID">
				<option value="0"><?php echo $hc_lang_manage['NoParent'];?></option>
				<option value="0">------------------</option>
		<?php	$result = doQuery("SELECT PkID, CategoryName FROM " . HC_TblPrefix . "categories WHERE PkID != " . cIn($cID) . " AND IsActive = 1 AND ParentID = 0 ORDER BY CategoryName");
				while($row = mysql_fetch_row($result)){
					if($parentID == $row[0]){
						echo "<option selected=\"selected\" value=\"" . $row[0] . "\">" . $row[1] . "</option>";
					} else {
						echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
					}//end if
				}//end while	?>
			</select>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_manage['SaveCat'];?> " class="button" />
	</form>
	</div>
	<div class="catMgtList">
<?php 	
	$result = doQuery("SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						WHERE c.ParentID = 0 AND 
									c.IsActive = 1
						GROUP BY c.PkID
						UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, NULL as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
						WHERE c.ParentID > 0 AND 
									c.IsActive = 1
						GROUP BY c.PkID 
						ORDER BY Sort, ParentID, CategoryName");
	$rowCnt = mysql_num_rows($result);
	if(hasRows($result)){	
		$cnt = 0;
		$curCat = "";
		while($row = mysql_fetch_row($result)){
			if($curCat != $row[0]){
				echo ($cnt % 2 == 1) ? '<div class="categoryListTitleHL">' : '<div class="categoryListTitle">';
				echo ($row[2] != 0) ? '&nbsp;&nbsp;&nbsp;' : '';
				echo ($cID == $row[0]) ? '<b>' . cOut($row[1]) . '</b>' : cOut($row[1]);
				echo '</div>';
				
				echo ($cnt % 2 == 1) ? '<div class="categoryListToolsHL">' : '<div class="categoryListTools">';
				echo '&nbsp;&nbsp;<a href="' . CalAdminRoot . '/index.php?com=categorymanage&amp;cID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>';
				echo ($rowCnt > 1) ? '&nbsp;<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>' : '';
				echo '&nbsp;</div>';
				
				$curCat = $row[0];
				$cnt++;
			}//end if
		}//end while
	} else {
		echo "<br />";
		echo $hc_lang_manage['NoCategory'];
	}//end if
	echo '</div>';	?>