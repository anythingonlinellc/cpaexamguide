<?php $t =& peTheme();?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>

<?php $template = is_page() ? $t->content->pageTemplate() : false; ?>

<div class="container">
	<div class="row">
		<div id="logo-region" class="logo col-md-3 text-center-sm">

			<?php $logo = $t->options->get("logo"); ?>

			<?php if ( ! empty( $logo ) ) : ?>

				<a href="<?php echo home_url( '/' ); ?>"><img src="<?php echo $logo; ?>" alt="logo" class="img-responsive" /></a>

			<?php else : ?>

				<h2 class="site-title"><a href="<?php echo home_url( '/' ); ?>"><?php echo $t->options->get("siteTitle");?></a></h2>

			<?php endif; ?>

		</div>

		<div id="menu-region" class="col-md-9">
			<nav class="navbar nestor-main-menu" role="navigation">
				<!-- Menu button for mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><?php _e( 'MENU' ,'Pixelentity Theme/Plugin'); ?></button>
				</div>

				<!-- Navigation links -->
				<div class="collapse navbar-collapse navbar-ex1-collapse">

					<?php $t->menu->show("main"); ?>

					
				</div> <!-- /navbar-collapse -->
			</nav>
		</div> <!-- /menu-region -->
	</div>
</div>