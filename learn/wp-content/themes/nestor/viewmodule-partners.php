<?php $t =& peTheme(); ?>
<?php list($data,$items,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="pricing padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-partners" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="partners">
		<div class="container">

			<div class="row text-center">

				<?php $items_count = count( $items ); ?>
				<?php $column = ( 12 / $items_count < 3 ) ? 3 : 12 / $items_count; ?>
				<?php if ( 12 === $column ) $column = '6 col-md-offset-3'; ?>

				<?php foreach ( $items as $item ) : ?>

					<div class="col-xs-12 col-sm-6 col-md-<?php echo $column; ?> margin-top-sm-50 partner">

						<?php if ( ! empty( $item->image ) ) : ?>

							<?php if ( ! empty( $item->link ) ) : ?>

								<a href="<?php echo $item->link; ?>" target="_blank">

							<?php endif; ?>

							<img src="<?php echo $item->image; ?>" alt="Partner" class="img-responsive" />

							<?php if ( ! empty( $item->link ) ) : ?>

								</a>

							<?php endif; ?>

						<?php endif; ?>

					</div>

				<?php endforeach; ?>

			</div>

		</div>
	</div>

</section>