<?php

class PeThemeNestorTemplate extends PeThemeTemplate  {

	public function __construct(&$master) {
		parent::__construct($master);
	}

	public function paginate_links($loop) {
		if (!$loop) return "";
		
		$classes = "row-fluid post-pagination";
		$all = "";

		if (apply_filters('pe_theme_pager_load_more',false)) {
			$classes .= ' pe-load-more';
			$all = empty($loop->main->all) ? false : $loop->main->all;
			$all = $all ? sprintf('data-all="%s"',esc_attr(json_encode($all))) : "";
		}
?>

	<div class="<?php echo $loop->main->class ?> text-center">
		<ul class="pagination">
			<li class="<?php echo $loop->main->prev->class ?>">
				<a href="<?php echo ( empty( $loop->main->prev->link ) ) ? '#' : $loop->main->prev->link; ?>">&laquo;</a>
			</li>
			<?php while ($page =& $loop->next()): ?>
			<li class="<?php echo $page->class; ?> pe-is-page">
				<a href="<?php echo $page->link; ?>"><?php echo $page->num; ?></a>
			</li>
			<?php endwhile; ?>
			<li class="<?php echo $loop->main->next->class ?>">
				<a href="<?php echo ( empty( $loop->main->next->link ) ) ? '#' : $loop->main->next->link; ?>">&raquo;</a>
			</li>
		</ul>
	</div>

<?php
	}


}

?>