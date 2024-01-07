<?php
namespace dao;

interface Dao {

	/** 主キーを用いて検索しDTOに値を設定する */
	public function loadByKey($transaction, &$dto);

	/** DTOを検索条件に値を読み込む */
	public function read($transaction, $dto);

	/** DTOを保存する */
	public function save($transaction, $dto);
}