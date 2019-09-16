<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php $is_sidebar = ( empty( $meta->sidebar->sidebar ) || 'no' === $meta->sidebar->sidebar ) ? false : true; ?>

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

<div id="<?php $content->slug(); ?>" class="content region">
	<div class="container">
		<div class="row">
			<div class="<?php echo $is_sidebar ? 'col-xs-12 col-md-9' : 'col-sm-12'; ?>">
				
				<div class="page-body pe-wp-default">
					<?php $content->content(); ?>
				</div>

			</div>
			<?php if ( $is_sidebar ) : ?>

				<div class="col-xs-12 col-md-3">
					<?php get_sidebar(); ?>
				</div>

			<?php endif; ?>
		</div>
	</div>
</div>