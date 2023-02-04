<?php

class Flashcard {

	static function createSelection(int $id_item, string $content) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("INSERT INTO `flashcard_item_selections` (
			`datetime_added`,
			`id_item`,
			`content`
		) VALUES (
			?,
			?,
			?
		)");
		$stmt->bind_param(
			'sis',
			$datetime_added,
			$id_item,
			$content,
		);
		$datetime_added = date('Y-m-d H:i:s');
		$stmt->execute();
		$id_selection = $conn->insert_id;
		$stmt->close();
		$conn->close();
		return $id_selection;
	}

	static function createItem(int $id_set, string $type, string $question) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("INSERT INTO `flashcard_set_items` (
			`datetime_added`,
			`id_set`,
			`type`,
			`question`
		) VALUES (
			?,
			?,
			?,
			?
		)");
		$stmt->bind_param(
			'siss',
			$datetime_added,
			$id_set,
			$type,
			$question,
		);
		$datetime_added = date('Y-m-d H:i:s');
		$stmt->execute();
		$id_item = $conn->insert_id;
		$stmt->close();
		$conn->close();
		return $id_item;
	}
	
	static function addSet(string $name, string $description, string $privacy) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("INSERT INTO `flashcard_sets` (
			`datetime_added`,
			`id_account`,
			`name`,
			`description`,
			`privacy`
		) VALUES (
			?,
			?,
			?,
			?,
			?
		)");
		$stmt->bind_param(
			'sisss',
			$datetime_added,
			$_SESSION['id'],
			$name,
			$description,
			$privacy,
		);
		$datetime_added = date('Y-m-d H:i:s');
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function loadPublicSets() {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT `flashcard_sets`.*, `user_accounts`.`username` FROM `flashcard_sets` JOIN `user_accounts` ON `flashcard_sets`.`id_account` = `user_accounts`.id WHERE `flashcard_sets`.`privacy` = 'public' AND `flashcard_sets`.`status` = 'active' ORDER BY `flashcard_sets`.`name` ASC");
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		$arr = [];
		while ($row = $result->fetch_assoc()) {
			$row['datetime_added'] = date('M-d-Y h:i A', strtotime($row['datetime_added']));
			$arr[sizeof($arr)] = $row;
		}
		return $arr;
	}

	static function loadSets(int $id_account) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT `flashcard_sets`.*, `user_accounts`.`username` FROM `flashcard_sets` JOIN `user_accounts` ON `flashcard_sets`.`id_account` = `user_accounts`.id WHERE `flashcard_sets`.`id_account` = ? AND `flashcard_sets`.`status` = 'active' ORDER BY `flashcard_sets`.`name` ASC");
		$stmt->bind_param('i', $id_account);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		$arr = [];
		while ($row = $result->fetch_assoc()) {
			$row['datetime_added'] = date('M-d-Y h:i A', strtotime($row['datetime_added']));
			$arr[sizeof($arr)] = $row;
		}
		return $arr;
	}

	static function loadSetItems(int $id_set) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT * FROM `flashcard_set_items` WHERE `id_set` = ? AND `status` = 'active' ORDER BY `id` ASC");
		$stmt->bind_param('i', $id_set);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		$arr = [];
		while ($row = $result->fetch_assoc()) {
			$row['datetime_added'] = date('M-d-Y h:i A', strtotime($row['datetime_added']));
			$arr[sizeof($arr)] = $row;
		}
		return $arr;
	}

	static function loadSetSelections(int $id_item) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT * FROM `flashcard_item_selections` WHERE `id_item` = ? AND `status` = 'active' ORDER BY `id` ASC");
		$stmt->bind_param('i', $id_item);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		$arr = [];
		while ($row = $result->fetch_assoc()) {
			$row['datetime_added'] = date('M-d-Y h:i A', strtotime($row['datetime_added']));
			$arr[sizeof($arr)] = $row;
		}
		return $arr;
	}

	static function removeItemSelections(int $id_item) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `flashcard_item_selections` SET `datetime_removed` = ?, `status` = 'removed' WHERE `id_item` = ?");
		$stmt->bind_param('si', $datetime_removed, $id_item);
		$datetime_removed = date('Y-m-d H:i:s');
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function updateItem(int $id, string $type, string $question) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `flashcard_set_items` SET `type` = ?, `question` = ? WHERE `id` = ?");
		$stmt->bind_param(
			'ssi',
			$type,
			$question,
			$id,
		);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function updateItemSelection(int $id_item, int $id_selection) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `flashcard_set_items` SET `id_selection` = ? WHERE `id` = ?");
		$stmt->bind_param('ii', $id_selection, $id_item);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function updateSet(int $id, string $name, string $description, string $privacy) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `flashcard_sets` SET `name` = ?, `description` = ?, `privacy` = ? WHERE `id` = ?");
		$stmt->bind_param(
			'sssi',
			$name,
			$description,
			$privacy,
			$id,
		);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

}

?>