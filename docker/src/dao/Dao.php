<?php
namespace dao;

interface Dao {

	/** DTOに該当する情報を削除する */
	public function delete($transaction, $dto);

	/** 主キーを用いて検索しDTOに値を設定する */
	public function loadByKey($transaction, &$dto);

	/** DTOを検索条件に値を読み込む */
	public function read($transaction, $dto);

	/** DTOを保存する */
	public function save($transaction, $dto);
}