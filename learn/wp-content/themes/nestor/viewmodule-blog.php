<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php list($data,$bid) = $t->template->data(); ?>
<?php $style = ''; ?>
<?php if ( ! empty( $data->bgcolor ) ) $style .= 'background-color: ' . $data->bgcolor . ';'; ?>
<?php if ( ! empty( $data->bgimage ) ) $style .= 'background-image: url(\'' . $data->bgimage . '\');'; ?>
<?php if ( ! empty( $style ) ) $style = 'style="' . $style . '"'; ?>

<section class="padding-top-<?php echo $data->padding_top; ?> padding-bottom-<?php echo $data->padding_bottom; ?> <?php if ( 'light' === $data->typography ) echo 'text-color-light'; ?> bg-image-cover section-type-blog" id="section-<?php echo empty($data->name) ? $bid : $data->name; ?>" <?php echo $style; ?>>
	
		<div class="container">
		<div class="row">
			<div class="main-content col-xs-12 col-md-8">

				<?php if ( ! empty( $data->title ) ) : ?>
			
					<h2 class="block-title"><?php echo $data->title; ?></h2>

				<?php endif; ?>

				<?php $t->template->data($data); ?>

				<div class="region">
					<div class="blog-single block">

						<?php $t->get_template_part("loop"); ?>
						
					</div>
				</div>

			</div>
			<div class="col-md-3 col-md-offset-1 col-xs-12 sidebar">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>

</section>