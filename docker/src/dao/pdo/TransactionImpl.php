<?php
namespace dao\pdo;

require_once('dao/Transaction.php');

use Exception;
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
		throw new Exception('Please Override!');
	}

	protected function createPDOStatement($sql, $placeHolders) {
		$st = $this->pdo->prepare($sql);

		// 単語単位で配列を作成
		$pattern = ",+()=";
		$pattern = preg_quote($pattern);
		$pattern = sprintf('/[\s%s]+/', $pattern);
		$pieces = preg_split($pattern, $sql);
		$pieces = array_unique($pieces);

		// SQL中のplaceHoldersの全件を繰り返す
		foreach($pieces as $piece) {
			if (!preg_match('/^:[_\-\w]+$/', $piece)) {
				continue;
			}
			$st->bindValue($piece, null, PDO::PARAM_NULL);
		}

		if (!empty($placeHolders)) {

			// DTO中のplaceHoldersの全件を繰り返す
			foreach($placeHolders as $key => $val) {

				// 単語単位の配列に含まれていないか判定
				$tmp = sprintf(':%s', $key);
				if (in_array($tmp, $pieces) !== true) {
					continue;
				}

				if (is_null($val)) {
					$st->bindValue($key, $val, PDO::PARAM_NULL);
				} else {
					$st->bindValue($key, $val);
				}
			}
		}

		return $st;
	}

	public function exec($value, $placeHolders = null) {
		throw new Exception('Please Override!');
	}

	public function fetch($value, $placeHolders = array()) {
		$rows = null;
		try {
			$st = $this->createPDOStatement($value, $placeHolders);
			$st->execute();
			return $st->getIterator();
		} catch (Exception $e) {
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