<?php
require_once('vendor/autoload.php');
use dao\DaoFactory;
use dao\Dao;

$transaction = null;
try {
	$transaction = DaoFactory::newTransaction();		// トランザクションを生成する
	$dao = DaoFactory::newInstance('person');			// DAO(DataAccessObject)を生成する
	$dto = array('id' => 2);							// DTO(DataTransferObject)を生成する
	$dto = array_merge($dto, array('age' => 'hehe'));	// 不要項目を追加する
	$dao->loadByKey($transaction, $dto);				// 主キーを用いてDTOにデータを読み込む
	echo json_encode($dto) . "\n<br>";
	assert('{"id":2,"age":"hehe","name":"bbb"}' === json_encode($dto));

	$dao = DaoFactory::newInstance('double_key');		// DAO(DataAccessObject)を生成する
	$dto = array('key1' => 'aaa', 'key2' => 1);			// DTO(DataTransferObject)を生成する
	$dto = array_merge($dto, array('age' => 'hehe'));	// 不要項目を追加する
	$dao->loadByKey($transaction, $dto);				// 主キーを用いてDTOにデータを読み込む
	echo json_encode($dto) . "\n<br>";
	assert('{"key1":"aaa","key2":1,"age":"hehe","val1":"@aaa@","val2":111}' === json_encode($dto));

	$dto = array('key1' => 'aaa', 'key2' => null);		// DTO(DataTransferObject)を生成する
	$dto = array_merge($dto, array('age' => 'hehe'));	// 不要項目を追加する
	$it = $dao->read($transaction, $dto);				// DTOを用いて検索する
	$rows = array();
	while ($it->valid()) {
		$row = $it->current();
		$rows[] = $row;
		$it->next();
	}
	echo json_encode($rows[0]) . "\n<br>";
	assert('{"key1":"aaa","key2":1,"val1":"@aaa@","val2":111}' === json_encode($rows[0]));
	echo json_encode($rows[1]) . "\n<br>";
	assert('{"key1":"aaa","key2":2,"val1":"*aaa*","val2":222}' === json_encode($rows[1]));

	$transaction->commit();							// 正常終了したのでcommitする
} catch (\Exception $e) {
	echo $e;
	$transaction->rollback();						// 異常終了したのでrollbackする
}
