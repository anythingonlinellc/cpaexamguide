<?php $t =& peTheme(); ?>
<?php if ($t->comments->supported()): ?>
<!--comment section-->
<div id="comments">

	<h6 id="comments-title">
		<?php _e("Comments",'Pixelentity Theme/Plugin'); ?> <span>( <?php $t->content->comments(); ?> )</span>
	</h6>

	<?php $t->comments->show(); ?>
			
	<div class="row">
		<div class="col-md-12">
			<?php $t->comments->pager(); ?>
		</div>
	</div>
			
	<?php $t->comments->form(); ?>
		
	<!--end comments wrap-->
</div>
<!--end comments-->
<?php endif; ?>