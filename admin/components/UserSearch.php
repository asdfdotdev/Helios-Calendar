<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/newsletter.php');	
	
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
	<div style="width:70%;">
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . '/index.php?com=userbrowse';?>" onsubmit="return chkFrm();">
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