<?php

date_default_timezone_set('Asia/Manila');
session_start();

// MySQL database info

define('HOSTNAME', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'lifeupps_v2');

// define('HOSTNAME', 'localhost');
// define('USERNAME', 'lifeupps_main');
// define('PASSWORD', 'x6YVIU%leP@U');
// define('DATABASE', 'lifeupps_main');



// web domain name

define('WEB_DOMAIN', '/');
// define('WEB_DOMAIN', 'https://www.lifeupps.com/');



// App info

define('APP_NAME', 'Life Upps');
define('APP_TAGLINE', 'Create, organize, share, and improve!');
define('APP_VERSION', '1.2.0');
define('APP_ICON_LOCATION', 'files/system/icon.png');
define('APP_LOGO_LOCATION', 'files/system/logo.png');
define('APP_BANNER_LOCATION', 'files/system/banner.png');



// facebook credentials

define('FB_APP_ID', 'your_app_id');



// twitter credentials

define('TWITTER_USERNAME', '@website-username');



// DBMS classes

class User {

	static function checkUsernameAvailability(string $username) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT `id` FROM `user_accounts` WHERE `username` = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		if ($result->num_rows > 0) {
			return false;
		} else {
			return true;
		}
	}

	static function checkUsernameValidity(string $username) {
		if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,49}$/', $username)) {
			return false;
		} else {
			return true;
		}
	}

	static function confirmUserAccount(int $id) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `user_accounts` SET `status` = 'active' WHERE `id` = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function confirmUserIdentificationFile(int $id_user_account) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `user_identification_file` SET `status` = 'confirmed' WHERE `id_user_account` = ?");
		$stmt->bind_param('i', $id_user_account);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function createUserAccount(string $username, string $password, string $status) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("INSERT INTO `user_accounts` (`username`, `password`, `status`) VALUES (?, ?, ?)");
		$stmt->bind_param('sss', $username, $password, $status);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$stmt->execute();
		$id = $conn->insert_id;
		$stmt->close();
		$conn->close();
		return $id;
	}

	static function createUserIdentificationFile(int $id_user_account, string $type, string $filename, string $status) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("INSERT INTO `user_identification_file` (`id_user_account`, `type`, `filename`, `status`) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('isss', $id_user_account, $type, $filename, $status);
		$stmt->execute();
		$id = $conn->insert_id;
		$stmt->close();
		$conn->close();
		return $id;
	}

	static function createUserPersonalData(int $id_user_account, string $lastname, string $firstname, string $middlename, string $suffix, string $gender, string $birthdate) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("INSERT INTO `user_personal_data` (`id_user_account`, `lastname`, `firstname`, `middlename`, `suffix`, `gender`, `birthdate`) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('issssss', $id_user_account, $lastname, $firstname, $middlename, $suffix, $gender, $birthdate);
		$stmt->execute();
		$id = $conn->insert_id;
		$stmt->close();
		$conn->close();
		return $id;
	}

	static function declineUserAccount(int $id) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `user_accounts` SET `status` = 'declined' WHERE `id` = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function getUserData(int $id_user_account) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT * FROM `user_personal_data` WHERE `id_user_account` = ?");
		$stmt->bind_param('i', $id_user_account);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		$row = $result->fetch_assoc();
		return $row;
	}

	static function getUserID(string $username) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT `id` FROM `user_accounts` WHERE `username` = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		if ($result->num_rows <= 0) {
			return 0;
		} else {
			$row = $result->fetch_assoc();
			return $row['id'];
		}
	}

	static function getUsername(int $id) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT `username` FROM `user_accounts` WHERE `id` = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		$row = $result->fetch_assoc();
		return $row['username'];
	}

	static function login(string $username, string $password) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT `id`, `username`, `password` FROM `user_accounts` WHERE `username` = ? AND `status` = 'active'");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		if ($result->num_rows != 1) {
			return 'not found user';
		} else {
			$row = $result->fetch_assoc();
			if (!password_verify($password, $row['password'])) {
				return 'invalid password';
			} else {
				$_SESSION['id'] = $row['id'];
				return 'ok';
			}
		}
	}

	static function setupUserAccountsTable() {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS `user_accounts` (`id` BIGINT NOT NULL AUTO_INCREMENT , `username` VARCHAR(50) NOT NULL , `password` VARCHAR(255) NOT NULL , `status` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`), INDEX (`status`), UNIQUE (`username`)) ENGINE = InnoDB;");
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function setupUserIdentificationFileTable() {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS `user_identification_file` (`id` BIGINT NOT NULL AUTO_INCREMENT , `id_user_account` BIGINT NOT NULL , `id_file` BIGINT NOT NULL , `type` VARCHAR(30) NOT NULL , `status` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user_account`), INDEX (`id_file`), INDEX (`type`), INDEX (`status`)) ENGINE = InnoDB;");
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function setupUserPersonalDataTable() {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS `user_personal_data` (`id` BIGINT NOT NULL AUTO_INCREMENT , `id_user_account` BIGINT NOT NULL , `lastname` VARCHAR(255) NOT NULL , `firstname` VARCHAR(255) NOT NULL , `middlename` VARCHAR(255) NOT NULL , `suffix` VARCHAR(50) NOT NULL , `gender` VARCHAR(30) NOT NULL , `birthdate` DATE NOT NULL , PRIMARY KEY (`id`), INDEX (`id_user_account`), INDEX (`lastname`), INDEX (`firstname`), INDEX (`middlename`), INDEX (`suffix`), INDEX (`gender`), INDEX (`birthdate`)) ENGINE = InnoDB;");
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function updateUserPersonalData(int $id_user_account, string $lastname, string $firstname, string $middlename, string $suffix, string $gender, string $birthdate) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("UPDATE `user_personal_data` SET `lastname` = ?, `firstname` = ?, `middlename` = ?, `suffix` = ?, `gender` = ?, `birthdate` = ? WHERE `id_user_account` = ?");
		$stmt->bind_param('ssssssi', $lastname, $firstname, $middlename, $suffix, $gender, $birthdate, $id_user_account);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	static function verify() {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("SELECT `id` FROM `user_accounts` WHERE `id` = ? AND `status` = 'active'");
		$stmt->bind_param('i', $_SESSION['id']);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$conn->close();
		if ($result->num_rows != 1) {
			return false;
		} else {
			return true;
		}
	}

}



class Log {

	static function createLog(int $id_obejct, string $type, string $log_data) {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("INSERT INTO `logs` (`datetime`, `id_object`, `type`, `log_data`) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('siss', $datetime, $id_obejct, $type, $log_data);
		$datetime = date('Y-m-d H:i:s');
		$stmt->execute();
		$id = $conn->insert_id;
		$stmt->close();
		$conn->close();
		return $id;
	}

	static function setupLogsTable() {
		$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
		$stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS `logs` (`id` BIGINT NOT NULL AUTO_INCREMENT , `datetime` DATETIME NOT NULL , `id_object` BIGINT NOT NULL , `type` VARCHAR(30) NOT NULL , `log_data` TEXT NOT NULL , PRIMARY KEY (`id`), INDEX (`datetime`), INDEX (`id_object`), INDEX (`type`)) ENGINE = InnoDB;");
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

}



// devsupport classes

class NonObjectClasses {

	public static $currencies = [
		'PHP' => '&#8369;',
		'USD' => '$',
	];

	public static function to_currency($currency, $value, $decimals) {
		return NonObjectClasses::$currencies[$currency].number_format($value, $decimals);
	}

}



class Regulator {

	static $files = [
		'images' => [
			'gif',
			'jpeg',
			'jpg',
			'png',
			'webp',
		],
		'max_size' => 6000000,
	];

	static function validateFilesImage($fileNames) {
		$valid = true;
		foreach ($fileNames as $element) {
			$arr = explode('.', $element);
			$ext = strtolower(end($arr));
			if (!in_array($ext, Regulator::$files['images'])) {
				$valid = false;
				break;
			}
		}
		return $valid;
	}

	static function validateFileSizes($fileSizes) {
		$valid = true;
		foreach ($fileSizes as $element) {
			if ($element > Regulator::$files['max_size']) {
				$valid = false;
				break;
			}
		}
		return $valid;
	}

}

?>