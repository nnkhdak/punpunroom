<?php
namespace dao;

interface Transaction {

	public function close();

	public function commit();

	public function exec($value, $placeHolders = null);

	public function fetch($value, $placeHolders = null);

	public function rollback();
}