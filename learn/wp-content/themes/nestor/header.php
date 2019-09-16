<!DOCTYPE html>
<?php $t =& peTheme();?>
<?php $content =& $t->content; ?>
<?php $meta = $t->content->meta(); ?>
<!--[if IE 8 ]><html class="desktop ie8 no-js" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9 ]><html class="desktop ie9 no-js" <?php language_attributes(); ?>><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" <?php language_attributes();?>><!--<![endif]-->
   
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<title><?php $t->header->title(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="format-detection" content="telephone=no" />

		<!--[if lt IE 9]>
		<script type="text/javascript">/*@cc_on'abbr article aside audio canvas details figcaption figure footer header hgroup mark meter nav output progress section summary subline time video'.replace(/\w+/g,function(n){document.createElement(n)})@*/</script>
		<![endif]-->
		<script type="text/javascript">if(Function('/*@cc_on return document.documentMode===10@*/')()){document.documentElement.className+=' ie10';}</script>
		<script type="text/javascript">(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
		
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<!-- favicon -->
		<link rel="shortcut icon" href="<?php echo $t->options->get("favicon") ?>" />

		<?php $t->font->load(); ?>

		<!-- wp_head() -->
		<?php $t->header->wp_head(); ?>
	</head>

	<body <?php $content->body_class(); ?>>

		<div class="main-wrapper <?php if ( $t->options->get("bodyLayout") && 'boxed' === $t->options->get("bodyLayout") ) echo 'boxed'; ?>">

			<?php $headerType = apply_filters( 'pe_theme_nestor_header_type', $t->options->get("headerType") ); ?>

			<?php if ( 'detailed' === $headerType ) get_template_part( "menu-detailed-top" ); ?>
			
			<header>

				<?php $template = is_page() ? $content->pageTemplate() : false; ?>

				<?php get_template_part( "menu", $headerType ); ?>

			</header>