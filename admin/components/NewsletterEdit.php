<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
if(isset($_GET['nID']) && is_numeric($_GET['nID'])){
	
	$whereAmI = "Add";
	$nID = 0;
	$name = "";
	$source = "";
	
	if($_GET['nID'] > 0){
		$nID = $_GET['nID'];
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE PkID = " . $nID);
		if(hasRows($result)){
			$name = mysql_result($result, 0, "TemplateName");
			$source = mysql_result($result, 0, "TemplateSource");
			$whereAmI = "Edit";
		}//end if
	}//end if
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Newsletter Template Was Updated Successfully!");
				break;
				
			case "2" :
				feedback(1,"Newsletter Template Was Added Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Event Newsletter Templates", "Select the template you wish to edit below, or click \"Add New Template\" to add a new template.");
	?>
<script language="JavaScript">
function chkFrm(){
dirty = 0;
warn = "Template could not be saved for the following reason(s):";
	
	if(document.frm.tempname.value == ''){
		dirty = 1;
		warn = warn + '\n*Template Name is Required';
	}//end if
	
	if(document.frm.tempsource.value == ''){
		dirty = 1;
		warn = warn + '\n*Template Source is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease complete the form and try again.');
		return false;
	} else {
		return true;
	}//end if
}//end chkFrm()
</script>
	<br>
	<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit" class="main">&laquo;&laquo;Return to Newsletter Template List</a></div>
	<br>
	<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_NewsletterEditAction;?>" onSubmit="return chkFrm();">
	<input type="hidden" name="nID" id="nID" value="<?echo $nID;?>">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="top">
				
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td class="main" width="100">Template Name:</td>
						<td><input size="49" maxlength="250" type="text" name="tempname" id="tempname" value="<?echo $name;?>" class="input"></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td colspan="2"><textarea name="tempsource" id="tempsource" rows="25" cols="63" class="input"><?echo $source;?></textarea></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td colspan="2">
						<input type="submit" name="submit" value=" Save Template " class="button">&nbsp;&nbsp;
						<input type="reset" name="reset" id="reset" value=" Reset Template " class="button">
						</td>
					</tr>
				</table>
				
			</td>
			<td width="120" valign="top">
				<table cellpadding="5" cellspacing="0" border="0"><tr><td class="instructions">
				<table cellpadding="0" cellspacing="0" border="0" width="120">
					<tr>
						<td class="eventMain">[events]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[events]</i><br>Replaces with event information for the category the recipients has requested.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[firstname]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[firstname]</i><br>Replaces with recipients first name.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[lastname]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[lastname]</i><br>Replaces with recipients last name.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[email]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[email]</i><br>Replaces with recipients email address.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[zip]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[zip]</i><br>Replaces with recipient zip code.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[billboard]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[billboard]</i><br>Replaces with the event billboard.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[billboard-today]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[billboard-today]</i><br>Replaces with event billboard of todays events.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[most-viewed]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[most-viewed]</i><br>Replaces with event billboard of most viewed events.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[event-count]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[event-count]</i><br>Replaces with count of active event totals.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[calendarurl]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[calendarurl]</i><br>Replaces with the address of the calendar.<br><br>" . CalRoot);?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[unsubscribe]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[unsubscribe]</i><br>Replaces with a link to unsubscribe from the newsletter.");?></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">[editregistration]</td>
						<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "<b>Variable:</b> <i>[editregistration]</i><br>Replaces with a link to edit the categories they are registered for.");?></td>
					</tr>
				</table>
				</td></tr></table>
				
			</td>
		</tr>
	</table>
	</form>
<?
} else {
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Newsletter Template Deleted Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Event Newsletter Templates", "Select the template you wish to edit below. Or click \"Add New Template\" to add a new template.");
?>
<script language="JavaScript">
function doDelete(dID){
	if(confirm('Template Delete Is Permanent!\nAre you sure you want to delete the template?\n\n          Ok = YES Delete Template\n          Cancel = NO Don\'t Delete Template')){
		document.location.href = '<?echo CalAdminRoot . "/" . HC_NewsletterEditAction;?>?dID=' + dID;
	}//end if
}//end doDelete

function templatePreview(pID){
	if (document.all)
        var xMax = screen.width, yMax = screen.height;
    else
        if (document.layers)
            var xMax = window.outerWidth, yMax = window.outerHeight;
        else
            var xMax = 800, yMax=600;

    var xOffset = (xMax - 600)/2, yOffset = (yMax - 400)/2
	
	previewWin = window.open("<?echo CalAdminRoot;?>/components/NewsletterPreview.php?pID=" + pID,"templatePreview","screenX=" + xOffset + ",screenY=" + yOffset + ",resizable=no,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,copyhistory=0,width=600,height=400");
	previewWin.focus();
}//
</script>

	<br>
	<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=newsletter" class="main">&laquo;&laquo;Return to Newsletter Generator</a></div>
	<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 AND PkID ORDER BY TemplateName");
	?>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan="4" class="eventMain"><b>Template Name</b> [ <a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit&nID=0" class="main">Add New Template</a> ]</td>
			</tr>
			<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
			<tr><td colspan="4" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
			<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				
		<?
	if(hasRows($result)){
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
			?>
				<tr>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> width="200"><?echo $row[1]?></td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> width="20"><a href="javascript:;" onClick="templatePreview('<?echo $row[0];?>');" class="main" title="Preview Template"><img src="<?echo CalAdminRoot;?>/images/iconView.gif" width="15" height="15" alt="" border="0" title="Preview Template"></a></td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> width="20"><a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit&nID=<?echo $row[0];?>" class="main" title="Edit Template"><img src="<?echo CalAdminRoot;?>/images/iconEdit.gif" width="15" height="15" alt="" border="0" title="Edit Template"></a></td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="javascript:doDelete('<?echo $row[0];?>');" class="main" title="Delete Template"><img src="<?echo CalAdminRoot;?>/images/iconDelete.gif" width="15" height="15" alt="" border="0" title="Delete Template"></a></td>
				</tr>
				<tr><td colspan="3" class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="2" alt="" border="0"></td></tr>
			
			<?
				$cnt++;
			}//end while

	} else {
	?>
		<tr>
			<td class="eventMain">
				There are currently no templates available.<br>
				Please click "Add New Template" above to add a template.
			</td>
		</tr>
	<?
	}//end if
		?>
		<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr><td colspan="4" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	</table>
	<?
}//end if
?>