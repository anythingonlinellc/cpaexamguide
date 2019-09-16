<?php $t =& peTheme(); ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-calltoaction" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?> data-stellar-background-ratio="0.7" data-stellar-vertical-offset="-350">

	<?php if ( empty( $data->font_size ) || 'medium' === $data->font_size ) : ?>
	
		<div class="call-to-action-1">
			<div class="container">
				<div class="row">

					<div class="col-xs-12 text-center text-color-light">

						<?php if ( ! empty( $data->text ) ) : ?>

							<h4 class="call-to-action-1-text"><?php echo $data->text; ?></h4>

						<?php endif; ?>

						<?php if ( ! empty( $data->button_text ) ) : ?>

							<?php $target = ! empty( $data->button_target ) && 'yes' === $data->button_target ? '_blank' : '_self'; ?>
							
							<a href="<?php echo esc_attr( $data->button_url ); ?>" class="call-to-action-1-button btn btn-default" target="<?php echo $target; ?>"><?php echo $data->button_text; ?></a>

						<?php endif; ?>

					</div>

				</div>
			</div>
		</div>

	<?php else : ?>

		<div class="text-widget-1">
			<div class="overlay">
				<div class="vertical-center text-center text-color-light">
					<div class="container">

						<?php if ( ! empty( $data->text ) ) : ?>

							<h2 class="<?php if ( empty( $data->button_text ) ) echo 'no-margin'; ?>"><?php echo $data->text; ?></h2>

						<?php endif; ?>

						<?php if ( ! empty( $data->button_text ) ) : ?>

							<?php $target = ! empty( $data->button_target ) && 'yes' === $data->button_target ? '_blank' : '_self'; ?>

							<a href="<?php echo esc_attr( $data->button_url ); ?>" class="call-to-action-1-button btn btn-default" target="<?php echo $target; ?>"><?php echo $data->button_text; ?></a>

						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>

</section>