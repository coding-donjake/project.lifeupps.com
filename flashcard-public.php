<?php

include_once 'devsupport/devsupport.php';
include_once 'scripts/classes.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- html5 -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?> - Public flashcards</title>
	<link rel="icon" href="<?php echo WEB_DOMAIN.APP_ICON_LOCATION; ?>">

	<!-- cdn -->
	<?php include_once 'components/cdn.php'; ?>

	<!-- css -->
	<link rel="stylesheet" href="/css/main.css?<?php echo APP_VERSION; ?>">

</head>
<body>

	<?php include_once 'components/navigation-v1.php'; ?>
	<?php include_once 'components/flashcard-list-public-sets.php'; ?>
	<?php include_once 'components/modal.php'; ?>
	<?php include_once 'components/footer-v1.php'; ?>
	
</body>
</html>