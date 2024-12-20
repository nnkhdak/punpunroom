<?php
namespace dao\pdo;

use \Exception;

class DaoImpl implements \dao\Dao {

	private $keys = null;
	private $name = null;
	private $vals = null;

	public function __construct($name) {
		$this->name = $name;
	}

	public function __toString() {
		$c = get_class($this);
		$n = $this->getName();
		$k = empty($this->keys) ? '' : json_encode($this->keys);
		$v = empty($this->vals) ? '' : json_encode($this->vals);
		return sprintf('%s(%s keys%s vals%s)', $c, $n, $k, $v);
	}

	public function analysis($transaction) {
		throw new Exception('Please Override!');
	}

	protected function createOrderByClause() {
		$keys = $this->getKeys();
		$result = '';
		foreach ($keys as $key) {
			$tmp = sprintf('%s DESC', $key, $key);
			$result = sprintf('%s , %s', $result, $tmp);
		}
		$result = preg_replace("/^\s*,\s+/", 'ORDER BY ', $result);
		return $result;
	}

	protected function createSaveClause() {
		throw new Exception('Please Override!');
	}

	protected function createWhereClause() {
		throw new Exception('Please Override!');
	}

	protected function createWhereClauseByKey() {
		$keys = $this->getKeys();
		if (empty($keys)) {
			throw new Exception('Please Override!');
		}

		$result = '';
		foreach ($keys as $key) {
			$tmp = sprintf('%s = :%s', $key, $key);
			$result = sprintf('%s AND %s', $result, $tmp);
		}
		$result = preg_replace("/^\s*AND\s+/", 'WHERE ', $result);
		return $result;
	}

	public function delete($transaction, $dto) {
		$this->analysis($transaction);

		$w = $this->createWhereClauseByKey();
		$n = $this->getName();
		$sql = sprintf('DELETE FROM %s %s', $n, $w);
		$tmp = array();
		foreach ($this->getKeys() as $key) {
			if (!array_key_exists($key, $dto)) {
				throw new Exception('Not Found.', 404);
			}
			$val = $dto[$key];
			$tmp[$key] = $val;
		}
		$transaction->exec($sql, $dto);
	}

	public function getKeys() {
		return is_null($this->keys) ? null : array_merge($this->keys);
	}

	public function getName() {
		return $this->name;
	}

	public function getVals() {
		return is_null($this->vals) ? null : array_merge($this->vals);
	}

	public function loadByKey($transaction, &$dto) {
		$this->analysis($transaction);

		$w = $this->createWhereClauseByKey();
		$n = $this->getName();
		$sql = sprintf('SELECT * FROM %s %s', $n, $w);
		$tmp = array();
		foreach ($this->getKeys() as $key) {
			if (!array_key_exists($key, $dto)) {
				throw new Exception('Not Found.', 404);
			}
			$val = $dto[$key];
			$tmp[$key] = $val;
		}
		$it = $transaction->fetch($sql, $dto);
		if ($it->valid()) {
			$row = $it->current();
			$it->next();
			if ($it->valid()) {
				while ($it->valid()) {
					$row = $it->current();
					$it->next();
				}
				throw new Exception('Logical', 500);
			}
			$dto = array_merge($dto, $row);
		} else {
			throw new Exception('Not Found.', 404);			
		}
	}

	public function read($transaction, $dto) {
		$this->analysis($transaction);
		$w = $this->createWhereClause();
		$n = $this->getName();
		$o = $this->createOrderByClause();
		$sql = sprintf('SELECT * FROM %s %s %s', $n, $w, $o);
		return $transaction->fetch($sql, $dto);
	}
	
	public function save($transaction, $dto) {
		$this->analysis($transaction);
		$sql = $this->createSaveClause();
		return $transaction->exec($sql, $dto);
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