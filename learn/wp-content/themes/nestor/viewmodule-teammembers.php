<?php $t =& peTheme(); ?>
<?php list($data,$items,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="pricing padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-teammembers" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="team">
		<div class="container">

			<?php if ( ! empty( $data->title ) ) : ?>

				<h2 class="block-title"><?php echo $data->title; ?></h2>

			<?php endif; ?>

			<div class="row">

				<?php $items_count = count( $items ); ?>
				<?php $column = ( 12 / $items_count < 3 ) ? 3 : 12 / $items_count; ?>
				<?php if ( 12 === $column ) $column = '6 col-md-offset-3'; ?>

				<?php foreach ( $items as $item ) : ?>

					<div class="col-xs-12 col-md-<?php echo $column; ?> team-item">

						<?php if ( ! empty( $item->image ) ) : ?>

							<div class="team-item-image">
								<img src="<?php echo $item->image; ?>" alt="<?php echo esc_attr( $item->name ); ?>" class="img-responsive" />
								<div class="team-item-overlay">

									<?php if ( ! empty( $item->links ) ) : ?>

										<p><?php foreach ( $item->links as $link ) : ?>

											<a href="<?php echo $link['url']; ?>" target="_blank"><?php echo $link['title']; ?></a><br/>

										<?php endforeach; ?></p>

									<?php endif; ?>

								</div>
							</div>

						<?php endif; ?>

						<div class="team-item-name">

							<?php if ( ! empty( $item->name ) ) : ?>

								<h6><?php echo $item->name; ?></h6>

							<?php endif; ?>

							<?php if ( ! empty( $item->role ) ) : ?>

								<p class="text-color-theme"><?php echo $item->role; ?></p>

							<?php endif; ?>

						</div>

						<?php if ( ! empty( $item->content ) ) : ?>

							<p class="team-item-description"><?php echo $item->content; ?></p>
					
						<?php endif; ?>

					</div>

				<?php endforeach; ?>

			</div>
		</div>
	</div>

</section>