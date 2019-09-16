<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<div id="content-region">
	<div class="container">
		<div class="row">
			<div id="main-content-region" class="main-content col-xs-12 col-md-8">
				<div class="region">
					<div id="blog-single-block" class="blog-single block">
						<?php $t->content->loop(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-md-offset-1 col-xs-12 sidebar">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>