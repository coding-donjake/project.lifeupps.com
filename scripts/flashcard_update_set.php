<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

try {
	Flashcard::updateSet
	(
		$_POST['id'],
		trim($_POST['name']),
		trim($_POST['description']),
		strtolower(trim($_POST['privacy'])),
	);
	$json['status'] = 'ok';
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>