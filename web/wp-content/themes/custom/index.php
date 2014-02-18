<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="narrowcolumn">

<?php $adsense_count = 1; ?>  

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small><?php the_time('F jS, Y') ?>  by <?php the_author() ?> </small>

				<div class="entry">  

					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>

				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>

<?php if ($adsense_count == 2) : ?> 
<p style="text-align: center;">
<script type="text/javascript"><!--
google_ad_client = "pub-8427257214131982";
/* Awesome Radical Between Posts 2 */
google_ad_slot = "5956401904";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</p>
<?php endif; $adsense_count++; ?>

			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
<p align="center">How about making some money while playing online? Visit the fun <a href="http://ca.888.com">888.com</a> and play all of your favorite casino table and skill games online. Learn strategies and play some fun games such as blackjack, <a href=http://www.pacificpoker.com>Texas Holdem</a>, bingo and many more.</p>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
