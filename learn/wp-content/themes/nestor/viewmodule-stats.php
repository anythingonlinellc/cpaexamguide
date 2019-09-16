<?php $t =& peTheme(); ?>
<?php list($data,$items,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-stats" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<?php if ( empty( $data->layout ) || 'columns' === $data->layout ) : ?>
	
		<div class="<?php echo ( empty( $data->font_type ) || 'default' === $data->font_type ) ? 'company-description' : 'skills-1'; ?>">
			<div class="container">

				<?php if ( ! empty( $data->title ) || ! empty( $data->content ) ) : ?>

					<div class="row">
						<div class="col-xs-12 col-md-8 col-md-offset-2 text-center">

							<?php if ( ! empty( $data->title ) ) : ?>

								<?php if ( empty( $data->font_type ) || 'default' === $data->font_type ) : ?>

									<h2><?php echo $data->title; ?></h2>

								<?php else : ?>

									<h3 class="text-color-theme"><?php echo $data->title; ?></h3>

								<?php endif; ?>

							<?php endif; ?>

							<?php if ( ! empty( $data->content ) ) : ?>
								
								<div class="section-type-stats-content">

									<?php echo $data->content; ?>

								</div>
							
							<?php endif; ?>

						</div>
					</div>

				<?php endif; ?>

				<div class="row <?php echo ( empty( $data->font_type ) || 'default' === $data->font_type ) ? 'company-stats-1' : 'skills-stats-1'; ?> text-center">

					<?php $items_count = count( $items ); ?>
					<?php $column = ( 12 / $items_count < 2 ) ? 2 : 12 / $items_count; ?>

					<?php foreach ( $items as $item ) : ?>

						<div class="col-xs-12 col-sm-6 col-md-<?php echo $column; ?> company-stat">

							<?php if ( ! empty( $item->number ) ) : ?>

								<?php if ( empty( $data->font_type ) || 'default' === $data->font_type ) : ?>

									<h2 class="company-stat-title" style="color: <?php echo esc_attr( $item->color ); ?>"><?php echo $item->number; ?></h2>

								<?php else : ?>

									<h4 class="skills-1-item-title" style="color: <?php echo esc_attr( $item->color ); ?>"><?php echo $item->number; ?></h4>

								<?php endif; ?>
								

							<?php endif; ?>

							<?php if ( ! empty( $item->detail ) ) : ?>

								<?php if ( empty( $data->font_type ) || 'default' === $data->font_type ) : ?>

									<p class="bitter-font"><?php echo $item->detail; ?></p>

								<?php else : ?>

									<p class="text-color-theme"><?php echo $item->detail; ?></p>

								<?php endif; ?>

							<?php endif; ?>

						</div>

					<?php endforeach; ?>

				</div>

				<?php if ( ! empty( $data->image ) ) : ?>

					<div class="row">
						<div class="col-xs-12 text-center">
							<img src="<?php echo $data->image; ?>" class="img-responsive" alt="Section image" />
						</div>
					</div>

				<?php endif; ?>

			</div>
		</div>

	<?php else : ?>

		<div class="company-stats-2 text-center">
			<div class="container">

				<?php if ( ! empty( $data->title ) || ! empty( $data->content ) ) : ?>

					<div class="row">
						<div class="col-xs-12 col-md-10 col-md-offset-1 text-center">

							<?php if ( ! empty( $data->title ) ) : ?>

								<h3 class="text-color-theme"><?php echo $data->title; ?></h3>

							<?php endif; ?>

							<?php if ( ! empty( $data->content ) ) : ?>
								
								<div class="section-type-stats-content">

									<?php echo $data->content; ?>

								</div>
							
							<?php endif; ?>

						</div>
					</div>

				<?php endif; ?>

				<div class="row">

					<?php $items_count = count( $items ); ?>
					<?php $column = ( 12 / $items_count < 4 ) ? 4 : 12 / $items_count; ?>
					<?php $i = 0; ?>
					<?php $j = 0; ?>

					<?php foreach ( $items as $item ) : ?>

						<?php if ( 0 === $i ) : ?>

							<div class="col-xs-12 col-md-4 company-stat-main">
								<i class="icon ion-arrow-graph-up-right size-48 text-color-theme"></i>

								<?php if ( ! empty( $item->number ) ) : ?>

									<h2 class="company-stat-main-title" style="color: <?php echo esc_attr( $item->color ); ?>"><?php echo $item->number; ?></h2>

								<?php endif; ?>

								<?php if ( ! empty( $item->detail ) ) : ?>

									<h6 class="company-stat-main-description"><?php echo $item->detail; ?></h6>

								<?php endif; ?>

							</div>

						<?php else : ?>

							<?php $j+= $column; ?>

							<?php if ( 1 === $i ) : ?>

								<div class="col-xs-12 col-md-8 company-stat-extra">

							<?php endif; ?>

								<?php if ( $column === $j ) : ?>

									<div class="row">

								<?php endif; ?>

									<div class="col-xs-<?php echo $column; ?>">

										<?php if ( ! empty( $item->number ) ) : ?>

											<h3 class="company-stat-extra-title" style="color: <?php echo esc_attr( $item->color ); ?>"><?php echo $item->number; ?></h3>

										<?php endif; ?>

										<?php if ( ! empty( $item->detail ) ) : ?>

											<p><?php echo $item->detail; ?></p>

										<?php endif; ?>

									</div>

								<?php if ( 12 === $j || $items_count - 1 === $i ) : ?>

									</div>

									<?php $j = 0; ?>

								<?php endif; ?>

							<?php if ( $items_count - 1 === $i ) : ?>

								</div>

							<?php endif; ?>

						<?php endif; ?>

						<?php $i++; ?>

					<?php endforeach; ?>

				</div>

				<?php if ( ! empty( $data->image ) ) : ?>

					<div class="row">
						<div class="col-xs-12 text-center">
							<img src="<?php echo $data->image; ?>" class="img-responsive" alt="Section image" />
						</div>
					</div>

				<?php endif; ?>

			</div>
		</div>

	<?php endif; ?>

</section>