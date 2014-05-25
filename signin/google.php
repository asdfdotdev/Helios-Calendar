<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	action_headers();
	
	if(user_check_status() || $hc_cfg[115] == 0 || isset($_GET['error']))
		go_home();
	
	$target = CalRoot.'/index.php';
	$callback_url = CalRoot.'/signin/google.php';
	$client_id = $client_secret = '';
	$_SESSION['Google_State'] = (!isset($_SESSION['Google_State'])) ? md5(mt_rand().date("U")) : $_SESSION['Google_State'];
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(124,125)");
	if(hasRows($result)){
		$client_id = mysql_result($result,0,0);
		$client_secret = mysql_result($result,1,0);
	}
	
	if($client_id != '' && $client_secret != ''){
		if(isset($_GET['state']) && isset($_GET['code'])){
			if(!isset($_SESSION['Google_State']) || $_SESSION['Google_State'] != $_GET['state']){
				session_destroy();
				go_home();
			}
			
			$code = cIn(strip_tags($_GET['code']));
			
			if(!$fp = fsockopen("ssl://accounts.google.com", 443, $errno, $errstr, 20))
				$fp = fsockopen("accounts.google.com", 80, $errno, $errstr, 20);
			
			if($fp){
				$read = '';
				$post_me = array(
				    'code'		=>	$code,
				    'client_id'	=>	$client_id,
				    'client_secret' =>	$client_secret,
				    'redirect_uri'	=>	$callback_url,
				    'grant_type'	=>	'authorization_code'
				);
				$content = http_build_query($post_me);
				
				
				$request = "POST /o/oauth2/token HTTP/1.1\r\nHost: accounts.google.com\r\nContent-Type: application/x-www-form-urlencoded\r\n";
				$request .= "Content-Length: ".strlen($content)."\r\n";
				$request .= "Connection: Close\r\n\r\n";
				$request .= $content;
				
				fwrite($fp, $request);
				while(!feof($fp)) {
					$read .= fread($fp,1024);
				}
				fclose($fp);
				
				$break = strpos($read,"{",strpos(strtolower($read),"connection:"));
				$response = json_decode(substr($read,$break));
				if(isset($response->access_token))
					$user = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$response->access_token));
				
				if(isset($user->id)){
					$result = doQuery("SELECT PkID, Email, Birthdate FROM " . HC_TblPrefix . "users WHERE NetworkType = '3' AND NetworkID = '" . cIn($user->id) . "'");
					$user_name = (isset($user->name)) ? $user->name : '';
					
					if(!hasRows($result)){
						$local_id = user_register_new(3,$user_name,$user->id);
						$_SESSION['new_user'] = true;
						if(isset($user->birthday))
							$_SESSION['new_user_bday'] = $user->birthday;
						if(isset($user->email))
							$_SESSION['new_user_email'] = $user->email;
					} else {
						$local_id = mysql_result($result,0,0);
						if(mysql_result($result,0,1) == '' || mysql_result($result,0,2) == ''){
							$_SESSION['new_user'] = true;
							if(isset($user->birthday))
								$_SESSION['new_user_bday'] = $user->birthday;
							if(isset($user->email))
								$_SESSION['new_user_email'] = $user->email;
						}
					}

					$_SESSION['UserNetToken'] = $response->access_token;
					$_SESSION['UserNetSecret'] = NULL;

					user_update_status(3, $user_name, $user->id, 1);
					user_update_history($local_id);
					unset($_SESSION['Google_State']);
					
					$target = CalRoot . '/index.php?com=acc';
				}
			}				
		} else {
			$target = 'https://accounts.google.com/o/oauth2/auth?client_id='.urlencode($client_id).'&redirect_uri='.urlencode($callback_url).'&state='.urlencode($_SESSION['Google_State']).'&response_type=code&scope='.urlencode('https://www.googleapis.com/auth/userinfo.profile');
		}
	}
	
	header('Location: ' . $target);
?>
