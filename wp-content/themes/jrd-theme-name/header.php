<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="utf-8" />
	<!-- ====================================================
	<?php //phpcs:disable ?>
													   33+,
													   3333
													  '3333
				 :333:                       ,;';,    3333;
				 3333      '33333333+    ,3333333333,:3333
				33333    ,333333'+333   33333+`  .33333333
				3333:   .3333`        .3333'        33333
			   ;3333    3333:         3333+         33333
			   3333+   ,3333         ;3333          3333
			  .3333    3333'         +3333         33333
			  33333   `3333           3333+       +3333
			  3333    33333            333333;;'33333+
			 33333    3333                33333333
	+.     ;33333
	 +333333333:
		+3+'
	<?php //phpcs:enable ?>

	Website created by Jackrabbit | jumpingjackrabbit.com
	===================================================== -->
	<meta name="MSSmartTagsPreventParsing" content="true" /><!--[if lte IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge"/><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link type="text/plain" rel="author" href="/authors.txt" />
	<link type="image/x-icon" rel="shortcut icon" href="/favicon.ico" />
	<?php
	wp_head();
	?>
	<?php
	/* Uncomment and replace GTM-ABCDEFG with your GTM to enable Google Tag Manager
	/*Remember to also add in the GTM ID to the body tag below
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-ABCDEFG');</script>
	<!-- End Google Tag Manager -->
	*/
	?>
</head>
<body <?php body_class(); ?>>
<?php
/* Uncomment and replace GTM-ABCDEFG with your GTM to enable Google Tag Manager
/*Remember to also add in the GTM ID to the head tag above
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ABCDEFG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
*/
?>
<?php wp_body_open(); ?>

<noscript aria-hidden="true"><div style="text-align:center;background-color:#000;color:#fff;padding:5px;">It looks like JavaScript is disabled in your browser. Please enable JavaScript to view the full site.</div></noscript>

<header id="header">
	<a id="skipnav" href="#body-content">Skip to Main Content</a>
	<?php
	if ( get_field( '_display_alert', 'options' ) ) {
		?>
		<div id="alert" role="complementary" style="display:none;">
			<a href="#" class="close">&times;</a>
		</div>
		<?php
	}
	?>
	<div class="wrap">
		<nav id="main-nav" aria-label="Main">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'main-menu',
				'container'      => '',
				'menu_class'     => '',
				'menu_id'        => '',
				'depth'          => 2,
				'walker'         => new Aria_Walker_Nav_Menu(),
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			)
		);
		?>
		</nav>
		<button id="menu-toggle" aria-label="Toggle Mobile Menu"><span></span></button>
	</div>
</header>
