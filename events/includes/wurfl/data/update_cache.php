<?php
/* ***** BEGIN LICENSE BLOCK *****
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is WURFL PHP Libraries.
 *
 * The Initial Developer of the Original Code is
 * Andrea Trasatti.
 * Portions created by the Initial Developer are Copyright (C) 2004-2005
 * the Initial Developer. All Rights Reserved.
 *
 * ***** END LICENSE BLOCK ***** */

/*
 * $Id: update_cache.php,v 1.1 2005/04/16 16:04:21 atrasatti Exp $
 * $RCSfile: update_cache.php,v $ v2.1 beta2 (Apr, 16 2005)
 *
 * Author: Andrea Trasatti ( atrasatti AT users DOT sourceforge DOT net )
 * Multicache implementation: Herouth Maoz ( herouth AT spamcop DOT net )
 *
 */

/*
 *
 * This script should be called manually (CLI is suggested) to update the
 * multicache files when a new XML is availabled.
 * This script should be used when you have configured WURFL_CACHE_AUTOUPDATE
 * to false.
 *
 * KNOWN BUG: cache.php will be updated automatically, a race condition might
 * happen while generating the new files in the temporary directory and before
 * it's moved to the default path. A temporary cache file should be used along
 * contributions are welcome.
 *
 * More info can be found here in the PHP section:
 * http://wurfl.sourceforge.net/
 *
 * Questions or comments can be sent to
 * "Andrea Trasatti" <atrasatti AT users DOT sourceforge DOT net>
 *
 * Please, support this software, send any suggestion and improvement to me
 * or the mailing list and we will try to keep it updated and make it better
 * every day.
 *
 * If you like it and use it, please let me know or contact the wmlprogramming
 * mailing list: wmlprogramming@yahoogroups.com
 *
 */

set_time_limit(600);

list($usec, $sec) = explode(" ", microtime());
$start = ((float)$usec + (float)$sec); 

require_once('./wurfl_config.php');
define('FORCED_UPDATE', true);
require_once(WURFL_PARSER_FILE);

list($usec, $sec) = explode(" ", microtime());
$load_parser = ((float)$usec + (float)$sec); 

wurfl_log('update_cache', "Forced cache update started");
if (WURFL_USE_CACHE === true) {
	parse();
	if ( WURFL_USE_MULTICACHE === true ) {
		wurfl_log('update_cache', "Updating multicache dir");
		touch(MULTICACHE_TOUCH);
		if ( is_dir(MULTICACHE_DIR) )
			rename(substr(MULTICACHE_DIR, 0, -1), substr(MULTICACHE_DIR, 0, -1).'.'.time());
		rename(substr(MULTICACHE_TMP_DIR, 0, -1), substr(MULTICACHE_DIR, 0, -1));
		unlink(MULTICACHE_TOUCH);
	}
	wurfl_log('update_cache', "Done updating cache");
} else {
	wurfl_log('update_cache', "Why update cache if WURFL_URE_CACHE is not set to true?");
}

list($usec, $sec) = explode(" ", microtime());
$parse = ((float)$usec + (float)$sec); 

echo "Parser load time:".($load_parser-$start)."<br>\n";
echo "Parsing time:".($parse-$load_parser)."<br>\n";
echo "Total:".($end-$start)."<br>\n";

?>
