<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	
	get_header();?>

</head>
<body itemscope itemtype="http://schema.org/WebPage">
	<a name="top"></a>	
	<header>
		<span>
			<?php echo cal_name();?>
			
			<div id="tag">Publishing awesome events.</div>
		</span>
		<aside>
			<?php mini_search('Search Events by Keyword',0);?>
		
		</aside>
	</header>
	<nav>
		<?php build_breadcrumb($crumbs);?>
	</nav>
	<section id="events">
		<article>
			<section>
				<fieldset id="signin">
					<legend>Select Your Account to Sign In</legend>
				<?php 
					twitter_signin_button();
					facebook_signin_button();
					google_signin_button();?>

				</fieldset>
			
				<p>
					Event submissions will be associated with the account you sign in with.<br />Sign in with that account again to update your events.
				</p>
			</section>
		</article>
		
		<aside>
	<?php 
		mini_cal_month();	
		get_side();?>
				
		</aside>
	</section>
	
	<?php get_footer(); ?>