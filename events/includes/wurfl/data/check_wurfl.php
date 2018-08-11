<form action="check_wurfl.php" method="GET">
force ua:<input type="text" name="force_ua" size="100">
</form>
<?php
/*
 * $Id: check_wurfl.php,v 1.1 2005/04/16 16:01:37 atrasatti Exp $
 * $RCSfile: check_wurfl.php,v $ v2.1 beta2 (Apr, 16 2005)
 *
 * Author: Andrea Trasatti ( atrasatti AT users DOT sourceforge DOT net )
 *
 */

set_time_limit(600);

list($usec, $sec) = explode(" ", microtime());
$start = ((float)$usec + (float)$sec); 

require_once('./wurfl_config.php');
require_once(WURFL_CLASS_FILE);

list($usec, $sec) = explode(" ", microtime());
$load_class = ((float)$usec + (float)$sec); 

$wurflObj = new wurfl_class();

list($usec, $sec) = explode(" ", microtime());
$init_class = ((float)$usec + (float)$sec); 

if ( isset($_GET['force_ua']) && strlen($_GET['force_ua'])>0 ) {
	$wurflObj->GetDeviceCapabilitiesFromAgent($_GET['force_ua']);
} else {
	//Forcing a test agent
	$wurflObj->GetDeviceCapabilitiesFromAgent("MOT-c350");
}

list($usec, $sec) = explode(" ", microtime());
$end = ((float)$usec + (float)$sec); 

echo "Time to load wurfl_class.php:".($load_class-$start)."<br>\n";
echo "Time to initialize class:".($init_class-$load_class)."<br>\n";
echo "Time to find the user agent:".($end-$init_class)."<br>\n";
echo "Total:".($end-$start)."<br>\n";

echo "<pre>";
var_export($wurflObj->capabilities);
echo "</pre>";

?>
<form action="check_wurfl.php" method="GET">
force ua:<input type="text" name="force_ua" size="100">
</form>
