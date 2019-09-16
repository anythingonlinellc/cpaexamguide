<?php $t =& peTheme(); ?>
<?php list($data,$items,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="services-1 padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-services" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="container">
		<div class="row">

			<div class="services-1-content col-xs-12 <?php echo ( empty( $data->image ) ) ? 'col-md-12' : 'col-md-7'; ?>">

				<?php if ( ! empty( $data->title ) ) : ?>

					<h2><?php echo $data->title; ?></h2>

				<?php endif; ?>

				<?php if ( ! empty( $data->content ) || ! empty( $data->button_text ) ) : ?>

					<?php if ( ! empty( $data->button_text ) ) : ?>

						<div class="row margin-bottom-40">
							<div class="col-xs-12 col-md-9">

					<?php endif; ?>

					<?php echo str_replace( '<p>', '<p class="margin-bottom-30">', $data->content ); ?>

					<?php if ( ! empty( $data->button_text ) ) : ?>

							</div>

							<div class="col-xs-12 col-md-3 text-left-sm margin-top-sm-30 text-right">

								<?php $target = 'yes' === $data->button_target ? '_blank' : '_self'; ?>

								<a href="<?php echo esc_attr( $data->button_url ); ?>" class="btn btn-primary" target="<?php echo $target; ?>"><?php echo $data->button_text; ?></a>

							</div>
						</div>

					<?php endif; ?>

				<?php endif; ?>

				<?php if ( ! empty( $items ) ) : ?>

					<?php $i = 0; ?>

					<div class="panels-2">

							<?php foreach ( $items as $item ): ?>

								<?php if ( ( ! empty( $data->image ) && 0 === $i % 2 ) || ( empty( $data->image ) && 0 === $i % 3 ) ) : ?>
								
									<div class="row">

								<?php endif; ?>

									<div class="panels-item <?php echo ( empty( $data->image ) ) ? 'col-sm-4' : 'col-sm-6'; ?>">

										<?php if ( ! empty( $item->icon ) ) : ?>

											<i class="icon <?php echo $item->icon; ?> text-color-theme"></i>

										<?php endif; ?>

										<?php if ( ! empty( $item->title ) ) : ?>

											<h6><?php echo $item->title; ?></h6>

										<?php endif; ?>

										<?php if ( ! empty( $item->content ) ) : ?>

											<div class="panels-service-content">
												<?php echo $item->content; ?>
											</div>

										<?php endif; ?>

									</div>

								<?php if ( ( ! empty( $data->image ) && 1 === $i % 2 ) || ( empty( $data->image ) && 2 === $i % 3 ) ) : ?>
								
									</div>

								<?php endif; ?>

								<?php $i++; ?>

							<?php endforeach; ?>

					</div>

				<?php endif; ?>

			</div>

			<?php if ( ! empty( $data->image ) ) : ?>

				<div class="services-1-image col-xs-12 col-md-5 text-center">
					<img src="<?php echo $data->image; ?>" alt="image" class="img-responsive" />
				</div>

			<?php endif; ?>

		</div>
	</div>

</section>