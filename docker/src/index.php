<?php
require_once('vendor/autoload.php');
use dao\DaoFactory;
use dao\Dao;

$transaction = null;
try {
	$transaction = DaoFactory::newTransaction();	// トランザクションを生成する
	$dao = DaoFactory::newInstance('person');		// DAO(DataAccessObject)を生成する
	$dto = array('id' => 1);						// DTO(DataTransferObject)を生成する
	$dao->loadByKey($transaction, $dto);			// 主キーを用いてDTOにデータを読み込む

echo json_encode($dto);
echo PHP_EOL.'<br>';
echo ($dao);

	$transaction->commit();							// 正常終了したのでcommitする
} catch (\Exception $e) {
	$transaction->rollback();						// 異常終了したのでrollbackする
}
