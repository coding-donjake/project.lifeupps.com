<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

try {
	Flashcard::addSet
	(
		trim($_POST['name']),
		trim($_POST['description']),
		strtolower(trim($_POST['privacy'])),
	);
	$json['status'] = 'created';
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>