<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

if (!User::checkUsernameValidity(trim($_POST['username']))) {
	$json['status'] = 'invalid username';
	echo json_encode($json);
	exit();
}

if (!User::checkUsernameAvailability(trim($_POST['username']))) {
	$json['status'] = 'exists username';
	echo json_encode($json);
	exit();
}

try {
	$id = User::createUserAccount(trim($_POST['username']), $_POST['password'], 'active');
	$_SESSION['id'] = $id;
	$json['status'] = 'created';
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>