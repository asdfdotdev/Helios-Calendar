<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}?>

	<div id="controls">
		<?php mini_cal_month();?>
		
		<div id="language"><?php select_language(0);?></div>
		
		<?php mini_search('Search Events by Keyword',0);?>
		
		<div id="billboard">
			<div class="listHeader">Billboard Events</div>
			<?php event_list(0);?>

		</div>

		<div id="popular">
			<div class="listHeader">Most Popular Events</div>
			<?php event_list(1);?>

		</div>

		<div id="newest">
			<div class="listHeader">Newest Events</div>
			<?php event_list(2);?>

		</div>
		
		Theme: <?php select_theme();?>
	</div>