<?php
namespace dao;

require_once('dao/Dao.php');
require_once('dao/Transaction.php');

class DaoFactory {

	public static function newInstance($keyword) {
		return new \dao\mysql\DaoPDO4MySQL($keyword);
		return new PersonDao();
	}

	public static function newTransaction($auto = false) {
		$transaction = new \dao\mysql\Transaction4Mysql($auto);
		return $transaction;
	}
}