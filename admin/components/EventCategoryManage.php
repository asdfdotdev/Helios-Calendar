<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
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
	}//end if
?>

<script langauge="JavaScript">
function doDelete(dID){
	if(confirm('Are you sure you want to delete the category?\n\n          Ok = YES Delete Category\n          Cancel = NO Don\'t Delete Category')){
		window.location.href='<?echo CalAdminRoot . "/" . HC_EventCategoryManageAction;?>?dID=' + dID;
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
</script>
<?php 
	if (isset($_GET['msg'])){
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
?>
<?php
	appInstructions(0, "Category_Management", "Event Category Administration", "Use the form below to add, edit &amp; delete event categories.<br>The number of events assigned to each category is available beside the category name.");
?>
<br>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td width="45%" valign="top">
			<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_EventCategoryManageAction;?>" onSubmit="return chkFrm();">
			<input type="hidden" name="cID" id="cID" value="<?echo $cID;?>">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="3" class="eventMain"><b><?echo $where;?> Category</b>&nbsp;<?if($cID > 0){?>[&nbsp;<a href="<?echo CalAdminRoot . "/index.php?com=eventcategorymanage";?>" class="main">Add Category</a>&nbsp;]<?}//end if?></td>
				</tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
				<?php
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE PkID = " . cIn($cID));
					
					if(hasRows($result)){
						$category = mysql_result($result,0,1);
					} else {
						$category = "";
					}//end if
				?>
					<td width="50" class="eventMain">Name:</td>
					<td>
						<input type="text" name="name" id="name" value="<?echo $category;?>" class="input">
					</td>
					<td><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="5" height="1" alt="" border="0"></td>
					
				</tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" name="submit" id="submit" value=" Save Category " class="button">
					</td>
				</tr>
			</table>
			</form>
		</td>
		<td width="200" class="eventMain" valign="top">
			<?	$result = doQuery("SELECT PkID, CategoryName FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 ORDER BY CategoryName");
				if(hasRows($result)){
					?>
					
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="200" class="eventMain"><b>Available Categories</b></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr><td colspan="5" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
						<tr><td colspan="5"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
						<tr>
					<?php
						$cnt = 0;
						$curCat = "";
						while($row = mysql_fetch_row($result)){
							if($curCat != $row[0]){
						?>
							<tr><td colspan="5" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
							<tr>
								<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>&nbsp;<?if($cID == $row[0]){echo "<b>" . cOut($row[1]) . "</b>";} else {echo cOut($row[1]);}//end if?></td>
								<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=eventcategorymanage&cID=<?echo $row[0];?>" class="main" title="Edit Category"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0"></a>&nbsp;&nbsp;</td>
								<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="javascript:;" onClick="doDelete('<?echo $row[0];?>');return false;" class="main" title="Delete Category"><img src="<?echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0"></a></td>
								<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="5" height="1" alt="" border="0"></td>
							</tr>
							<tr><td colspan="5" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
						<?
							$curCat = $row[0];
							$cnt++;
							}//end if
							
						}//end while
					?>
						</tr>
						<tr><td colspan="5"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
						<tr><td colspan="5" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
					</table>
				<?
				} else {
				?>
					<br><br>
					There are currently no calendar categories.
				<?
				}//end if
			?>
		</td>
	</tr>
</table>