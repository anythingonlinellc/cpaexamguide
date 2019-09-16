<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<ul class="pager">
	<li class="previous<?php echo (($prev = $content->prevPostLink())  ? "" : " disabled") ?>">
		<a href="<?php echo ($prev ? $prev : "#"); ?>">&larr; <?php _e("Previous",'Pixelentity Theme/Plugin'); ?></a>
	</li>
	<li class="next<?php echo (($next = $content->nextPostLink())  ? "" : " disabled") ?>">
		<a href="<?php echo ($next ? $next : "#"); ?>"><?php _e("Next",'Pixelentity Theme/Plugin'); ?> &rarr;</a>
	</li>
</ul>