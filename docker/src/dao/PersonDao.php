<?php
namespace dao;

class PersonDao implements Dao {

	public function loadByKey($transaction, &$dto) {
		$dto['name'] = 'bbb';
	}

	public function read($transaction, $dto) {
	}
}