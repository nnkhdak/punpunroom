<?php
namespace dao;

use \ArrayObject;
use \Exception;

class PersonDao implements Dao {

	public function delete($transaction, $dto) {		
		throw new Exception('Unsupported.', 500);		
	}

	public function loadByKey($transaction, &$dto) {
		$dto['name'] = 'bbb';
	}

	public function read($transaction, $dto) {
		$row1 = json_decode('{"id":1,"name":"aaa"}', true);
		$row2 = json_decode('{"id":2,"name":"bbb"}', true);
		$row3 = json_decode('{"id":3,"name":"ccc"}', true);
		$row9 = json_decode('{"id":9,"name":"aaa"}', true);
		$rows = array($row9, $row3, $row2, $row1);
		if (!empty($dto)) {
			$rows = array($row9, $row1);
		}
		$obj = new ArrayObject($rows);
		$it = $obj->getIterator();
		return $it;
	}

	public function save($transaction, $dto) {
		throw new Exception('Unsupported.', 500);		
	}
}