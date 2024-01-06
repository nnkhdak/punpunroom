<?php
namespace dao\pdo;

require_once('dao/Transaction.php');

use PDO;
use PDOException;

class TransactionImpl implements \dao\Transaction {

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
		$st = $this->pdo->prepare($sql);
		if (!empty($placeHolders)) {
			foreach($placeHolders as $key => $val) {
				if (gettype($val) === 'object') {
					continue;
				}

				if (is_null($val)) {
//					$st->bindValue(":$key", $val, PDO::PARAM_NULL);
				} else {
//					$st->bindValue(":$key", $val);
				}
			}
		}
		return $st;
	}

	public function exec($value, $placeHolders = null) {
		throw new \Exception('Please Override!');
	}

	public function fetch($value, $placeHolders = array()) {
		$rows = null;
		try {
			$st = $this->createPDOStatement($value, $placeHolders);
			$st->execute($placeHolders);
			return $st->getIterator();
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