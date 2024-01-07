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
	$expected = array('{"id":2,"age":"hehe","name":"bbb"}');
	$actual = array($dto);
	assertHelper($expected, $actual);

	$dto = array('id' => null, 'name' => 'aaa');		// DTO(DataTransferObject)を生成する
	$dto = array_merge($dto, array('age' => 'hehe'));	// 不要項目を追加する
	$actual = $dao->read($transaction, $dto);			// DTOを用いて検索する
	$expected1 = '{"id":1,"name":"aaa"}';
	$expected2 = '{"id":9,"name":"aaa"}';
	$expected = array($expected1, $expected2);
	assertHelper($expected, $actual);

	$dao = DaoFactory::newInstance('double_key');		// DAO(DataAccessObject)を生成する
	$dto = array('key1' => 'aaa', 'key2' => 1);			// DTO(DataTransferObject)を生成する
	$dto = array_merge($dto, array('age' => 'hehe'));	// 不要項目を追加する
	$dao->loadByKey($transaction, $dto);				// 主キーを用いてDTOにデータを読み込む
	$expected = array('{"key1":"aaa","key2":1,"age":"hehe","val1":"@aaa@","val2":111}');
	$actual = array($dto);
	assertHelper($expected, $actual);

	$dto = array('key1' => 'aaa', 'key2' => null);		// DTO(DataTransferObject)を生成する
	$dto = array_merge($dto, array('age' => 'hehe'));	// 不要項目を追加する
	$actual = $dao->read($transaction, $dto);			// DTOを用いて検索する
	$expected1 = '{"key1":"aaa","key2":1,"val1":"@aaa@","val2":111}';
	$expected2 = '{"key1":"aaa","key2":2,"val1":"*aaa*","val2":222}';
	$expected = array($expected1, $expected2);
	assertHelper($expected, $actual);

	$transaction->commit();							// 正常終了したのでcommitする
	echo 'success';
} catch (\Exception $e) {
	echo $e;
	$transaction->rollback();						// 異常終了したのでrollbackする
}

function assertHelper($expected, $actual) {
	if ($expected instanceof \Iterator) {
		$expected = iterator_to_array($expected);
	}
	if ($actual instanceof \Iterator) {
		$actual = iterator_to_array($actual);
	}
	$e = count($expected);
	$a = count($actual);
	assert($e === $a, sprintf('expected:%d actual:%d', $e, $a));

	for ($i = 0; $i < count($expected); $i++) {
		$e = $expected[$i];
		if (is_array($e)) {
			$e = json_encode($e);
		}

		$a = $actual[$i];
		if (is_array($a)) {
			$a = json_encode($a);
		}

		assert($e === $a, sprintf('expected:%s actual:%s i:%d', $e, $a, $i));
	}
}
