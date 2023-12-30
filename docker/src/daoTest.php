dao test
<?php
require_once('vendor/autoload.php');
use dao\DaoFactory;
use dao\Dao;

$transaction = null;
try {
    $transaction = DaoFactory::newTransaction();

    $dao = DaoFactory::newInstance('person');
    $dto = array('id' => 'hoge');
    $dao->loadByKey($transaction, $dto);

    $transaction->commit();
} catch (\Exception $e) {
    $transaction->rollback();
}