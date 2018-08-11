<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (5,6,7) ORDER BY PkID");
	$keywords = cOut(mysql_result($result,0,0));
	$description = cOut(mysql_result($result,1,0));
	$allowindex = cOut(mysql_result($result,2,0));
?>
<script language="JavaScript"/>
function chkEmail(obj){
	var x = obj.value;
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(x)) {
		return 1;
	} else {
		return 0;
	}//end if
}//end chkMail()

function chkFrm(){
	var dirty = 0;
	var warn = 'Settings could not be saved for the following reasons:\n';
	
	if(document.frm.keywords.value == ''){
		dirty = 1;
		warn = warn + '\n*Keywords Text is Required';
	}//end if
	
	if(document.frm.description.value == ''){
		dirty = 1;
		warn = warn + '\n*Description Text is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease make the required changes and try again.');
		return false;
	}//end if
	
}//end if
</script>
<?php 
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Search Engine Optimization Settings Saved Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Search_Optimization", "Search Engine Optimization", "Use the form below to optimize your calendar for search engine indexing.<br><br><li><b>Indexing</b>: Do you want your calendar to be crawled by <a href=\"http://en.wikipedia.org/wiki/Web_spider\" target=\"new\" class=\"main\">web spiders</a>?<li><b>Keywords</b>: Search keywords under which you would like to be ranked.<br><li><b>Description</b>: Used to describe your site. [ <a href=\"http://en.wikipedia.org/wiki/Meta_tag\" target=\"new\" class=\"main\">More Info</a> ]");
?>
<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_SearchOptimizeAction;?>" onSubmit="return chkFrm();">
<br>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="eventMain" colspan="2">
			<b>Search Engine Optimization</b>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="105">Allow Indexing:</td>
		<td class="eventMain">
			<select name="indexing" id="indexing" class="input">
				<option <?if($allowindex == 1){echo "SELECTED";}//end if?> value="1">Yes</option>
				<option <?if($allowindex == 0){echo "SELECTED";}//end if?> value="0">No</option>
			</select>
			[ <a href="http://en.wikipedia.org/wiki/Robots.txt" class="main" target="_blank">What Is This?</a> ]&nbsp;&nbsp; (<i>If No Settings Below Ignored</i>)
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Keywords:</td>
		<td><input size="60" type="text" name="keywords" id="keywords" value="<?echo $keywords;?>" class="input"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" valign="top">Description:</td>
		<td><textarea name="description" id="description" rows="10" cols="60" class="input"><?echo $description;?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submit" id="submit" value=" Save Settings " class="button">
		</td>
	</tr>
</table>
</form>