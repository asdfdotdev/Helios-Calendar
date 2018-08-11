<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
		$cID = $_GET['cID'];
		$where = "Edit";
	} else {
		$cID = 0;
		$where = "Add";
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('Are you sure you want to delete the category?\n\n          Ok = YES Delete Category\n          Cancel = NO Don\'t Delete Category')){
			window.location.href='<?echo CalAdminRoot . "/components/CategoryManageAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	
	function chkFrm(){
		var dirty = 0;
		var warn = 'Category could not be saved for the following reasons:\n';
		
		if(document.frm.name.value == ''){
			dirty = 1;
			warn = warn + '\n*Category Name is Required';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\nPlease make the required changes and try again.');
			return false;
		}//end if
	}//end chkFrm()
	//-->
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Category Updated Successfully!");
				break;
				
			case "2" :
				feedback(1, "Category Added Successfully!");
				break;
				
			case "3" :
				feedback(1,"Category Deleted Successfully!");
				break;
				
		}//end switch
	}//end if
	
	$category = "";
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE PkID = " . cIn($cID));
	if(hasRows($result)){
		$category = mysql_result($result,0,1);
	}//end if
	
	appInstructions(0, "Category_Management", "Event Category Administration", "Use the form below to add, edit &amp; delete event categories.");	?>
	<br />
	<div style="float:left;width:45%;padding:0px 15px 0px 0px;">
	<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/components/CategoryManageAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="cID" id="cID" value="<?echo $cID;?>" />	
	<fieldset style="padding-top:5px;">
		<legend><?echo $where;?> Category<?if($cID > 0){?>&nbsp;&nbsp;( <a href="<?echo CalAdminRoot;?>/index.php?com=categorymanage" class="main">Add New</a> )<?}?></legend>
		<div class="frmReq">
			<label>Name:</label>
			<input type="text" name="name" id="name" value="<?echo $category;?>" />
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Save Category " class="button" />
	</form>
	</div>
	<div style="float:left;width:50%;padding-top:15px;">
	<?	$result = doQuery("SELECT PkID, CategoryName FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 ORDER BY CategoryName");
		$rowCnt = mysql_num_rows($result);
		if(hasRows($result)){	
			$cnt = 0;
			$curCat = "";
			while($row = mysql_fetch_row($result)){
				if($curCat != $row[0]){	?>
				<div class="categoryListTitle<?if($cnt % 2 == 1){echo "HL";}?>"><?if($cID == $row[0]){echo "<b>" . cOut($row[1]) . "</b>";} else {echo cOut($row[1]);}//end if?></div>
				<div class="categoryListTools<?if($cnt % 2 == 1){echo "HL";}?>"><a href="<?echo CalAdminRoot;?>/index.php?com=categorymanage&amp;cID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a><?if($rowCnt > 1){?>&nbsp;<a href="javascript:;" onclick="doDelete('<?echo $row[0];?>');return false;" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="middle" /></a><?}//end if?></div>
			<?	$curCat = $row[0];
				$cnt++;
				}//end if
				
			}//end while
		} else {	?>
			<br /><br />
			There are currently no calendar categories.
	<?	}//end if	?>
	</div>