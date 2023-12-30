<?php
namespace dao;

interface Transaction {
    public function commit();
    public function rollback();
    public function fetch($value, $placeHolders = null);
    public function save($value, $placeHolders = null);
}