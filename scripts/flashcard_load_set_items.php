<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

try {
	$json['data'] = Flashcard::loadSetItems($_POST['id_set']);
	$json['data2'] = [];
	foreach ($json['data'] as $element) {
		$temp = Flashcard::loadSetSelections($element['id']);
		$json['data2'] = array_merge($json['data2'], $temp);
	}
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>