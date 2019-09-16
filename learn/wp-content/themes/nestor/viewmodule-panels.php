<?php $t =& peTheme(); ?>
<?php list($data,$items,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-panels" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="panels-4">
		<div class="container">

			<?php $items_count = count( $items ); ?>
			<?php $column = ( 12 / $items_count < 4 ) ? 4 : 12 / $items_count; ?>
			<?php $i = 0; ?>
			<?php $j = 0; ?>

			<?php foreach ( $items as $item ) : ?>

				<?php $j+= $column; ?>

				<?php if ( 4 === $j ) : ?>

					<div class="row">

				<?php endif; ?>

				<?php if ( ! empty( $item->icon ) ) : ?>

					<div class="col-xs-12 col-md-<?php echo $column; ?> panels-item">
						<div class="panels-icon text-color-theme">

							<i class="icon <?php echo $item->icon; ?>"></i>

						</div>
						<div class="panels-text">

							<?php if ( ! empty( $item->title ) ) : ?>

								<h6><?php echo $item->title; ?></h6>

							<?php endif; ?>

							<?php if ( ! empty( $item->content ) ) : ?>

								<p><?php echo $item->content; ?></p>
						
							<?php endif; ?>

						</div>
					</div>

				<?php else : ?>

					<div class="col-xs-12 col-md-4 panels-item">

						<?php if ( ! empty( $item->title ) ) : ?>

							<h5 class="panels-item-title"><?php echo $item->title; ?></h5>

						<?php endif; ?>

						<?php if ( ! empty( $item->content ) ) : ?>

							<p><?php echo $item->content; ?></p>
						
						<?php endif; ?>

					</div>

				<?php endif; ?>

				<?php if ( 12 === $j || $items_count - 1 === $i ) : ?>

					</div>

					<?php $j = 0; ?>

				<?php endif; ?>

				<?php $i++; ?>

			<?php endforeach; ?>

			<?php if ( ! empty( $data->image ) ) : ?>

				<div class="row panels-image">
					<div class="col-xs-12 text-center">
						<img src="<?php echo $data->image; ?>" class="img-responsive" alt="Section image" />
					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>

</section>