<?php
namespace dao;

require_once('dao/Dao.php');
require_once('dao/Dao4MySQL.php');
require_once('dao/PersonDao.php');
require_once('dao/Transaction.php');
require_once('dao/Transaction4MySQL.php');

class DaoFactory {

    public static function newInstance($keyword) {
        return new Dao4MySQL();
        return new PersonDao();
    }

    public static function newTransaction($auto = false) {
        $transaction = new Transaction4Mysql($auto);
        return $transaction;
    }
}