<?php $t =& peTheme();?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>

<?php if ( ! empty( $meta->splash->gallery ) ) : ?>

	<?php if ( $loop = $t->gallery->getSliderLoop( $meta->splash->gallery ) ) : ?>

		<div id="highlighted-region" class="highlighted text-color-light">
			<div id="highlighted-slider-2-block" class="highlighted-slider-2">

				<div class="flex-bullet-slider">
					<ul class="slides">

						<?php while ($slide =& $loop->next()): ?>

							<li>
								<figure>
									<img src="<?php echo $t->image->resizedImgUrl( $slide->img, 1440, 600 ); ?>" class="img-responsive img-full-width" alt="Slider image">
									<figcaption class="overlay overlay-30 text-center">
										<div class="highlighted-slider-2-content">

											<?php if ( ! empty( $slide->ititle ) ) : ?>

												<h1 class="slider-title"><?php echo $slide->ititle; ?></h1>

											<?php endif; ?>

											<?php if ( ! empty( $slide->subtitle ) ) : ?>

												<p class="slider-description"><?php echo $slide->subtitle; ?></p>

											<?php endif; ?>

											<?php if ( ! empty( $slide->button ) ) : ?>

												<?php $target = 'yes' === $slide->button_new_window ? 'target="_blank"' : 'target="_self"'; ?>

												<a href="<?php echo esc_attr( $slide->link ); ?>" class="btn btn-default slider-button" <?php echo $target; ?>><?php echo $slide->button; ?></a>

											<?php endif; ?>

										</div> <!-- /highlighted-slider-2-content -->
									</figcaption>
								</figure>
							</li>

						<?php endwhile; ?>

					</ul>
				</div> <!-- /flex-bullet-slider -->

			</div> <!-- /highlighted-slider-2-block -->
		</div> <!-- /highlighted-region -->

	<?php endif; ?>

<?php endif; ?>