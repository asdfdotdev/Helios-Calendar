<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/register.php');
	
	$rID = (isset($_GET['rID']) && is_numeric($_GET['rID'])) ? cIn($_GET['rID']) : 0;
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(2, $hc_lang_register['Feed01']);
				break;
		}//end switch
	}//end if	
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE PkID = " . cIn($rID));
	if(hasRows($result)){
		$instTitle = $hc_lang_register['TitleRegisterE'];
		$instText = $hc_lang_register['InstructRegisterE'];
		$name = mysql_result($result,0,1);
		$email = mysql_result($result,0,2);
		$phone = mysql_result($result,0,3);
		$address = mysql_result($result,0,4);
		$address2 = mysql_result($result,0,5);
		$city = mysql_result($result,0,6);
		$state = mysql_result($result,0,7);
		$postal = mysql_result($result,0,8);
	} else {
		$instTitle = $hc_lang_register['TitleRegisterA'];
		$instText = $hc_lang_register['InstructRegisterA'];
		$name = "";
		$email = "";
		$phone = "";
		$address = "";
		$address2 = "";
		$city = "";
		$state = $hc_cfg21;
		$postal = "";
	}//end if
	
	appInstructions(0, "", $instTitle, $instText);
?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_register['Valid01'];?>";
	
		if(document.frm.name.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_register['Valid02'];?>';
		}//end if
		
		if(document.frm.email.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_register['Valid03'];?>';
		} else {
			if(chkEmail(document.frm.email) == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_register['Valid04'];?>';
			}//end if
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_register['Valid05'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	//-->
	</script>
	<form id="frm" name="frm" method="post" action="<?php echo CalAdminRoot . "/components/RegisterAddAction.php";?>" onsubmit="return chkFrm();">
	<input name="oldemail" id="oldemail" type="hidden" value="<?php echo $email;?>" />
	<input name="eventID" id="eventID" type="hidden" value="<?php echo $_GET['eID'];?>" />
	<input name="rID" id="rID" type="hidden" value="<?php echo $rID;?>" />
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_register['RegLabel'];?></legend>
		<div class="frmReq">
			<label for="name"><?php echo $hc_lang_register['Name'];?></label>
			<input name="name" id="name" type="text" size="20" maxlength="50" value="<?php echo $name;?>" />
		</div>
		<div class="frmReq">
			<label for="name"><?php echo $hc_lang_register['Email'];?></label>
			<input name="email" id="email" type="text" size="40" maxlength="75" value="<?php echo $email;?>" />
		</div>
		<div class="frmOpt">
			<label for="phone"><?php echo $hc_lang_register['Phone'];?></label>
			<input name="phone" id="phone" type="text" size="20" maxlength="25" value="<?php echo $phone;?>" />
		</div>
		<div class="frmOpt">
			<label for="address"><?php echo $hc_lang_register['Address'];?></label>
			<input name="address" id="address" type="text" size="25" maxlength="75" value="<?php echo $address;?>" />
		</div>
		<div class="frmOpt">
			<label for="address2"><?php echo $hc_lang_register['Address2'];?></label>
			<input name="address2" id="address2" type="text" size="25" maxlength="75" value="<?php echo $address2;?>" />
		</div>
	<?php
		$inputs = array(1 => array('City','city',$city),2 => array('Postal','zip',$postal));

		echo '<div class="frmOpt">';
		$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
		$second = ($first == 1) ? 2 : 1;

		echo '<label for="' . $inputs[$first][1] . '">' . $hc_lang_register[$inputs[$first][0]] . '</label>';
		echo '<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" value="' . $inputs[$first][2] . '" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';

		if($hc_lang_config['AddressRegion'] != 0){
			echo '<div class="frmOpt">';
			echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
			include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);
			echo '<span style="color: #0000FF;">*</span></div>';
		}//end if

		echo '<div class="frmOpt">';
		echo '<label for="' . $inputs[$second][1] . '">' . $hc_lang_register[$inputs[$second][0]] . '</label>';
		echo '<input name="' . $inputs[$second][1] . '" id="' . $inputs[$second][1] . '" value="' . $inputs[$second][2] . '" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';
		?>


	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_register['SaveReg'];?>" class="button" />&nbsp;&nbsp;
	<input name="cancel" id="cancel" type="button" value="  <?php echo $hc_lang_register['Cancel'];?>  " onclick="window.location.href='<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $_GET['eID'];?>';" class="button" />
	</form>