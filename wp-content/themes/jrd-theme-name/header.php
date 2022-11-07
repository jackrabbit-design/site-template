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
	/* Uncomment and replace UA-XXX-Y
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXX-Y"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-XXX-Y');
	</script>
	*/
	?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<noscript aria-hidden="true"><div style="text-align:center;background-color:#000;color:#fff;padding:5px;">It looks like JavaScript is disabled in your browser. Please enable JavaScript to view the full site.</div></noscript>

<header id="header" role="banner">
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
		<nav id="main-nav" aria-label="Primary Site Navigation" tabindex="-1">
			<ul>
				<li>
					<a href="#">Menu Item 1</a>
				</li>
				<li>
					<a href="#">Menu Item 2</a>
				</li>
				<li>
					<a href="#">Menu Item 3</a>
				</li>
			</ul>
		</nav>
		<button id="menu-toggle"><span></span></button>
	</div>
</header>
