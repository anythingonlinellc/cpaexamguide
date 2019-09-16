<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php $t->layout->pageTitle = __("Not Found",'Pixelentity Theme/Plugin'); ?>
<?php get_header(); ?>

<div id="top-content-region" class="top-content region-0 padding-top-15 padding-bottom-15 block-15 bg-color-grayLight1">
	<div class="container">
		<div class="row">

			<div id="top-content-left-region" class="top-content-left col-xs-12 col-md-6 text-center-sm">
				<div class="region">

					<div id="page-title-block" class="page-title block">
						<h1>404</h1>
					</div> <!-- /page-title-block -->

				</div> <!-- /region -->
			</div> <!-- /top-content-left-region -->

		</div> <!-- /row -->
	</div> <!-- /container -->
</div> <!-- /top-content-region -->

<div id="content-region" class="content region">

	<div id="404-content-block" class="404-content block">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-offset-2 col-md-8 text-center">

					<i class="icon ion-ios7-glasses-outline size-128 text-color-theme"></i>
					<h2><b><?php _e( 'Page not found!' ,'Pixelentity Theme/Plugin'); ?></b></h2>
					<p><?php _e( 'We are sorry. We couldn\'t find the page you were looking for. Please review your url address.' ,'Pixelentity Theme/Plugin'); ?></p>

				</div> <!-- /col-xs-12 -->
			</div> <!-- /row -->
		</div> <!-- /container -->
	</div> <!-- /404-content-block -->

</div> <!-- /content-region -->

<?php get_footer(); ?>