<?php $t =& peTheme(); ?>
<?php list($data,$items,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="pricing padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-pricing" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="company-prices">
		<div class="container">

			<?php if ( ! empty( $data->title ) || ! empty( $data->content ) ) : ?>

				<div class="row margin-bottom-50">
					<div class="col-xs-12 col-md-8 col-md-offset-2 text-center">

						<?php if ( ! empty( $data->title ) ) : ?>

							<h2><?php echo $data->title; ?></h2>

						<?php endif; ?>

						<?php if ( ! empty( $data->content ) ) : ?>

							<div class="section-type-pricing-content">

								<?php echo $data->content; ?>

							</div>

						<?php endif; ?>

					</div>
				</div>

			<?php endif; ?>

			<div class="pricing-tables-1 row">

				<?php $items_count = count( $items ); ?>
				<?php $column = ( 12 / $items_count < 3 ) ? 3 : 12 / $items_count; ?>
				<?php if ( 12 === $column ) $column = '6 col-sm-offset-3'; ?>

				<?php foreach ( $items as $item ): ?>

					<?php $featured = empty( $item->featured ) ? '' : $item->featured; ?>
					<?php $button_class = empty( $featured ) ? 'btn-default' : $featured; ?>
					<?php if ( ! empty( $featured ) ) $featured = ( 'btn-primary' === $featured ) ? 'featured' : 'premium'; ?>

					<div class="col-xs-12 col-sm-<?php echo $column; ?>">

						<div class="pricing-table <?php if ( ! empty( $featured ) ) echo 'pricing-' . $featured; ?>">

							<?php if ( ! empty( $item->plan ) ) : ?>

								<div class="pricing-table-title">
									<h4><?php echo $item->plan; ?></h4>
								</div>

							<?php endif; ?>

							<?php if ( ! empty( $item->price ) ) : ?>

								<div class="pricing-table-price">
									<h3><?php echo ( empty( $item->unit ) ) ? '' : $item->unit; ?><?php echo $item->price; ?></h3>
								</div>

							<?php endif; ?>

							<?php if ( ! empty( $item->features ) ) : ?>

								<div class="pricing-table-description">
									<ul class="list-unstyled">

										<?php foreach ( $item->features as $feature ) : ?>

											<li><?php echo $feature['text']; ?></li>

										<?php endforeach; ?>

									</ul>
								</div>

							<?php endif; ?>

							<?php if ( ! empty( $item->button_text ) ) : ?>

								<div class="pricing-table-button">
									<a href="<?php echo esc_attr( $item->button_link ); ?>" class="btn <?php echo $button_class; ?>"><?php echo $item->button_text; ?></a>
								</div>

							<?php endif; ?>

						</div>

					</div>

				<?php endforeach; ?>

			</div>
		</div>
	</div>

</section>