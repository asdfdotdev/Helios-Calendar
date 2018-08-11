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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');	
	
	appInstructions(0, "Searching_Recipients", $hc_lang_news['TitleSearchR'], $hc_lang_news['InstructSearchR']);	?>
	
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(document.frm.name.value == '' && document.frm.email.value == ''){
			alert('<?php echo $hc_lang_news['Valid11'];?>');
			return false;
		}//end if
	}//end chkFrm
	//-->
	</script>
	<div style="width:60%;">
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/index.php?com=userbrowse";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="search" id="search" value="1" />
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_news['Search'];?></legend>
		<div class="frmReq">
			<label for="name"><?php echo $hc_lang_news['LName'];?></label>
			<input name="name" id="name" type="text" size="25" maxlength="50" value="" />
		</div>
		<div class="frmReq">
			<label for="email"><?php echo $hc_lang_news['Email'];?></label>
			<input name="email" id="email" type="text" size="30" maxlength="75" value="" />
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_news['SearchB'];?> " class="button" />
	</form>
	</div>