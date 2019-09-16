<?php $t =& peTheme(); ?>
<?php $layout =& $t->layout; ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>

	<?php

		$footer_address = $t->options->get("footerAddress");
		$footer_email  = $t->options->get("footerEmail");
		$footer_phone  = $t->options->get("footerPhone");

		$grid_class = 'col-md-4';
		$i         = 0;

		if ( $footer_address ) $i++;
		if ( $footer_email ) $i++;
		if ( $footer_phone ) $i++;

		if ( 2 === $i ) $grid_class = 'col-md-6';
		if ( 1 === $i ) $grid_class = 'col-md-4 col-md-offset-4';

	?>

	<?php if ( $i ) : ?>

		<div id="footer-columns-region" class="footer-columns region-30 block-30 bg-color-grayDark2 text-color-light">
			<div class="container">
				<div class="row">

					<?php if ( $footer_address ) : ?>

						<div id="footer-first-column-region" class="footer-first-column col-xs-12 <?php echo $grid_class; ?> text-center">
							<div class="region">

								<div id="footer-address-block" class="footer-address block">
									<i class="icon ion-ios7-location-outline size-32 margin-bottom-20"></i>
									<p><?php echo $footer_address; ?></p>
								</div> <!-- /footer-address-block -->

							</div> <!-- /region -->
						</div> <!-- /footer-first-column-region -->

					<?php endif; ?>

					<?php if ( $footer_email ) : ?>

						<div id="footer-second-column-region" class="footer-second-column col-xs-12 <?php echo $grid_class; ?> text-center">
							<div class="region">

								<div id="footer-mail-block" class="footer-mail block">
									<i class="icon ion-ios7-email-outline size-32 margin-bottom-20"></i>
									<p><?php echo $footer_email; ?></p>
								</div> <!-- /footer-mail-block -->

							</div> <!-- /region -->
						</div>  <!-- /footer-second-column-region -->

					<?php endif; ?>

					<?php if ( $footer_phone ) : ?>

						<div id="footer-third-column-region" class="footer-third-column col-xs-12 <?php echo $grid_class; ?> text-center">
							<div class="region">

								<div id="footer-phone-block" class="footer-phone block">
									<i class="icon ion-ios7-telephone-outline size-32 margin-bottom-20"></i>
									<p><?php echo $footer_phone; ?></p>
								</div> <!-- /footer-phone-block -->

							</div> <!-- /region -->
						</div>  <!-- /footer-third-column-region -->

					<?php endif; ?>

				</div> <!-- /row -->
			</div> <!-- /container -->
		</div> <!-- /footer-columns-region -->

	<?php endif; ?>

	<footer class="region-10 block-10 bg-color-grayDark1 text-color-light">
		<div class="container">
			<div class="row">

			<div id="footer-left-region" class="footer-left region-bottom-sm-0 col-xs-12 col-md-6 text-center-sm">
				<div class="region">

					<div id="copyright-block" class="block">
						<p><?php echo $t->options->get("footerCopyright"); ?></p>
					</div> <!-- /copyright-block -->

				</div> <!-- /region -->
			</div> <!-- /footer-left-region -->

			<div id="footer-right-region" class="footer-right region-top-sm-0 col-xs-12 col-md-6 text-right text-center-sm">
				<div class="region">

					<?php $social_links = $t->options->get("footerSocialLinks"); ?>

					<?php if ( ! empty( $social_links ) ) : ?>

						<div id="social-networks-footer-block" class="social-networks-footer block">

							<?php foreach ( $social_links as $social_link ) : ?>

								<a href="<?php echo esc_attr( $social_link['url'] ); ?>" target="_blank"><i class="icon ion-social-<?php echo $social_link['name']; ?>"></i></a>

							<?php endforeach; ?>

						</div> <!-- /social-networks-footer -->

					<?php endif; ?>

				</div> <!-- /region -->
			</div> <!-- /footer-right-region -->

			</div> <!-- /row -->
		</div> <!-- /container -->
	</footer>

</div>

<div id="back-to-top">
	<i class="ion-ios7-arrow-up"></i>
</div>

<?php $t->footer->wp_footer(); ?>

</body>
</html>
