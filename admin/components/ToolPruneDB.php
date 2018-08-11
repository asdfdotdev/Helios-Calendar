<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/tools.php');
	
	appInstructions(0, "Prune_Database", $hc_lang_tools['TitlePrune'], $hc_lang_tools['InstructPrune']);
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_tools['Feed03']);
				break;
				
		}//end switch
	}//end if	?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(confirm('<?php echo $hc_lang_tools['Valid13'] . "\\n\\n          " . $hc_lang_tools['Valid14'] . "\\n          " . $hc_lang_tools['Valid15'];?>')){
			return true;
		} else {
			return false;
		}//end if
	}//end updateLink()
	//-->
	</script>
<?php
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	if(hasRows($result)){	?>
		<b><?php echo $hc_lang_tools['Database'] . " " . mysql_result($result,0,0) . " " . $hc_lang_tools['Deleted'];?></b>
		<br /><br />
<?php 	if(mysql_result($result,0,0) > 0){	?>
		<form name="frmToolPrune" id="frmToolPrune" method="post" action="<?php echo CalAdminRoot . "/components/ToolPruneDBAction.php";?>" onsubmit="return chkFrm();">
		<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_tools['DoPrune'];?> " class="button" />
		</form>
<?php 	}//end if
	} else {
		echo "<b>" . $hc_lang_tools['NoPrune'] . "</b>";
	}//end if?>