<?php
namespace dao;

require_once('dao/Dao.php');
require_once('dao/Transaction.php');

class DaoFactory {

	private static function calcMapping() {
		$path = sprintf('./%s.json', __CLASS__);
		$path = str_replace('\\', '/', $path);
		if (!file_exists($path)) {
			return array();
		}

		$path = realpath($path);
		$map = file_get_contents($path);
		$map = json_decode($map, true);
		return $map;
	}

	public static function newInstance($keyword, ...$params) {
		$map = self::calcMapping();
		if (!empty($map)) {
			if (array_key_exists($keyword, $map)) {
				$fqcn = $map[$keyword];
				self::require_once($fqcn);
				$impl = new $fqcn($params);
				return $impl;
			}
		}

		$fqcn = $map["dao\Dao"];
		self::require_once($fqcn);
		$impl = new $fqcn($keyword);
		return $impl;
	}

	public static function newTransaction($auto = false) {
		return self::newInstance("dao\Transaction", $auto);
	}

	private static function require_once($fqcn) {
		$path = sprintf('%s.php', $fqcn);
		$path = str_replace('\\', '/', $path);
		require_once($path);
	}
}