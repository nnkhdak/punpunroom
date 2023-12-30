<?php
namespace dao;

interface Dao {

    /** 主キーを用いて検索しDTOに値を設定する */
    public function loadByKey($transaction, &$dto);
}