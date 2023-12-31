<?php
namespace dao\mysql;

$root = '..';
require_once('dao/Transaction.php');

use PDO;
use PDOException;

class TransactionPDOImpl implements \dao\Transaction {

	private $pdo = null;

	public function __construct($auto = false) {
        $pdo = $this->createPDO($auto);
        $this->setPDO($pdo);
    }

	public function __destruct() {
		$this->close();
	}

	public function close() {
		if (is_null($this->pdo)) {
			return;
		}        

		try {
			$this->rollback();
		} finally {
		}

		try {
			$this->pdo = null;
		} finally {
		}
	}

	public function commit() {
		if (!is_null($this->pdo) && $this->pdo->inTransaction()) {
			$this->pdo->commit();
		}
	}

	protected function createPDO($auto) {
		throw new \Exception('Please Override!');
	}

	protected function createPDOStatement($sql, $placeHolders) {
		$statement = $this->pdo->prepare($sql);
		if (!empty($placeHolders)) {
			foreach($placeHolders as $key => $val) {
				$pattern = "/\\s+:$key(|\\s+)/";;
				if (!preg_match($pattern, $sql)) {
					continue;
				}
				$statement->bindValue($key, $val);
			}
		}
		return $statement;
	}

	public function exec($value, $placeHolders = null) {
		throw new \Exception('Please Override!');
	}

	public function fetch($value, $placeHolders = null) {
		$rows = null;
		try {
			$statement = $this->createPDOStatement($value, $placeHolders);
			$statement->execute();
			$rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
			return $rows;
		} catch (\Exception $e) {
			throw $e;
		}
		return $rows;
	}

	public function rollback() {
		if (!is_null($this->pdo) && $this->pdo->inTransaction()) {
			$this->pdo->rollback();
		}
	}

	protected function setPDO($pdo) {
		$this->pdo = $pdo;
	}
}