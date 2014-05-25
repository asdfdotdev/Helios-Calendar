<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	$_SESSION['license'] = (((isset($_POST['agree']) && $_POST['agree'] == 'on')) || (isset($_SESSION['license']) && ($_SESSION['license'] == true))) ? true : false;
	
	if(!isset($_SESSION['license']) || $_SESSION['license'] == false){
		hc_fail();
	} else {
		$pass = 1;
		$cf_class = $cd_class = $up_class = $php_class = $mysql_class = $dbh_class = $dbn_class = $dbu_class = $dbp_class = $tbp_class = $cr_class = $ar_class = 
			$cn_class = $rnd_class = $mys_class = $curl_class = $gd_class = $ssl_class = $apc_class = 'error';
		$config_file = 'not found';
		$cache_dir = $up_dir = 'not writable';
		$php_ver = phpversion();
		$mysql_ver = 'Unknown';
		$mysql_stamp = 'Failed';
		$db_host = $db_name = $db_user = $db_pass = $tb_pre = $cal_root = $admin_root = $cal_name = 'Unset';
		$hc_rand = 'Unset or value too short.';
		
		if(file_exists(HCPATH.'/inc/config.php')){
			++$pass;
			$cf_class = 'ok';
			$config_file = 'found';}
		
		if(is_writable(HCPATH.'/cache')){
			++$pass;
			$cd_class = 'ok';
			$cache_dir = 'writable';}
			
		if(is_writable(HCPATH.'/uploads')){
			++$pass;
			$up_class = 'ok';
			$up_dir = 'writable';}
		
		if(!php_abort($php_ver)){
			++$pass;
			$php_class = 'ok';}
		if($php_class == 'ok' && !php_current($php_ver)){
			$php_class = 'old';}
		
		$phpLibs = array_map('strtolower',get_loaded_extensions());		
		if(in_array('curl',$phpLibs)){
			$curl_class = 'ok';}
		if(in_array('gd',$phpLibs)){
			$gd_class = 'ok';}
		if(in_array('openssl',$phpLibs)){
			$ssl_class = 'ok';}
		if(in_array('apc',$phpLibs)){
			$apc_class = 'ok';}
			
		try {
			$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME,$dbc);
			
			$result = mysql_query("SELECT VERSION();");
			if(mysql_result($result,0,0) != '')
				$mysql_ver = $_SESSION['mysql_version'] = mysql_result($result,0,0);
			if(!mysql_abort(mysql_result($result,0,0))){
				++$pass;
				$mysql_class = 'ok';}
			if($mysql_class == 'ok' && !mysql_current(mysql_result($result,0,0))){
				$mysql_class = 'old';}				
		} catch(Exception $e) {$mysql_ver = 'Unknown';}
		
		if(defined("DB_HOST") && DB_HOST != ''){
			++$pass;
			$db_host = DB_HOST;
			$dbh_class = 'ok';}
		
		if(defined("DB_NAME") && DB_NAME != ''){
			++$pass;
			$db_name = DB_NAME;
			$dbn_class = 'ok';}
		
		if(defined("DB_USER") && DB_USER != ''){
			++$pass;
			$db_user = DB_USER;
			$dbu_class = 'ok';}
		
		if(defined("DB_PASS") && DB_PASS != ''){
			$db_pass = '';
			for($i=0; $i < strlen(DB_PASS); $i++)
				$db_pass .= "x";
			
			$dbp_class = 'ok';}
		
		if(defined("HC_TblPrefix") && HC_TblPrefix != ''){
			++$pass;
			$tb_pre = HC_TblPrefix;
			$tbp_class = 'ok';}
		
		if(defined("CalRoot") && CalRoot != ''){
			++$pass;
			$cal_root = CalRoot;
			$cr_class = 'ok';}
		
		if(defined("AdminRoot") && AdminRoot != ''){
			++$pass;
			$admin_root = AdminRoot;
			$ar_class = 'ok';}
		
		if(defined("CalName") && CalName != ''){
			++$pass;
			$cal_name = CalName;
			$cn_class = 'ok';}
		
		if(defined("HC_Rando") && strlen(HC_Rando) >= 20){
			++$pass;
			$hc_rand = 'Set - '.strlen(HC_Rando).' characters.';
			$rnd_class = 'ok';}
			
		try {
			$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME,$dbc);
			
			$result = mysql_query("SELECT NOW();");
			if(mysql_result($result,0,0) != ''){
				$mysql_stamp = mysql_result($result,0,0);
				$mys_class = 'ok';
				++$pass;}
		} catch(Exception $e) {}
		
		echo '
		<fieldset>
			<legend>Files</legend>
			<label>config.php:</label>
			<span class="output '.$cf_class.'">
				'.$config_file.'
			</span>
		</fieldset>
		<fieldset>
			<legend>Directories</legend>
			<label>/cache:</label>
			<span class="output '.$cd_class.'">
				'.$cache_dir.'
			</span>
			<label>/uploads:</label>
			<span class="output '.$up_class.'">
				'.$up_dir.'
			</span>
		</fieldset>
		<fieldset>
			<legend>PHP &amp; MySQL</legend>
			<label>PHP Version:</label>
			<span class="output '.$php_class.'">
				'.$php_ver.'
			</span>
			<label>PHP Libraries:</label>
			<span class="output">
				<span class="'.$apc_class.'">APC</span>,
				<span class="'.$curl_class.'">cURL</span>,
				<span class="'.$gd_class.'">GD Graphics</span>,
				<span class="'.$ssl_class.'">OpenSSL</span>
			</span>

			<label>MySQL Version:</label>
			<span class="output '.$mysql_class.'">
				'.$mysql_ver.'
			</span>
		</fieldset>
		<fieldset>
			<legend>config.php Contents</legend>
			<label>DB_HOST:</label>
			<span class="output '.$dbh_class.'">
				'.$db_host.'
			</span>
			<label>DB_NAME:</label>
			<span class="output '.$dbn_class.'">
				'.$db_name.'
			</span>
			<label>DB_USER:</label>
			<span class="output '.$dbu_class.'">
				'.$db_user.'
			</span>
			<label>DB_PASS:</label>
			<span class="output '.$dbp_class.'">
				'.$db_pass.'
			</span>
			<label>HC_TblPrefix:</label>
			<span class="output '.$tbp_class.'">
				'.$tb_pre.'
			</span>
			<label>CalRoot:</label>
			<span class="output '.$cr_class.'">
				'.$cal_root.'
			</span>
			<label>AdminRoot:</label>
			<span class="output '.$ar_class.'">
				'.$admin_root.'
			</span>
			<label>CalName:</label>
			<span class="output '.$cn_class.'">
				'.$cal_name.'
			</span>
			<label>HC_Rando:</label>
			<span class="output '.$rnd_class.'">
				'.$hc_rand.'
			</span>
		</fieldset>
		<fieldset>
			<legend>MySQL Connection Test</legend>
			<label>Server Time:</label>
			<span class="output '.$mys_class.'">
				'.$mysql_stamp.'
			</span>
		</fieldset>
		
		<fieldset>
			<legend>Configuration Review</legend>
			<label>Results:</label>
			<span class="output">
				'.$pass.'/15 Steps Completed Successfully
			</span>
		</fieldset>
		<fieldset>
			<p>
				If you wish to make any changes to your config.php file please do so now and upload the changes before continuing. 
				You should refresh this page after making any changes to your config.php file.
			</p>
			<p>
				<span class="error">IMPORTANT:</span> Confirm the following, or your setup will fail:
				<ol>
					<li>The MySQL user (DB_USER) listed above must have at least Select/Insert/Update/Delete/Create/Alter privileges granted for the database (DB_NAME) listed above.</li>
					<li>The public calendar URL (CalRoot) listed above must match your current location minus "/setup/index.php?step=2".</li>
				</ol>
			</p>
		</fieldset>';
		
		if($pass >= 15){
			$_SESSION['review'] = true;
			echo '<input type="button" name="button" id="button" onclick="window.location.href=\'' . CalRoot . '/setup/index.php?step=3\';return false;" value="Continue" />';
		} else {
			$_SESSION['review'] = false;
			echo '<input type="button" name="button" id="button" onclick="window.location.href=\'index.php?step=2\';return false;" value="Refresh" />';
		}
	}
?>