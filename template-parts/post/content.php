<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
 $trackingid = get_option('tracking_id');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if ( is_sticky() && is_home() ) :
			echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
		endif;
	?>
	<header class="entry-header">
		<?php
			if ( 'post' === get_post_type() ) :
				echo '<div class="entry-meta">';
					if ( is_single() ) :
						twentyseventeen_posted_on();
					else :
						echo twentyseventeen_time_link();
						twentyseventeen_edit_link();
					endif;
				echo '</div><!-- .entry-meta -->';
			endif;

			if ( is_single() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
		?>
	</header><!-- .entry-header -->

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

			<div class="entry-content">
				<div class="post-thumbnail" style="width: 33.33333333333333%;float: left;">
				<?php if(get_post_meta($post->ID, "image", $single = true) != ""){ ?>
				
				<img width="360" height="360" src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" />
				<?php } ?>
				</div>
				<div class="post-content" style="width: 66.66666666666666%;float: left;">
					<div class="labelright">
					<span class="green size18"><b>
					<?php if(get_post_meta($post->ID, "price", $single = true) != ""){ ?>
					<?php echo get_post_meta($post->ID, "price", $single = true); ?>
					<?php } ?>	
					</b></span><br>
					<span class="size11 grey">(Min. Order: 1 Piece)</span>
					<br><br>
					<div style="text-align:right;">
					<button onclick="window.open('https://www.amazon.com/gp/product/<?php echo get_post_meta($post->ID, "asin", $single = true); ?>/ref=as_li_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=<?php echo get_post_meta($post->ID, "asin", $single = true); ?>&linkCode=as2&tag=<?php echo $trackingid; ?>&linkId=c535a10d9f2e5680f211ecc31c5a8d4f','_blank')" class="redirect bookbtn mt1" title="View Detail">View Detail</button>
				</div>
				</div>
					<div class="labelleft2">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
				get_the_title()
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			) );
		?>
					</div>
				</div>	
					
				</div><!-- .entry-content -->
	<?php if ( is_single() ) : ?>
		<?php twentyseventeen_entry_footer(); ?>
	<?php endif; ?>

</article><!-- #post-## -->
