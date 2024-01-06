<?php
namespace dao;

require_once('dao/Dao.php');
require_once('dao/Transaction.php');

class DaoFactory {

	public static function newInstance($keyword) {
		return new \dao\mysql\DaoImpl($keyword);
	}

	public static function newTransaction($auto = false) {
		return new \dao\mysql\TransactionImpl($auto);
	}
}