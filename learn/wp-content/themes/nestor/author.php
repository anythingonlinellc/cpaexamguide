<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $author = $wp_query->get_queried_object(); ?>
<?php $author = empty($author->user_nicename) ? '' : $author->user_nicename; ?>
<?php $t->layout->pageTitle = sprintf(__("Author: %s",'Pixelentity Theme/Plugin'),$author); ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>

<div id="content-region">
	<div class="container">
		<div class="row">
			<div id="main-content-region" class="main-content col-xs-12 col-md-9">
				<div class="region">
					<div id="blog-single-block" class="blog-single block">
						<?php $t->content->loop(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-3 sidebar">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>