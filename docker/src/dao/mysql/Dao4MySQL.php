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

		$sql = 'SELECT * FROM person WHERE id = 1';
		$rows = $transaction->fetch($sql, array('id' => 1));
		if (empty($rows)) {
			throw new \Exception('Not Found.', 404);
		}
		$row0 = $rows[0];
		$dto = array_merge($dto, $row0);
	}

	public function read($transaction, $dto) {
		$this->analysis($transaction);
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