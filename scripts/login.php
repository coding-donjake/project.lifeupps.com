<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

try {
	$json['status'] = User::login(trim($_POST['username']), $_POST['password']);
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>