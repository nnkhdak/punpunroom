<?php
require_once('vendor/autoload.php');
use dao\DaoFactory;
use dao\Dao;

$transaction = null;
try {
	$transaction = DaoFactory::newTransaction();	// トランザクションを生成する
	$dao = DaoFactory::newInstance('person');		// DAO(DataAccessObject)を生成する
	$dto = array('id' => 2);						// DTO(DataTransferObject)を生成する
$dto = array_merge($dto, array('age' => 'hehe'));
	$dao->loadByKey($transaction, $dto);			// 主キーを用いてDTOにデータを読み込む
echo json_encode($dto);
echo "\n<br>";

	$dao = DaoFactory::newInstance('double_key');	// DAO(DataAccessObject)を生成する
	$dto = array('key1' => 'aaa', 'key2' => 1);		// DTO(DataTransferObject)を生成する
	$dao->loadByKey($transaction, $dto);			// 主キーを用いてDTOにデータを読み込む
echo json_encode($dto);
echo "\n<br>";

	$dto = array('key1' => 'aaa', 'key2' => null);	// DTO(DataTransferObject)を生成する
	$it = $dao->read($transaction, $dto);			// DTOを用いて検索する
	while ($it->valid()) {
		$row = $it->current();
echo json_encode($row);
echo "\n<br>";
		$it->next();
	}

	$transaction->commit();							// 正常終了したのでcommitする
} catch (\Exception $e) {
	echo $e;
	$transaction->rollback();						// 異常終了したのでrollbackする
}
