<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	include(HCPATH.HCINC.'/functions/api.php');
	include_once(HCLANG . '/public/api.php');
	define('APIVersion', "$hc_cfg[133]");
	
	header('content-type: text/plain; charset=' . $hc_lang_config['CharSet']);

	api_active();
	
	$user = (isset($_GET['u'])) ? utf8_decode(cIn(htmlspecialchars(strip_tags(cleanBreaks($_GET['u']))))) : '';
	$key = (isset($_GET['k'])) ? cIn(htmlspecialchars(strip_tags(cleanBreaks($_GET['k'])))) : '';
	$api_type = (isset($_GET['data'])) ? cIn(htmlspecialchars(strip_tags(cleanBreaks($_GET['data'])))) : '';
	$api_data = api_user_authenticate($user,$key);
		
	if($api_data == ''){
		switch($api_type){
			case 'events_c':
				$api_data = api_get_events(1);
				break;
			case 'events_b':
				$api_data = api_get_events(2);
				break;
			case 'events_p':
				$api_data = api_get_events(3);
				break;
			case 'events_n':
				$api_data = api_get_events(4);
				break;
			case 'categories_h':
				$api_data = api_get_categories(1);
				break;
			case 'categories_a':
				$api_data = api_get_categories(2);
				break;
			case 'categories_e':
				$api_data = api_get_categories(3);
				break;
			case 'newsletters_c':
				$api_data = api_get_newsletters(1);
				break;
			case 'newsletters_p':
				$api_data = api_get_newsletters(2);
				break;
			default:
				if(!isset($_GET['data']))
					$api_data = api_error(0);
				else
					$api_data = api_error(1);
				break;
		}
	}
	
	echo $api_data;
?>
