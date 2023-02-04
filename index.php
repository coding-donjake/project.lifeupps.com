<?php

include_once 'devsupport/devsupport.php';
include_once 'scripts/classes.php';

unset($_SESSION['id']);

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- html5 -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?> - Log in or create account</title>
	<link rel="icon" href="<?php echo WEB_DOMAIN.APP_ICON_LOCATION; ?>">

	<!-- cdn -->
	<?php include_once 'components/cdn.php'; ?>

	<!-- SEO meta tags -->
	<meta name="description" content="<?php echo APP_TAGLINE; ?>">

	<!-- facebook meta tags -->
	<meta property="og:title" content="<?php echo APP_NAME; ?> - <?php echo APP_TAGLINE; ?>">
	<meta property="og:description" content="<?php echo APP_TAGLINE; ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo WEB_DOMAIN; ?>">
	<meta property="og:image" content="<?php echo WEB_DOMAIN.APP_BANNER_LOCATION; ?>">
	<meta property="fb:app_id" content="<?php echo FB_APP_ID; ?>">

	<!-- twitter meta tags -->
	<meta name="twitter:title" content="<?php echo APP_NAME; ?> - <?php echo APP_TAGLINE; ?>">
	<meta name="twitter:description" content="<?php echo APP_TAGLINE; ?>">
	<meta name="twitter:image" content="<?php echo WEB_DOMAIN.APP_BANNER_LOCATION; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="<?php echo TWITTER_USERNAME; ?>">

	<!-- css -->
	<link rel="stylesheet" href="/css/main.css?<?php echo APP_VERSION; ?>">

</head>
<body>

	<?php include_once 'components/form-login.php'; ?>
	<?php include_once 'components/index-introduction.php'; ?>
	<?php include_once 'components/footer-v1.php'; ?>
	
</body>
</html>