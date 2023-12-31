<?php
namespace dao\mysql;

class Dao4MySQL implements \dao\Dao {

	public function loadByKey($transaction, &$dto) {
		$sql = 'SELECT * FROM person WHERE id = 1';
		$rows = $transaction->fetch($sql, array('id' => 1));
		if (empty($rows)) {
			throw new \Exception('Not Found.', 404);
		}
		$row0 = $rows[0];
		$dto = array_merge($dto, $row0);
	}
}