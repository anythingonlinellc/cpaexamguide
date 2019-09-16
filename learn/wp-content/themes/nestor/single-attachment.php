<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<section class="container">
	<?php if ( wp_attachment_is_image( $post->id ) ) : ?>

	<div class="post-media clearfix text-center">
		<?php $img = wp_get_attachment_image_src( $post->id, "full"); ?>
		<?php $content->img( 1140, 0, $img[0] ); ?>
	</div>

	<?php endif; ?>

</section>

<?php get_footer(); ?>