<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

try {
	Flashcard::updateItem($_POST['id'], strtolower(trim($_POST['type'])), trim($_POST['question']));
	Flashcard::removeItemSelections($_POST['id']);
	if (strtolower(trim($_POST['type'])) == 'enumeration') {
		$id_selection = Flashcard::createSelection($_POST['id'], $_POST['selections'][0]);
	} else if (strtolower(trim($_POST['type'])) == 'selections') {
		for ($i=0; $i < sizeof($_POST['selections']); $i++) { 
			if ($i > 0) {
				Flashcard::createSelection($_POST['id'], $_POST['selections'][$i]);
			} else {
				$id_selection = Flashcard::createSelection($_POST['id'], $_POST['selections'][$i]);
			}
		}
	} else if (strtolower(trim($_POST['type'])) == 'selections-img') {
		if (!Regulator::validateFilesImage($_FILES['selections']['name'])) {
			$json['status'] = 'invalid filetype';
			echo json_encode($json);
			exit();
		} else if (!Regulator::validateFileSizes($_FILES['selections']['size'])) {
			$json['status'] = 'invalid filesize';
			echo json_encode($json);
			exit();
		} else {
			for ($i=0; $i < sizeof($_FILES['selections']['name']); $i++) { 
				$arr = explode('.', $_FILES['selections']['name'][$i]);
				$ext = strtolower(end($arr));
				$filename = uniqid('', true).'.'.$ext;
				move_uploaded_file($_FILES['selections']['tmp_name'][$i], '../files/'.$filename);
				if ($i > 0) {
					Flashcard::createSelection($_POST['id'], $filename);
				} else {
					$id_selection = Flashcard::createSelection($_POST['id'], $filename);
				}
			}
		}
	}
	Flashcard::updateItemSelection($_POST['id'], $id_selection);
	$json['status'] = 'ok';
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>