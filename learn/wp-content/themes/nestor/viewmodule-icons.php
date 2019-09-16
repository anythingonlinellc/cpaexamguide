<?php $t =& peTheme(); ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-icons" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>

	<div class="social-networks text-center">
		<div class="container">

			<?php if ( ! empty( $data->title ) ) : ?>

				<h2><?php echo $data->title; ?></h2>

			<?php endif; ?>

			<?php if ( ! empty( $data->icons ) ) : ?>

			<div class="row">
				<div class="col-xs-12">
					<ul class="list-inline">

						<?php foreach ( $data->icons as $icon ) : ?>
						
							<li><a href="<?php echo ( empty( $icon['url'] ) ) ? '#' : $icon['url']; ?>" target="_blank"><i class="icon <?php echo $icon['icon']; ?>"></i></a></li>

						<?php endforeach; ?>

					</ul>
				</div>
			</div>

			<?php endif; ?>

		</div>
	</div>

</section>