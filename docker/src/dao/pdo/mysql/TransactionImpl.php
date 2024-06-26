<?php
namespace dao\pdo\mysql;

require_once('dao/Transaction.php');
require_once('dao/pdo/TransactionImpl.php');

use PDO;
use PDOException;

class TransactionImpl extends \dao\pdo\TransactionImpl implements \dao\Transaction {

	public function __construct($auto = false) {
		parent::__construct($auto);
	}
	
	protected function createPDO($auto) {
		$host = 'db';
		$db = 'test';
		$charset = 'utf8';
		$dsn = "mysql:host=$host; dbname=$db; charset=$charset";

		$user = 'root';
		$pass = 'pass';

		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];

		$pdo = null;
		try {
			$pdo = new PDO($dsn, $user, $pass, $options);
			if (!$auto) {
				$pdo->beginTransaction();
			}

		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		return $pdo;
	}
}