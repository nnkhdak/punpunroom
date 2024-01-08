<?php
namespace dao\pdo\mysql;

require_once('dao/pdo/DaoImpl.php');

use \Exception;

class DaoImpl extends \dao\pdo\DaoImpl implements \dao\Dao {

	public function __construct($name) {
		parent::__construct($name);
	}

	public function analysis($transaction) {
		if (!is_null($this->getKeys()) || !is_null($this->getVals())) {
			return;
		}

		if (is_null($transaction)) {
			throw new Exception('is null', 500);
		}

		$keys = array();
		$vals = array();
		$sql = sprintf('SHOW COLUMNS FROM %s', $this->getName());
		$rows = $transaction->fetch($sql);
		foreach ($rows as $row) {
			$field = $row['Field'];
			$isKey = $row['Key'] === 'PRI';
			if ($isKey) {
				$keys[] = $field;
			} else {
				$vals[] = $field;
			}
		}
		$this->setKeys($keys);
		$this->setVals($vals);
	}

	protected function createSaveClause() {
		throw new Exception('Please Override!');
	}

	protected function createWhereClause() {
		$result = '';
		$all = array_merge($this->getKeys(), $this->getVals());
		foreach ($all as $key) {
			$tmp = sprintf('%s = :%s', $key, $key);
			$tmp = sprintf("(0 = LENGTH(IFNULL(:%s, '')) OR %s )", $key, $tmp);
			$result = sprintf('%s AND %s', $result, $tmp);
		}
		$result = preg_replace("/^\s*AND\s+/", 'WHERE ', $result);
		return $result;
	}
}