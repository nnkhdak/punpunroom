<?php
namespace dao;

class DaoPDOImpl implements \dao\Dao {

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
		throw new \Exception('Please Override!');
	}

	protected function createWhereClause($columns, $orMode = false) {
		throw new \Exception('Please Override!');
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

		$where = $this->createWhereClause($this->keys, $dto);
		$sql = sprintf('SELECT * FROM %s %s', $this->name, $where);
		$it = $transaction->fetch($sql, $dto);
		if ($it->valid()) {
			$row = $it->current();
			$it->next();
			if ($it->valid()) {
				while ($it->valid()) {
					$row = $it->current();
					$it->next();
				}
				throw new \Exception('Logical', 500);
			}
			$dto = array_merge($dto, $row);
		} else {
			throw new \Exception('Not Found.', 404);			
		}
	}

	public function read($transaction, $dto) {
		$this->analysis($transaction);
		$where = $this->createWhereClause($this->keys, $dto);
		$sql = sprintf('SELECT * FROM %s %s', $this->name, $where);
		return $transaction->fetch($sql, $dto);
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