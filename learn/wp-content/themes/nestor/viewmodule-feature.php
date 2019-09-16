<?php $t =& peTheme(); ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-feature" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="services-2">
		<div class="container">

			<div class="row services-2-item">
				<div class="col-xs-12 col-md-<?php echo ( empty( $data->image ) ) ? '12' : '5 col-md-offset-1'; ?> services-2-content">

					<?php if ( ! empty( $data->title ) ) : ?>

						<h4><?php echo $data->title; ?></h4>

					<?php endif; ?>

					<?php if ( ! empty( $data->content ) ) : ?>

						<div class="section-type-feature-content">

							<?php echo $data->content; ?>

						</div>
					
					<?php endif; ?>

				</div>

				<?php if ( ! empty( $data->image ) ) : ?>

					<div class="col-xs-12 col-md-5 services-2-image">
						<img src="<?php echo $data->image; ?>" class="img-responsive" alt="Section image">
					</div>

				<?php endif; ?>

			</div>

		</div>
	</div>

</section>