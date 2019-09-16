<?php $t =& peTheme(); ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-highlight" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?> data-stellar-background-ratio="0.7">

	<div class="highlighted-image-1" style="height: <?php echo ( empty( $data->height ) ) ? '500' : $data->height; ?>px;">
		<div class="overlay text-color-light">
			<div class="vertical-center text-<?php echo ( empty( $data->alignment ) ) ? 'left' : $data->alignment; ?>">
				<div class="container">

					<?php if ( ! empty( $data->title ) ) : ?>

						<h1><?php echo $data->title; ?></h1>

					<?php endif; ?>

					<?php if ( ! empty( $data->subtitle ) ) : ?>

						<p><?php echo $data->subtitle; ?></p>

					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>

</section>