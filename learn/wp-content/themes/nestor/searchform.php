<form action="<?php echo esc_url(home_url("/")); ?>" class="clearfix">
	<input name="s" id="s" type="text" class="search form-control" placeholder="<?php _e("Search..",'Pixelentity Theme/Plugin'); ?>" value="<?php echo get_search_query() ? get_search_query() : ""; ?>" />
	<input type="submit" class="search-submit btn btn-primary btn-sm margin-top-10" value="<?php _e("Go",'Pixelentity Theme/Plugin'); ?>" />
</form>