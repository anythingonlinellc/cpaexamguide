<?php $t =& peTheme(); ?>
<?php $project =& $t->project; ?>
<?php $content =& $t->content; ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-portfolio" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="portfolio-content">
		<div class="container">

			<?php if ( ! empty( $data->title ) ) : ?>
			
				<h2 class="block-title"><?php echo $data->title; ?></h2>

			<?php endif; ?>

			<ul class="nav nav-pills portfolio-filters">
				<li class="text-bold text-color-grayDark1"><?php _e( 'Filter by:' ,'Pixelentity Theme/Plugin'); ?></li>
				<?php $project->filter( '', 'filter', 'active' ); ?>
			</ul>

			<div class="portfolio-grid row">

				<?php $lightbox = ( empty( $data->lightbox ) || 'no' === $data->lightbox ) ? false : true; ?>

				<?php while ( $content->looping() ) : ?>

					<?php $meta =& $content->meta(); ?>

					<div class="portfolio-item mix <?php $project->filterClasses(); ?> col-xs-12 col-sm-6 col-md-<?php echo $data->columns; ?>">
						<a href="<?php echo ( $lightbox ) ? $t->image->resizedImgUrl( wp_get_attachment_url( get_post_thumbnail_id() ), 9999, 9999 ) : get_permalink(); ?>" <?php echo ( $lightbox ) ? 'class="venobox" data-gall="gallery-' . $bid . '"' : ''; ?> title="<?php the_title_attribute(); ?>">
							<div class="portfolio-image">
								<div class="img-responsive">
									<?php $content->img( 600, 450 ); ?>
								</div>
								<div class="portfolio-overlay">
									<h6 class="portfolio-title"><?php $content->title(); ?></h6>
								</div>
							</div>
						</a>
					</div>

				<?php endwhile; ?>

			</div>

		</div>
	</div>

</section>