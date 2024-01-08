<?php
require_once('vendor/autoload.php');

use dao\DaoFactory;

$transaction = null;
try {
	$transaction = DaoFactory::newTransaction();				// トランザクションを生成する

	if (true) {

		// loadByKeyのassert
		require_once('dao/PersonDao.php');
		$dao = new \dao\PersonDao();							// DAO(DataAccessObject)を生成する
		$dto = array('id' => 2);								// DTO(DataTransferObject)を生成する
		$dto = array_merge($dto, array('age' => 'hehe'));		// 不要項目を追加する
		$dao->loadByKey($transaction, $dto);					// 主キーを用いてDTOにデータを読み込む
		$expected = array('{"id":2,"age":"hehe","name":"bbb"}');
		$actual = array($dto);
		assertHelper($expected, $actual);

		// loadByKeyのassert
		$dao = DaoFactory::newInstance('person');				// DAO(DataAccessObject)を生成する
		$dto = array('id' => 2);								// DTO(DataTransferObject)を生成する
		$dto = array_merge($dto, array('age' => 'hehe'));		// 不要項目を追加する
		$dao->loadByKey($transaction, $dto);					// 主キーを用いてDTOにデータを読み込む
		$expected = array('{"id":2,"age":"hehe","name":"bbb"}');
		$actual = array($dto);
		assertHelper($expected, $actual);

		// readのassert
		$expected1 = '{"id":1,"name":"aaa"}';
		$expected2 = '{"id":2,"name":"bbb"}';
		$expected3 = '{"id":3,"name":"ccc"}';
		$expected9 = '{"id":9,"name":"aaa"}';
		$dto = array(); 										// DTO(DataTransferObject)を生成する
		$actual = $dao->read($transaction, $dto);				// DTOを用いて検索する
		$expected = array($expected9, $expected3, $expected2, $expected1);
		assertHelper($expected, $actual);

		// read(絞り込みあり)のassert
		$dto = array('id' => null, 'name' => 'aaa');			// DTO(DataTransferObject)を生成する
		$dto = array_merge($dto, array('age' => 'hehe'));		// 不要項目を追加する
		$actual = $dao->read($transaction, $dto);				// DTOを用いて検索する
		$expected = array($expected9, $expected1);
		assertHelper($expected, $actual);

		// saveのassert
	}

	if (true) {
		// loadByKeyのassert
		$dao = DaoFactory::newInstance('double_key');			// DAO(DataAccessObject)を生成する
		$dto = array('key1' => 'aaa', 'key2' => 1); 			// DTO(DataTransferObject)を生成する
		$dto = array_merge($dto, array('age' => 'hehe'));		// 不要項目を追加する
		$dao->loadByKey($transaction, $dto);					// 主キーを用いてDTOにデータを読み込む
		$expected = array('{"key1":"aaa","key2":1,"age":"hehe","val1":"@aaa@","val2":111}');
		$actual = array($dto);
		assertHelper($expected, $actual);

		// readのassert
		$expected1 = '{"key1":"aaa","key2":1,"val1":"@aaa@","val2":111}';
		$expected2 = '{"key1":"aaa","key2":2,"val1":"*aaa*","val2":222}';
		$expected3 = '{"key1":"bbb","key2":1,"val1":"@bbb@","val2":222}';
		$expected4 = '{"key1":"bbb","key2":2,"val1":"*bbb*","val2":333}';
		$dto = array(); 										// DTO(DataTransferObject)を生成する
		$actual = $dao->read($transaction, $dto);				// DTOを用いて検索する
		$expected = array($expected4, $expected3, $expected2, $expected1);
		assertHelper($expected, $actual);

		// read(絞り込みあり)のassert
		$dto = array('key1' => 'aaa', 'key2' => null);			// DTO(DataTransferObject)を生成する
		$dto = array_merge($dto, array('age' => 'hehe'));		// 不要項目を追加する
		$actual = $dao->read($transaction, $dto);				// DTOを用いて検索する
		$expected = array($expected2, $expected1);
		assertHelper($expected, $actual);
	}

	$transaction->commit(); 									// 正常終了したのでcommitする
	echo 'success';
} catch (\Exception $e) {
	echo $e;
	$transaction->rollback();									// 異常終了したのでrollbackする
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
