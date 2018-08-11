<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (5,6,7) ORDER BY PkID");
	$keywords = cOut(mysql_result($result,0,0));
	$description = cOut(mysql_result($result,1,0));
	$allowindex = cOut(mysql_result($result,2,0));	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
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
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Search Engine Optimization Settings Saved Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Search_Optimization", "Search Engine Optimization", "Use the form below to optimize your calendar for search engine indexing.<br /><br /><b>Indexing</b>: Do you want your calendar to be crawled by <a href=\"http://en.wikipedia.org/wiki/Web_spider\" target=\"new\" class=\"main\">web spiders</a>?<br /><b>Keywords</b>: Search keywords under which you would like to be ranked.<br /><b>Description</b>: Used to describe your site. [ <a href=\"http://en.wikipedia.org/wiki/Meta_tag\" target=\"new\" class=\"main\">More Info</a> ]");	?>
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/SearchOptimizeAction.php";?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend>Meta Settings</legend>
		<div class="frmOpt">
			<label for="indexing">Indexing:</label>
			<select name="indexing" id="indexing">
				<option <?php if($allowindex == 1){echo "SELECTED";}//end if?> value="1">Yes, Allow Indexing</option>
				<option <?php if($allowindex == 0){echo "SELECTED";}//end if?> value="0">No, Prevent Indexing</option>
			</select>
			[ <a href="http://en.wikipedia.org/wiki/Robots.txt" class="main" target="_blank">What Is This?</a> ]&nbsp;&nbsp; (<i>If No Settings Below Ignored</i>)
		</div>
		<div class="frmOpt">
			<label for="keywords">Keywords:</label>
			<input name="keywords" id="keywords" size="60" maxlength="250" type="text" value="<?php echo $keywords;?>" />
		</div>
		<div class="frmOpt">
			<label for="description">Description:</label>
			<input name="description" id="description" size="60" maxlength="250" type="text" value="<?php echo $description;?>" />
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Save Settings " class="button" />
	</form>