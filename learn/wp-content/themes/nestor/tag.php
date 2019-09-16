<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php $t->layout->pageTitle = sprintf(__("Tag: %s",'Pixelentity Theme/Plugin'),single_tag_title("",false)); ?>
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