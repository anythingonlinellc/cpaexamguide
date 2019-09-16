<?php $t =& peTheme();?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>

<?php $header_text = $t->options->get("headerText"); ?>
<?php $social_links = $t->options->get("headerSocialLinks"); ?>

<div id="top-header-region" class="top-header region-5 block-5 bg-color-grayLight1">
	<div class="container">
		<div class="row">

		<div id="top-header-left-region" class="top-header-left region-bottom-sm-0 col-xs-12 col-md-6 text-center-sm">
			<div class="region">

				<div id="text-widget-2-block" class="text-widget-2 block">

					<?php if ( ! empty( $header_text ) ) : ?>

					<ul class="list-inline">

						<?php foreach ( $header_text as $text ) : ?>

							<li><?php echo $text['text']; ?></li>

						<?php endforeach; ?>

					</ul>

				<?php endif; ?>

				</div> <!-- /text-widget-2-block -->

			</div> <!-- /region -->
		</div> <!-- /top-header-left-region -->

		<div id="top-header-right-region" class="top-header-right region-top-sm-0 col-xs-12 col-md-6 text-right text-center-sm">
			<div class="region">

				<?php if ( ! empty( $social_links ) ) : ?>

				<div id="social-networks-top-header-block" class="social-networks-top-header block">

					<?php foreach ( $social_links as $social_link ) : ?>

						<a href="<?php echo esc_attr( $social_link['url'] ); ?>" target="_blank"><i class="icon ion-social-<?php echo $social_link['name']; ?>"></i></a>
					
					<?php endforeach; ?>

				</div> <!-- /social-networks-top-header -->

				<?php endif; ?>

			</div> <!-- /region -->
		</div> <!-- /top-header-right-region -->

		</div> <!-- /row -->
	</div> <!-- /container -->
</div>