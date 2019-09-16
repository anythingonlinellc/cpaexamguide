<?php $t =& peTheme();?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>
<?php $background = empty( $meta->splash->video_fallback ) ? '' : 'style="background-image: url(\'' . esc_attr( $meta->splash->video_fallback ) . '\');"'; ?>


<div id="<?php $content->slug(); ?>-intro-video" class="intro video" <?php echo $background; ?>>

	<?php if ( ! empty( $meta->splash->video ) && -1 !== $meta->splash->video ) : ?>

		<?php $video = get_post_meta( $meta->splash->video, 'pe_theme_meta', true ); ?>

		<a class="player" data-property="{videoURL:'<?php echo $video->video->url; ?>',containment:'.intro',startAt:0,mute:true,autoPlay:true,loop:true,opacity:1,printUrl:false,showControls:false}"></a>

	<?php endif; ?>

	<div class="container">
		<div class="row">
			<div class="carousel-caption-left colour-white">

				<?php if ( ! empty( $meta->splash->subtitle ) ) : ?>

					<h2><?php echo $meta->splash->subtitle; ?></h2>

				<?php endif; ?>

				<?php if ( ! empty( $meta->splash->title ) ) : ?>

					<h1><?php echo $meta->splash->title; ?></h1>

				<?php endif; ?>

				<?php if ( ! empty( $meta->splash->description ) ) : ?>

					<p><?php echo $meta->splash->description; ?></p>

				<?php endif; ?>

				<?php if ( ! empty( $meta->splash->button_text ) ) : ?>

					<?php $target = 'yes' === $meta->splash->button_new_window ? 'target="_blank"' : 'target="_self"'; ?>

					<p><a class="btn btn-lg btn-primary scroll" href="<?php echo esc_attr( $meta->splash->button_url ); ?>" role="button" <?php echo $target; ?>><?php echo $meta->splash->button_text; ?></a></p>

				<?php endif; ?>

			</div>
		</div>
	</div>
	<div class="overlay-bg"></div>
</div>