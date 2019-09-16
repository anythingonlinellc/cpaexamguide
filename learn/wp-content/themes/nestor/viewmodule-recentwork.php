<?php $t =& peTheme(); ?>
<?php $project =& $t->project; ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-recentwork" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="our-work-1">
		<div class="container">

			<?php if ( ! empty( $data->title ) ) : ?>
			
				<h2 class="block-title"><?php echo $data->title; ?></h2>

			<?php endif; ?>

			<div class="row our-work-1-description">

				<?php if ( ! empty( $data->content ) ) : ?>
				
					<div class="col-xs-12 <?php echo ( empty( $data->button_text ) ) ? 'col-md-12' : 'col-md-9'; ?>">

						<?php echo $data->content; ?>

					</div>

				<?php endif; ?>

				<?php if ( ! empty( $data->button_text ) ) : ?>

					<div class="col-xs-12 col-md-3 text-left-sm margin-top-sm-30 text-right">

						<?php $target = ! empty( $data->button_target ) && 'yes' === $data->button_target ? '_blank' : '_self'; ?>
						
						<a href="<?php echo esc_attr( $data->button_url ); ?>" class="btn btn-primary" target="<?php echo $target; ?>"><?php echo $data->button_text; ?></a>

					</div>

				<?php endif; ?>

			</div>

			<?php $content =& $t->content; ?>

			<div class="row">

				<?php while ( $content->looping() ) : ?>

					<?php $meta =& $content->meta(); ?>

					<div class="our-work-1-item col-xs-12 col-sm-4">
						<a href="<?php echo get_permalink(); ?>">
							<div class="our-work-1-image">
								<div class="img-responsive">
									<?php $content->img( 600, 450 ); ?>
								</div>
								<div class="our-work-1-overlay">
									<p><?php _e( 'Click to see more' ,'Pixelentity Theme/Plugin'); ?></p>
								</div>
							</div>
						</a>
						<div class="our-work-1-item-description">
							<h6><?php $content->title(); ?></h6>
							<p class="text-color-theme"><small><?php the_time( 'm.d.Y' ); ?></small></p>
						</div>
					</div>

				<?php endwhile; ?>

			</div>
		</div>
	</div>

</section>