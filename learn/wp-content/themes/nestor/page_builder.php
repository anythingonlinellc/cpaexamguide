<?php
/*
 * Template Name: Content Builder
 * Description: A Page Template which uses the drag and drop builder to compose content
 *
 * @package WordPress
 * @subpackage Theme
 * @since 1.0
 */
?>
<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<?php if ( ! empty( $meta->splash->type ) ) : ?>

	<?php if ( 'image' === $meta->splash->type ) : ?>

		<?php get_template_part( 'splash', 'image' ); ?>

	<?php elseif ( 'gallery' === $meta->splash->type ) : ?>

		<?php get_template_part( 'splash', 'gallery' ); ?>

	<?php endif; ?>

<?php endif; ?>

<?php if ( empty( $meta->splash->type ) || 'none' === $meta->splash->type ) : ?>

	<div id="top-content-region" class="top-content region-0 padding-top-15 padding-bottom-15 block-15 bg-color-grayLight1">
		<div class="container">
			<div class="row">

				<div id="top-content-left-region" class="top-content-left col-xs-12 col-md-6 text-center-sm">
					<div class="region">

						<div id="page-title-block" class="page-title block">
							<h1><?php $content->title(); ?></h1>
						</div> <!-- /page-title-block -->

					</div> <!-- /region -->
				</div> <!-- /top-content-left-region -->

				<div id="top-content-right-region" class="top-content-right col-xs-12 col-md-6 text-right text-center-sm">
					<div class="region">

						<div id="page-breadcrumbs-block" class="page-breadcrumbs block">
							<div class="breadcrumbs">
							
								<?php $content->breadcrumbs( '&rsaquo;' ); ?>

								<?php if ( ! is_home() ) : ?>

									<span class="delimiter">&rsaquo;</span>
									<?php $content->title(); ?>

								<?php endif; ?>

							</div>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>

<?php endif; ?>

<div id="<?php $content->slug(); ?>" class="content"><?php $content->builder(); ?></div>

<?php get_footer(); ?>