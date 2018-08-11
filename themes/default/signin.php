<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body id="top" itemscope itemtype="http://schema.org/WebPage">
	<a name="top"></a>
<?php
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section>
		<fieldset id="signin">
			<legend>Select Your Account to Sign In</legend>
		<?php 
			twitter_signin_button();
			facebook_signin_button();
			google_signin_button();?>
			
			<p>
				Event submissions will be associated with the account you sign in with.<br />Sign in with that account again to update your events.
			</p>
		</fieldset>
	</section>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>