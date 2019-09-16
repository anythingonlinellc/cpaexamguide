<?php $t =& peTheme(); ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-about" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="about-content">
		<div class="container">
			<div class="row">

				<?php if ( ! empty( $data->galleries ) ) : ?>

					<div class="col-xs-12 col-md-6">

						<?php if ( ! empty( $data->title ) ) : ?>

							<h5><?php echo $data->title; ?></h5>

						<?php endif; ?>

						<?php if ( ! empty( $data->content ) ) : ?>

							<div class="section-type-about-content">

								<?php echo $data->content; ?>

							</div>

						<?php endif; ?>

					</div>

					<?php $loop = $t->gallery->getSliderLoop( $data->galleries ); ?>

					<?php if ( $loop ): ?>

						<div class="col-xs-12 col-md-6 margin-top-sm-30">
							<div class="flex-arrow-slider">
								<ul class="slides">

									<?php while ($item =& $loop->next()): ?>

										<li class="img-responsive"><?php echo $t->image->resizedImg( $item->img, 555, 312 ); ?></li>

									<?php endwhile; ?>

								</ul>
							</div>
						</div>

					<?php endif; ?>					

				<?php else : ?>

					<div class="col-xs-12 col-md-8 col-md-offset-2 text-center">

						<?php if ( ! empty( $data->title ) ) : ?>

							<h4 class="text-color-theme"><?php echo $data->title; ?></h4>

						<?php endif; ?>

						<?php if ( ! empty( $data->content ) ) : ?>

							<div class="section-type-about-content">

								<?php echo $data->content; ?>

							</div>

						<?php endif; ?>

					</div>

				<?php endif; ?>

			</div>
		</div>
	</div>

</section>