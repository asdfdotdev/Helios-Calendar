<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();

	$new_lang = (isset($_GET['l'])) ? strtolower(cIn(strip_tags($_GET['l']))) : '';
	$target = CalRoot.'/';

	if($new_lang != ''){
		$dir = dir(realpath(HCPATH.HCINC.'/lang/'));
		if(is_dir($dir->path.'/'.$new_lang)){
			$_SESSION['LangSet'] = $new_lang;
			
			if(isset($_SERVER['HTTP_REFERER']) && preg_match('(^'.CalRoot.')',$_SERVER['HTTP_REFERER']))
				$target = cIn(strip_tags($_SERVER['HTTP_REFERER']));
		}
	}
	header('Location: ' . $target);
?>