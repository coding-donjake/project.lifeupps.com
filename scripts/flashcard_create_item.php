<?php

include_once '../devsupport/devsupport.php';
include_once 'classes.php';

$json = [];

try {
	$id_item = Flashcard::createItem($_POST['id_set'], strtolower(trim($_POST['type'])), trim($_POST['question']));
	if (strtolower(trim($_POST['type'])) == 'enumeration') {
		$id_selection = Flashcard::createSelection($id_item, $_POST['selections'][0]);
	} else if (strtolower(trim($_POST['type'])) == 'selections') {
		for ($i=0; $i < sizeof($_POST['selections']); $i++) { 
			if ($i > 0) {
				Flashcard::createSelection($id_item, $_POST['selections'][$i]);
			} else {
				$id_selection = Flashcard::createSelection($id_item, $_POST['selections'][$i]);
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
					Flashcard::createSelection($id_item, $filename);
				} else {
					$id_selection = Flashcard::createSelection($id_item, $filename);
				}
			}
		}
	}
	Flashcard::updateItemSelection($id_item, $id_selection);
	$json['status'] = 'created';
} catch (Exception $e) {
	$json['status'] = 'query denied';
}

echo json_encode($json);

?>