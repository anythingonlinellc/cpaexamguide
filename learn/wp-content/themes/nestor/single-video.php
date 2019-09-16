<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php get_header(); ?>

<div id="content-region">
	<div class="container">
		<div class="row">
			<div id="main-content-region" class="main-content col-xs-12 col-md-10 col-md-offset-1">
				<div class="region">
					<div id="blog-single-block" class="blog-single block">
					
						<h2 class="video-title"><?php $content->title(); ?></h2>

						<?php if ( ! post_password_required( $post->ID ) ) : ?>

							<div class="post-media clearfix">
								<?php $t->video->output(get_the_id()); ?>
							</div>

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
