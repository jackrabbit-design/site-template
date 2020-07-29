<?php
	$alert_cookie = 'show';
	if(isset($_COOKIE['alert_cookie']) && $_COOKIE['alert_cookie'] != ''){
		$alert_cookie = $_COOKIE['alert_cookie'];
	}
	global $alert;
	$alert = $alert_cookie;
	if(isset($_POST['alert']) && $_POST['alert'] != ''){
		$alert = $_POST['alert'];
		setcookie( "alert_cookie", $alert, strtotime( '+1 day' ),'/' );
	}
?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="utf-8" />
	<!-- ====================================================

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


	Website created by Jackrabbit | jumpingjackrabbit.com
	===================================================== -->
	<meta name="MSSmartTagsPreventParsing" content="true" /><!--[if lte IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge"/><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php wp_title(); ?></title>
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

<noscript aria-hidden="true"><div style="text-align:center;background-color:#000;color:#fff;padding:5px;">It looks like JavaScript is disabled in your browser. Please enable JavaScript to view the full site.</div></noscript>

<?php
    if(get_field('_display_alert','options') && $alert != 'hide'){
?>
<div id="alert" role="complementary">
	<a href="#" class="close">&times;</a>
</div>
<?php } ?>

<header id="header" role="banner">
	<a id="skipnav" onclick="focusIt();" href="#jumptocontent" aria-label="Skip Navigation">Skip to Main Content</a>
</header>

<div id="body-content" role="main">
	<a id="jumptocontent" name="jumptocontent"></a>
</div>
