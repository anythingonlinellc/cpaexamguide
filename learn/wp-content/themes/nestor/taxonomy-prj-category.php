<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $t->layout->pageTitle = sprintf(__("Category: %s",'Pixelentity Theme/Plugin'),single_cat_title("",false));; ?>
<?php get_header(); ?>

<section class="container">
	<div class="row">
		<div class="col-sm-8">
			<?php $t->content->loop(); ?>
		</div>
		<div class="col-sm-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>