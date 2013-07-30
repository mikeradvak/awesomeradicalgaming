<?php
/*
Template Name: Blank Page
*/
?>
<?php
// If you are using The Loop inside your own design (and your own design is not a template), set WP_USE_THEMES to false.
define('WP_USE_THEMES', false);
// Register the $more global.
global $more;
// Start the loop.
if ( have_posts() ) : while ( have_posts() ) : the_post();
		$more = 1;
		?>
<div class="entry">
	<?php the_content(); ?>
</div>
		<?php
	endwhile;
endif;
?>
