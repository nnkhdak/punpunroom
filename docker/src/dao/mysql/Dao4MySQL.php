<?php
namespace dao\mysql;

class Dao4MySQL implements \dao\Dao {

	private $keys = null;
	private $name = null;
	private $vals = null;

	public function __construct($name) {
		$this->name = $name;
	}

	public function __toString() {
		$c = __CLASS__;
		$n = $this->getName();
		$k = empty($this->keys) ? '' : json_encode($this->keys);
		$v = empty($this->vals) ? '' : json_encode($this->vals);
		return sprintf('%s(%s keys%s vals%s)', $c, $n, $k, $v);
	}

	public function analysis($transaction) {
		if (!is_null($this->keys) || !is_null($this->vals)) {
			return;
		}

		if (is_null($transaction)) {
			throw new \Exception('is null', 500);
		}

		$keys = array();
		$vals = array();
		$sql = sprintf('SHOW COLUMNS FROM %s', $this->name);
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

	protected function createWhereClause($columns, $orMode = false) {
		$result = '';
		if (!empty($this->keys)) {
			foreach ($this->keys as $key) {
				$tmp = sprintf('%s = :%s', $key, $key);
				if ($orMode) {
					$tmp = sprintf('(0 = LENGTH( :%s ) OR %s )', $key, $tmp);
				}
				$result = sprintf('%s AND %s', $result, $tmp);
			}
			$result = preg_replace("/^\s*AND\s+/", 'WHERE ', $result);
		}
		return $result;
	}

	public function getKeys() {
		return array_merge($this->keys);
	}

	public function getName() {
		return $this->name;
	}

	public function getVals() {
		return array_merge($this->vals);
	}

	public function loadByKey($transaction, &$dto) {
		$this->analysis($transaction);

		$where = $this->createWhereClause($this->keys, $dto);
		$sql = sprintf('SELECT * FROM %s %s', $this->name, $where);
		$rows = $transaction->fetch($sql, $dto);
		if (empty($rows)) {
			throw new \Exception('Not Found.', 404);
		} else if (count($rows) !== 1) {
			throw new \Exception('Logical', 500);
		}
		$row0 = $rows[0];
		$dto = array_merge($dto, $row0);
	}

	public function read($transaction, $dto) {
		$this->analysis($transaction);
		$where = $this->createWhereClause($this->keys, $dto);
		$sql = sprintf('SELECT * FROM %s %s', $this->name, $where);
		$rows = $transaction->fetch($sql, $dto);
		if (empty($rows)) {
			throw new \Exception('Not Found.', 404);
		}
		return $rows;
	}

	public function setKeys($keys) {
		$this->keys = $keys;
		return $this;
	}

	public function setVals($vals) {
		$this->vals = $vals;
		return $this;
	}
}