<?php $t =& peTheme();?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>
<?php $background = empty( $meta->splash->background ) ? '' : 'style="background-image: url(\'' . esc_attr( $meta->splash->background ) . '\');"'; ?>

<?php if ( empty( $meta->splash->image_type ) || 'multiple' === $meta->splash->image_type ) : ?>

	<div id="highlighted-region" class="highlighted text-color-light">
		<div id="highlighted-slider-1-block" class="highlighted-slider-1 position-relative bg-image-cover" <?php echo $background; ?> data-stellar-background-ratio="0.7">
			<div class="overlay">

				<?php if ( ! empty( $meta->splash->headlines ) ) : ?>

					<div class="flex-bullet-slider vertical-center text-center">
						<div class="container">
							<ul class="slides">

								<?php foreach ( $meta->splash->headlines as $headline ) : ?>

									<li>

										<?php if ( ! empty( $headline['title'] ) ) : ?>

											<h1 class="slider-title"><?php echo $headline['title']; ?></h1>

										<?php endif; ?>

										<?php if ( ! empty( $headline['description'] ) ) : ?>

											<p class="slider-description"><?php echo $headline['description']; ?></p>

										<?php endif; ?>

										<?php if ( ! empty( $headline['button_text'] ) ) : ?>

											<a href="<?php echo esc_attr( $headline['button_url'] ); ?>" class="btn btn-default"><?php echo $headline['button_text']; ?></a>

										<?php endif; ?>

									</li>

								<?php endforeach; ?>

							</ul>
						</div> <!-- /container -->
					</div> <!-- /flex-bullet-slider -->

				<?php endif; ?>

			</div> <!-- /overlay -->
		</div> <!-- /highlighted-slider-1-block -->
	</div>

<?php else : ?>

	<div id="highlighted-region" class="highlighted text-color-light">
		<div id="highlighted-image-1-block" class="highlighted-image-1 position-relative bg-image-cover" <?php echo $background; ?>>
			<div class="overlay">

				<?php if ( ! empty( $meta->splash->title ) ) : ?>

					<div class="vertical-center text-center">
						<div class="container">
							<h1 class="no-margin"><?php echo $meta->splash->title; ?></h1>
						</div> <!-- /container -->
					</div> <!-- /vertical-center -->

				<?php endif; ?>

			</div> <!-- /overlay -->
		</div> <!-- /highlighted-slider-1-block -->
	</div>

<?php endif; ?>