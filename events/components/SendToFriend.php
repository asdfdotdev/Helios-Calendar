<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/sendtofriend.php');	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/javascript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_sendtofriend['Valid01'];?>";

	<?php	captchaValidation('1');?>
		
		if(document.frmSendToFriend.hc_fx1.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid03'];?>';
		}//end if
		
		if(document.frmSendToFriend.hc_fx2.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid04'];?>';
		} else {
			if(chkEmail(document.frmSendToFriend.hc_fx2) == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid05'];?>';
			}//end if
		}//end if
		
		if(document.frmSendToFriend.hc_fx3.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid06'];?>';
		}//end if
		
		if(document.frmSendToFriend.hc_fx4.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid07'];?>';
		} else {
			if(chkEmail(document.frmSendToFriend.hc_fx4) == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid08'];?>';
			}//end if
		}//end if
		
		if(document.frmSendToFriend.message.value.length > 250){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_sendtofriend['Valid09'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_sendtofriend['Valid10'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	
	function testCAPTCHA(){
		if(document.frmSendToFriend.proof.value != ''){
			var qStr = 'CaptchaCheck.php?capEntered=' + document.frmSendToFriend.proof.value;
			ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
		} else {
			alert('<?php echo $hc_lang_sendtofriend['Valid11'];?>');
		}//end if
	}//end testCAPTCHA()
	//-->
	</script>
	<br />
<?php	
	$hourOffset = date("G") + ($hc_cfg35);
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID) . " AND StartDate >= '" . date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y"))) . "'");
	
	if(hasRows($result)){
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_sendtofriend['Feed01']);
					break;	
			}//end switch
		}//end if
		
		echo $hc_lang_sendtofriend['SendLabelA'];
		echo "<br /><br />";
		echo "<b>" . mysql_result($result,0,1) . "</b>";
		echo "<br /><br />";
		echo $hc_lang_sendtofriend['SendLabelB'];
		echo "<br /><br />";?>
		<form name="frmSendToFriend" id="frmSendToFriend" method="post" action="<?php echo CalRoot;?>/components/SendToFriendAction.php" onsubmit="return chkFrm();">
<?php 	fakeFormFields();	?>
		<input type="hidden" name="eID" id="eID" value="<?php echo $eID;?>" />
<?php	if($hc_cfg65 > 0 && in_array(2, $hc_captchas)){
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_sendtofriend['Authentication'] . '</legend>';
			buildCaptcha();
			echo '</fieldset><br />';
		}//end if	?>
		<fieldset>
			<legend><?php echo $hc_lang_sendtofriend['CreateMsg'];?></legend>
			<div class="frmReq">
				<label for="hc_fx1"><?php echo $hc_lang_sendtofriend['MyName'];?></label>
				<input name="hc_fx1" id="hc_fx1" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx2"><?php echo $hc_lang_sendtofriend['MyEmail'];?></label>
				<input id="hc_fx2" name="hc_fx2" type="text" size="35" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx3"><?php echo $hc_lang_sendtofriend['FriendsName'];?></label>
				<input name="hc_fx3" id="hc_fx3" type="text" size="25" maxlength="100" />
		    </div>
		    <div class="frmReq">
				<label for="hc_fx4"><?php echo $hc_lang_sendtofriend['FriendsEmail'];?></label>
				<input name="hc_fx4" id="hc_fx4" type="text" size="35" maxlength="100" />
		    </div>
			<div class="frmReq">
				<label for="hc_fx5"><?php echo $hc_lang_sendtofriend['Message'] . "<br /><br /><b>" . $hc_lang_sendtofriend['MessageLimit'];?></b></label>
				<textarea name="hc_fx5" id="hc_fx5" rows="10" cols="10" style="width:80%;" onkeyup="this.value=this.value.slice(0, 250)"><?php echo $hc_lang_sendtofriend['SentMessage'];?></textarea>
			</div>
		</fieldset>
		<br />
		<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_sendtofriend['SendMessage'];?>" class="button" />
		<input name="cancel" id="cancel" type="button" value="<?php echo $hc_lang_sendtofriend['Cancel'];?>" class="button" onclick="window.location.href='<?php echo CalRoot;?>/?com=detail&amp;eID=<?php echo $eID;?>';return false;" />
		</form>
<?php 
	} else {	
		echo '<br />';
		echo $hc_lang_sendtofriend['NoEvent'];
		echo '<br /><br />';
		echo '<a href="' . CalRoot . '" class="eventMain">' . $hc_lang_sendtofriend['ThisWeekLink'] . '</a>';
	}//end if	?>