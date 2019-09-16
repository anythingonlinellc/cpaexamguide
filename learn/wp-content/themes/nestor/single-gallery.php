<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<div id="content-region">
	<div class="container">
		<div class="row">
			<div id="main-content-region" class="main-content col-xs-12 col-md-10 col-md-offset-1">
				<div class="region">
					<div id="blog-single-block" class="blog-single block">

						<h2 class="gallery-title"><?php $content->title(); ?></h2>

						<?php if ( ! post_password_required( $post->ID ) ) : ?>

							<?php if ($loop = $t->gallery->getSliderLoop(get_the_id())): ?>
							
								<div class="flex-arrow-slider">
									<ul class="slides">

										<?php while ($item =& $loop->next()): ?>

											<li><?php echo $t->image->resizedImg( $item->img, 1280, 0 ); ?></li>

										<?php endwhile; ?>

									</ul>
								</div>

							<?php endif; ?>

						<?php else : ?>

							<?php echo get_the_password_form(); ?>

						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>