<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<style type="text/css" media="screen">

<?php
// Checks to see whether it needs a sidebar or not
if ( !is_single() || is_single() ) {
?>
	#page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbg-<?php bloginfo('text_direction'); ?>.jpg") repeat-y top; border: none; }
<?php } else { // No sidebar ?>
	#page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbgwide.jpg") repeat-y top; border: none; }
<?php } ?>

</style>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body>
<div id="page">


<div id="header" onclick="location.href='http://awesomeradicalgaming.com/';" style="cursor: pointer;">
	<div id="headerimg">
		<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	</div>
</div>
<hr />
<div id="top_menu">
	<ul>
		<li><a href="http://awesomeradicalgaming.com">Home</a></li>
		<li><a href="http://awesomeradicalgaming.com/category/review/">Reviews</a></li>
		<li><a href="http://awesomeradicalgaming.com/tag/E3+2010/">E3</a></li>
		<li><a href="http://awesomeradicalgaming.com/about/">About</a></li>
		<li><a href="mailto:tips@awesomeradicalgaming.com">Contact</a></li>
		<li><a href="http://awesomeradicalgaming.com/links-to-partners/">Partners</a></li>
	</ul>
	<form method="get" id="searchform" action="http://awesomeradicalgaming.com/" style="text-align: right; margin: 0px;">
		<label class="hidden" for="s">Search for:</label>
		<div><input type="text" value="" name="s" id="s" />
		<input type="submit" id="searchsubmit" value="Search" />
		</div>
	</form>
</div>