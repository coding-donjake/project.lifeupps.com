<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

try {
	$json['data'] = Flashcard::loadSets($_SESSION['id']);
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>