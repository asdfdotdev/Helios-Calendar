<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
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
				$whereAmI = "Edit";
				$name = mysql_result($result, 0, "TemplateName");
				$source = mysql_result($result, 0, "TemplateSource");
			}//end if
		}//end if
		
		appInstructions(0, "Edit_Newsletter_Templates", "Event Newsletter Templates", "Select the template you wish to edit below, or click \"Add New Template\" to add a new template.");	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
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
		//-->
		</script>
		<div style="float:left;width:75%;">
		<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_NewsletterEditAction;?>" onsubmit="return chkFrm();">
		<input type="hidden" name="nID" id="nID" value="<?echo cOut($nID);?>" />
		<br />
	<fieldset>
			<legend>Newsletter Template</legend>
			<div class="frmReq">
				<label for="tempname">Name:</label>
				<input name="tempname" id="tempname" type="text" size="40" maxlength="250" value="<?echo cOut($name);?>" />
			</div>
			<textarea name="tempsource" id="tempsource" rows="25" cols="60"><?echo cOut($source);?></textarea>
		</fieldset>
		<br />
		<input type="submit" name="submit" value=" Save Template " class="button" />&nbsp;&nbsp;
		<input type="reset" name="reset" id="reset" value=" Reset Template " class="button" />
		</form>
		</div>
		<div style="float:left;width:20%;padding:15px 0px 0px 5px;">
			<br />
			<table cellpadding="5" cellspacing="0" border="0"><tr><td class="instructions">
			<table cellpadding="0" cellspacing="0" border="0" width="120">
				<tr>
					<td class="eventMain">[events]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[events]&lt;/i&gt;&lt;br /&gt;Replaces with event information for the category the recipients has requested.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[firstname]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[firstname]&lt;/i&gt;&lt;br /&gt;Replaces with recipients first name.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[lastname]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[lastname]&lt;/i&gt;&lt;br /&gt;Replaces with recipients last name.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[email]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[email]&lt;/i&gt;&lt;br /&gt;Replaces with recipients email address.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[zip]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[zip]&lt;/i&gt;&lt;br /&gt;Replaces with recipient zip code.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[billboard]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[billboard]&lt;/i&gt;&lt;br /&gt;Replaces with the event billboard.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[billboard-today]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[billboard-today]&lt;/i&gt;&lt;br /&gt;Replaces with event billboard of todays events.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[most-viewed]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[most-viewed]&lt;/i&gt;&lt;br /&gt;Replaces with event billboard of most viewed events.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[event-count]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[event-count]&lt;/i&gt;&lt;br /&gt;Replaces with count of active event totals.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[calendarurl]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[calendarurl]&lt;/i&gt;&lt;br /&gt;Replaces with the address of the calendar.<br /><br />" . CalRoot);?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[unsubscribe]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[unsubscribe]&lt;/i&gt;&lt;br /&gt;Replaces with a link to unsubscribe from the newsletter.");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
				<tr>
					<td class="eventMain">[editregistration]</td>
					<td width="25" align="right"><? appInstructionsIcon("Newsletter Variables", "&lt;b&gt;Variable:&lt;/b&gt; &lt;i&gt;[editregistration]&lt;/i&gt;&lt;br /&gt;Replaces with a link to edit the categories they are registered for.");?></td>
				</tr>
			</table>
			</td></tr></table>
		</div>
<?	} else {
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,"Newsletter Template Deleted Successfully!");
					break;
					
				case "2" :
					feedback(1,"Newsletter Template Was Updated Successfully!");
					break;
					
				case "3" :
					feedback(1,"Newsletter Template Was Added Successfully!");
					break;
			}//end switch
		}//end if
		
		appInstructions(0, "Edit_Newsletter_Templates", "Event Newsletter Templates", "Select the template you wish to edit below. Or click \"Add New Template\" to add a new template.");	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('Template Delete Is Permanent!\nAre you sure you want to delete the template?\n\n          Ok = YES Delete Template\n          Cancel = NO Don\'t Delete Template')){
			document.location.href = '<?echo CalAdminRoot . "/" . HC_NewsletterEditAction;?>?dID=' + dID;
		}//end if
	}//end doDelete
	
	function templatePreview(pID){
		if(document.all){
			var xMax = screen.width, yMax = screen.height;
		} else {
			if (document.layers){
				var xMax = window.outerWidth, yMax = window.outerHeight;
			} else {
				var xMax = 800, yMax=600;
			}//end if
		}//end if
	    var xOffset = (xMax - 600)/2, yOffset = (yMax - 400)/2
		
		previewWin = window.open("<?echo CalAdminRoot;?>/components/NewsletterPreview.php?pID=" + pID,"templatePreview","screenX=" + xOffset + ",screenY=" + yOffset + ",resizable=no,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,copyhistory=0,width=600,height=400");
		previewWin.focus();
	}//end templatePreview()
	//-->
	</script>
<?	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "newsletters WHERE IsActive = 1 AND PkID ORDER BY TemplateName");	?>
	<div class="newsletterList">Template Name&nbsp;&nbsp;(<a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit&amp;nID=0" class="main">Add New Template</a>)</div>
	<?	if(hasRows($result)){
			$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="newsletterTitle<?if($cnt % 2 == 1){echo "HL";}?>"><?echo cOut($row[1])?></div>
				<div class="newsletterTools<?if($cnt % 2 == 1){echo "HL";}?>"><a href="javascript:;" onclick="templatePreview('<?echo $row[0];?>');" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconView.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;<a href="<?echo CalAdminRoot;?>/index.php?com=newsletteredit&amp;nID=<?echo cOut($row[0]);?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;<a href="javascript:doDelete('<?echo cOut($row[0]);?>');" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
			<?	$cnt++;
			}//end while
		} else {	?>
			<div class="frmOpt">
				There are currently no templates available.<br />
				Please click "Add New Template" above to add a template.
			</div>
	<?	}//end if	?>
<?	}//end if	?>