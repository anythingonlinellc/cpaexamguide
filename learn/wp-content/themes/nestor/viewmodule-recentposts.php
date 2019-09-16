<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-recentposts" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="latest-news-1">
		<div class="container">

			<?php if ( ! empty( $data->title ) ) : ?>
			
				<h2 class="block-title"><?php echo $data->title; ?></h2>

			<?php endif; ?>

			<?php if ( ! empty( $data->content ) ) : ?>

				<div class="latest-news-1-description">

					<div class="col-xs-12 col-md-12">

						<?php echo $data->content; ?>

					</div>

				</div>

			<?php endif; ?>

			<div class="row">

				<?php while ( $content->looping() ) : ?>

					<div class="col-xs-12 col-sm-4 latest-news-1-item">
						<a href="<?php echo get_permalink(); ?>">
							<div class="latest-news-1-image">
								<div class="img-responsive">
									<?php $content->img( 600, 450 ); ?>
								</div>
								<div class="latest-news-1-overlay">
									<p><?php _e( 'Click to read more' ,'Pixelentity Theme/Plugin'); ?></p>
								</div>
							</div>
						</a>
						<h6 class="latest-news-1-title"><a href="<?php echo get_permalink(); ?>"><?php $content->title(); ?></a></h6>
						<p><?php
							$excerpt = get_the_content();
							$excerpt = strip_tags( $excerpt );
							echo substr( $excerpt, 0, 100 ) . '...';
						?></p>
					</div>

				<?php endwhile; ?>

			</div>

		</div>
	</div>

</section>