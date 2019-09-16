<?php $t =& peTheme(); ?>
<?php list($data,$items,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-testimonials" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?> data-stellar-background-ratio="0.7" data-stellar-vertical-offset="-350">

	<?php if ( empty( $data->layout ) || 'slider' === $data->layout ) : ?>

		<div class="testimonials-1">
			<div class="container">
				<div class="row">

					<div class="col-xs-12 col-md-10 col-md-offset-1">
						<div class="flex-bullet-slider vertical-center text-center">
							<i class="icon ion-ios7-chatbubble-outline testimonials-1-icon text-color-theme"></i>
							<ul class="slides">

								<?php foreach ( $items as $item ): ?>

									<li>

										<?php if ( ! empty( $item->content ) ) : ?>

											<h4 class="testimonials-1-text lato-font"><?php echo $item->content; ?></h4>

										<?php endif; ?>

										<?php if ( ! empty( $item->author ) ) : ?>

											<p class="testimonials-1-author text-color-theme"><?php echo $item->author; ?><?php if ( ! empty( $item->source ) ) : ?>, <i><?php echo $item->source; ?></i><?php endif; ?></p>

										<?php endif; ?>

									</li>
									
								<?php endforeach; ?>

							</ul>
						</div>
					</div>

				</div>
			</div>
		</div>

	<?php else : ?>

		<div class="testimonials-2 text-center">
			<div class="container">
				<div class="row">

					<?php $items_count = count( $items ); ?>
					<?php $column = ( 12 / $items_count < 3 ) ? 3 : 12 / $items_count; ?>
					<?php if ( 12 === $column ) $column = '6 col-md-offset-3'; ?>

					<?php foreach ( $items as $item ): ?>

						<div class="col-xs-12 col-md-<?php echo $column; ?> testimonials-2-item">

							<i class="icon ion-ios7-chatbubble-outline testimonials-2-icon text-color-theme size-48"></i>

							<?php if ( ! empty( $item->content ) ) : ?>
							
								<p class="testimonials-2-text"><?php echo $item->content; ?></p>
							
							<?php endif; ?>

							<?php if ( ! empty( $item->author ) ) : ?>

								<p class="testimonials-2-author bitter-font"><?php echo $item->author; ?><?php if ( ! empty( $item->source ) ) : ?>, <i><?php echo $item->source; ?></i><?php endif; ?></p>

							<?php endif; ?>

						</div>

					<?php endforeach; ?>

				</div>
			</div>
		</div>

	<?php endif; ?>

</section>