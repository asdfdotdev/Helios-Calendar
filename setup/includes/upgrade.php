<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!preg_match('/^http:\/\/'.strtolower($_SERVER['SERVER_NAME']).'/', strtolower(CalRoot))){
		echo '<a href="'.CalRoot.'/setup/">Current URL does not match CalRoot. Click here to run the upgrade.</a>';
		return(-1);
	}
	
	if(!isset($_POST['uID'])){
		try {
			$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME,$dbc);
			
			$result = mysql_query("SELECT SettingValue, VERSION() FROM " . HC_TblPrefix."settings WHERE PkID = 49");
			if(mysql_result($result,0,0) != ''){
				$_SESSION['mysql_version'] = mysql_result($result,0,1);
				$db_ver = mysql_result($result,0,0);
			}
		} catch(Exception $e) {}
		
		echo '
			<p>
				<b>Step 0)</b> Make a backup of your Helios Calendar database.
			</p>
			<p>
				<b>Step 1)</b> Confirm your Helios Calendar <b>MySQL user has ALTER &amp; CREATE privileges</b> for your Helios database.
			</p>
			<p>
				<b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".
			</p>
			<p>
				<b>Step 3)</b> When the upgrade is complete, delete the /setup directory and cache files from your /cache directory.
			</p>
			<div class="notice">
				<p>Your license &amp; subscription status will be verified. Upgrades are only available to eligible subscribers.</p>
			</div>
			'.(isset($db_ver) ? '<p><b>Current Database Version:</b>&nbsp;'.$db_ver.'</p>':'').'
			<form name="frm" id="frm" method="post" action="index.php" onsubmit="return validate();">
			<fieldset>
				<legend>Upgrade to Run</legend>
				<select name="uID" id="uID" required="required">
					<option value="0">Select Current Version</option>
					<option value="15">2.2</option>
					<option value="14">2.1</option>
					<option value="13">2.0.2</option>
					<option value="12">2.0.1</option>
					<option value="11">2.0</option>
					<option value="10">1.7.1</option>
					<option value="9">1.7</option>
					<option value="8">1.6.1</option>
					<option value="7">1.6</option>
					<option value="6">1.5.2</option>
					<option value="5">1.5.1</option>
					<option value="4">1.5</option>
					<option value="3">1.4.1</option>
					<option value="2">1.4</option>
					<option value="1">1.3.1</option>
				</select>
				&nbsp;&nbsp;-->&nbsp;&nbsp;<b>'.$curVersion.'</b>
			</fieldset>
			<input name="submit" id="submit" type="submit" value=" Run Upgrade " />
			</form>
			

			<script>
			//<!--
			function validate(){
				if(document.getElementById(\'uID\').value != 0){
					return true;
				} else {
					alert("To run the upgrade you must select your current version.");
					return false;
				}
			}
			//-->
			</script>';
	} else {
		set_time_limit(300);
		$status = $no_file = 0;
		echo '
			<p>
				<strike><b>Step 1)</b> Confirm that your Helios <b>MySQL User has ALTER &amp; CREATE permissions</b> for your Helios database.</strike>
			</p>
			<p>
				<strike><b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".</strike>
			</p>';
		echo '
			<fieldset style="padding:10px;">';

		function doUpgrade($status, $msg, $query){
			echo '<div style="padding-left:5px;line-height:15px;">' . $msg;
			if(mysql_query($query)){
				echo '<b>Finished</b>';
			} else {
				++$status;
				echo '<span class="error">Failed</span>';
			}
			echo '</div>';
			return $status;
		}
		
		switch($_POST['uID']){
			case 1:
				echo ($_POST['uID'] == 1) ? '<div class="utitle">Upgrading from 1.3.1 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/54acb57afb.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/54acb57afb.txt')));
				else
					++$no_file;

			case 2:
				echo ($_POST['uID'] == 2) ? '<div class="utitle">Upgrading from 1.4 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/9e605ce3bb.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/9e605ce3bb.txt')));
				else
					++$no_file;

			case 3:
				echo ($_POST['uID'] == 3) ? '<div class="utitle">Upgrading from 1.4.1 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/1e6dbfaaa0.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/1e6dbfaaa0.txt')));
				else
					++$no_file;

			case 4:
				echo ($_POST['uID'] == 4) ? '<div class="utitle">Upgrading from 1.5 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/6008647277.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/6008647277.txt')));
				else
					++$no_file;

			case 5:
				echo ($_POST['uID'] == 5) ? '<div class="utitle">Upgrading from 1.5.1 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/3ef04b1c4b.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/3ef04b1c4b.txt')));
				else
					++$no_file;

			case 6:
				echo ($_POST['uID'] == 6) ? '<div class="utitle">Upgrading from 1.5.2 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/0cea24ae3b.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/0cea24ae3b.txt')));
				else
					++$no_file;

			case 7:
				echo ($_POST['uID'] == 7) ? '<div class="utitle">Upgrading from 1.6 to ' . $curVersion . '</div>' : '';

			case 8:
				echo ($_POST['uID'] == 8) ? '<div class="utitle">Upgrading from 1.6.1 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/e34cf9774e.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/e34cf9774e.txt')));
				else
					++$no_file;

			case 9:
				echo ($_POST['uID'] == 9) ? '<div class="utitle">Upgrading from 1.7 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/7fd3cdaaba.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/7fd3cdaaba.txt')));
				else
					++$no_file;

			case 10:
				echo ($_POST['uID'] == 10) ? '<div class="utitle">Upgrading from 1.7.1 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/91a5707680.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/91a5707680.txt')));
				else
					++$no_file;

			case 11:
				echo ($_POST['uID'] == 11) ? '<div class="utitle">Upgrading from 2.0 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/x487kj3021.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/x487kj3021.txt')));
				else
					++$no_file;

			case 12:
				echo ($_POST['uID'] == 12) ? '<div class="utitle">Upgrading from 2.0.1 to ' . $curVersion . '</div>' : '';


			case 13:
				echo ($_POST['uID'] == 13) ? '<div class="utitle">Upgrading from 2.0.2 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/xf36985r3d.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/xf36985r3d.txt')));
				else
					++$no_file;

			case 14:
				echo ($_POST['uID'] == 14) ? '<div class="utitle">Upgrading from 2.1 to ' . $curVersion . '</div>' : '';
				if(file_exists(HCSETUP.'/data/knt47fcb2m.txt'))
					eval(base64_decode(file_get_contents(HCSETUP.'/data/knt47fcb2m.txt')));
				else
					++$no_file;
			
			case 15:
				echo ($_POST['uID'] == 15) ? '<div class="utitle">Upgrading from 2.2 to ' . $curVersion . '</div>' : '';


			//	All Upgrades
				mysql_query("UPDATE `" . HC_TblPrefix . "settings` SET `SettingValue` = '" . $curVersion . "' WHERE PkID = '49'");
				echo '
			</fieldset>

			<fieldset style="padding:10px;">
				<legend>Upgrade Results</legend>';

				if($status == 0 && $no_file == 0){
					echo '
				<p>
					Upgrade Successful.<br /><br />Delete the /setup directory and your cache files to complete the upgrade.
				</p>';
				} else {
					echo ($no_file > 0) ? '<p><b>One or more required upgrade data files are missing.</b></p>' : '';
					echo '
				<p>
					One or more of the upgrades failed. This is most commonly caused by improper configuration of your MySQL user privileges. You may need to restore your database from a backup before running this upgrade again.
				</p>';
				}
			break;
		}
		
		echo '</fieldset>';
	}
?>