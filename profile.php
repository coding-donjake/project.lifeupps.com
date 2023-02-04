<?php

include_once 'scripts/main.php';
include_once 'devsupport/devsupport.php';
include_once 'scripts/classes.php';

if (!User::verify()) {
	header('Location: index');
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- html5 -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?> - Profile</title>
	<link rel="icon" href="<?php echo WEB_DOMAIN.APP_ICON_LOCATION; ?>">

	<!-- cdn -->
	<?php include_once 'components/cdn.php'; ?>

	<!-- css -->
	<link rel="stylesheet" href="/css/main.css?<?php echo APP_VERSION; ?>">

	<!-- js -->
	<link rel="stylesheet" href="/devsupport/devsupport.js?<?php echo APP_VERSION; ?>">

</head>
<body>

	<?php include_once 'components/navigation-v1.php'; ?>
	<?php include_once 'components/profile.php'; ?>
	<?php include_once 'components/footer-v1.php'; ?>
	
</body>
</html>