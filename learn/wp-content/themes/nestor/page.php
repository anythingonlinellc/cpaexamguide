<?php $t =& peTheme(); ?>
<?php $content = $t->content; ?>
<?php $meta = $content->meta(); ?>
<?php get_header(); ?>
<?php while ($content->looping() ) : ?>

<?php if ( ! empty( $meta->splash->type ) ) : ?>

	<?php if ( 'image' === $meta->splash->type ) : ?>

		<?php get_template_part( 'splash', 'image' ); ?>

	<?php elseif ( 'gallery' === $meta->splash->type ) : ?>

		<?php get_template_part( 'splash', 'gallery' ); ?>

	<?php endif; ?>

<?php endif; ?>

<?php get_template_part("pagecontent"); ?>
<?php endwhile; ?>
<?php get_footer(); ?>