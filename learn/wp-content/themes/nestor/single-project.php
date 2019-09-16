<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<?php while ($content->looping() ) : ?>

	<div id="top-content-region" class="top-content region-0 padding-top-15 padding-bottom-15 block-15 bg-color-grayLight1">
		<div class="container">
			<div class="row">

				<div id="top-content-left-region" class="top-content-left col-xs-12 col-md-6 text-center-sm">
					<div class="region">

						<div id="page-title-block" class="page-title block">
							<h1><?php $content->title(); ?></h1>
						</div> <!-- /page-title-block -->

					</div> <!-- /region -->
				</div> <!-- /top-content-left-region -->

			</div> <!-- /row -->
		</div> <!-- /container -->
	</div>

	<div id="content-region" class="content region">

		<div id="portfolio-single-block" class="portfolio-single block">
			<div class="container">
				<div class="row">

					<div class="col-xs-12">

						<?php $format = get_post_format(); ?>

						<?php switch( $format ) :

							case( false ) : ?>

								<div class="project-image portfolio-single-image img-responsive">

										<?php $content->img( 1140,0 ); ?>

								</div>

							<?php break; ?>

							<?php case( 'gallery' ) : ?>

								<?php $loop = $t->gallery->getSliderLoop($meta->gallery->id); ?>

								<?php if ( $loop ): ?>

									<div class="flex-arrow-slider">
										<ul class="slides">

											<?php while ($item =& $loop->next()): ?>

												<li class="portfolio-single-image img-responsive"><?php echo $t->image->resizedImg( $item->img, 1170, 658 ); ?></li>

											<?php endwhile; ?>

										</ul>
									</div>

								<?php endif; ?>

							<?php break; ?>

							<?php case( 'video' ) : ?>

								<div class="project-video">

										<?php $videoID = $meta->video->id; ?>
										<?php if ($video = $t->video->getInfo($videoID)): ?>

										<div class="vendor">

											<?php switch($video->type): case "youtube": ?>

												<iframe width="1170" height="658" src="http://www.youtube.com/embed/<?php echo $video->id; ?>?autohide=1&modestbranding=1&showinfo=0" class="fullwidth-video" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
											
											<?php break; case "vimeo": ?>

												<iframe src="http://player.vimeo.com/video/<?php echo $video->id; ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" class="fullwidth-video" width="1170" height="658" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
											
											<?php endswitch; ?>

										</div>

										<?php endif; ?>
								</div>

							<?php break; ?>

						<?php endswitch; ?>

					</div> <!-- /col-xs-12 -->

					<div class="col-xs-12 margin-top-40 text-center">
						<?php $content->content(); ?>
					</div> <!-- /col-xs-12 -->

				</div> <!-- /row -->
			</div> <!-- /container -->
		</div> <!-- /portfolio-single-block -->

	</div>

	<?php if ( ! empty( $meta->cta->text ) || ! empty( $meta->cta->button_text ) ) : ?>

		<div id="content-1-region" class="content-1 region bg-color-theme text-color-light">

			<div id="call-to-action-1-block" class="call-to-action-1 block">
				<div class="container">
					<div class="row">

						<div class="col-xs-12 text-center">

							<?php if ( ! empty( $meta->cta->text ) ) : ?>

								<h4 class="call-to-action-1-text"><?php echo $meta->cta->text; ?></h4>

							<?php endif; ?>

							<?php if ( ! empty( $meta->cta->button_text ) ) : ?>

								<?php $target = ! empty( $meta->cta->button_target ) && 'yes' === $meta->cta->button_target ? '_blank' : '_self'; ?>

								<a href="<?php echo esc_attr( $meta->cta->button_url ); ?>" class="call-to-action-1-button btn btn-default" target="<?php echo $target; ?>"><?php echo $meta->cta->button_text; ?></a>

							<?php endif; ?>

						</div>

					</div> <!-- /row -->
				</div> <!-- /container -->
			</div> <!-- /call-to-action-1-block -->

		</div>

	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>