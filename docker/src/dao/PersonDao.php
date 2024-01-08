<?php
namespace dao;

use \ArrayObject;

class PersonDao implements Dao {

	public function loadByKey($transaction, &$dto) {
		$dto['name'] = 'bbb';
	}

	public function read($transaction, $dto) {
		$row1 = json_decode('{"id":1,"name":"aaa"}', true);
		$row2 = json_decode('{"id":2,"name":"bbb"}', true);
		$row3 = json_decode('{"id":3,"name":"ccc"}', true);
		$row9 = json_decode('{"id":9,"name":"aaa"}', true);
		$rows = array($row1, $row2, $row3, $row9);
		if (!empty($dto)) {
			$rows = array($row1, $row9);
		}
		$obj = new ArrayObject($rows);
		$it = $obj->getIterator();
		return $it;
	}

	public function save($transaction, $dto) {
		throw new \Exception('Unsupported.', 500);		
	}
}