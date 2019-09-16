<?php $t =& peTheme(); ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-contact" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="contact-content">
		<div class="container">
			<div class="row">

				<div class="col-xs-12 col-md-8 col-md-offset-2">

					<form class="form-horizontal peThemeContactForm" method="post" role="form">
						<div class="form-group">
							<div class="col-md-6 margin-bottom-sm-20">
								<input type="text" class="form-control" id="contact-name" name="author" placeholder="<?php _e( 'Name' ,'Pixelentity Theme/Plugin'); ?>" />
							</div>
							<div class="col-md-6">
								<input type="email" class="form-control" id="contact-email" name="email" placeholder="<?php _e( 'Email' ,'Pixelentity Theme/Plugin'); ?>" />
							</div>
						</div>

						<div class="form-group">
							<div class="col-xs-12">
								<input type="text" class="form-control" id="contact-subject" name="subject" placeholder="<?php _e( 'Subject' ,'Pixelentity Theme/Plugin'); ?>" />
							</div>
						</div>

						<div class="form-group">
							<div class="col-xs-12">
								<textarea class="form-control" rows="8" id="contact-message" name="message" placeholder="<?php _e( 'Message' ,'Pixelentity Theme/Plugin'); ?>"></textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="col-xs-12">
								<input type="submit" class="btn btn-primary btn-sm" id="submit" name="submit" value="<?php _e( 'Send' ,'Pixelentity Theme/Plugin'); ?>" />
							</div>
						</div>

						<div id="contactFormSent" class="notification-wrap clearfix formSent">
							<div class="notification success clearfix"><p><?php echo $data->msgOK; ?></p></div>
						</div>
						<div id="contactFormError" class="notification-wrap clearfix formError">
							<div class="notification error clearfix"><p><?php echo $data->msgKO; ?></p></div>
						</div>

					</form>

				</div>

			</div>
		</div>
	</div>

	<?php if ( 'yes' === $data->show ) : ?>

		<div id="map-region" class="margin-top-50">
			<div id="map_canvas" class="google-map" data-latitude="<?php echo $data->latitude; ?>" data-longitude="<?php echo $data->longitude; ?>" data-pintitle="<?php echo $data->pin_title; ?>" data-pindescription="<?php echo $data->pin_description; ?>" data-zoom="<?php echo $data->zoom; ?>"></div>
		</div>

	<?php endif; ?>

</section>