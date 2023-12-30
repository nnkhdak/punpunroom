<?php
namespace dao\mysql;

$root = '..';
require_once('dao/Transaction.php');

use PDO;
use PDOException;

class Transaction4MySQL implements \dao\Transaction {

    private $impl = null;

    public function __construct($auto = false) {
        $host = 'db';
        $db = 'test';
        $charset = 'utf8';
        $dsn = "mysql:host=$host; dbname=$db; charset=$charset";

        $user = 'root';
        $pass = 'pass';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->impl = new PDO($dsn, $user, $pass, $options);
            if (!$auto) {
                $this->impl->beginTransaction();
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function __destruct() {
        $this->close();
    }

    public function close() {
        if (is_null($this->impl)) {
            return;
        }        

        try {
            $this->rollback();
        } finally {
        }

        try {
            $this->impl = null;
        } finally {
        }
    }

    public function commit() {
        if (!is_null($this->impl) && $this->impl->inTransaction()) {
            $this->impl->commit();
        }
    }

    protected function createPDOStatement($sql, $placeHolders) {
		$statement = $this->impl->prepare($sql);
		if (!empty($placeHolders)) {
			foreach($placeHolders as $key => $val) {
				$pattern = "/\\s+:$key(|\\s+)/";;
				if (!preg_match($pattern, $sql)) {
					continue;
				}
				$statement->bindValue($key, $val);
			}
		}
		return $statement;
	}

    public function fetch($value, $placeHolders = null) {
        $rows = null;
        try {
			$statement = $this->createPDOStatement($value, $placeHolders);
			$statement->execute();
			$rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
echo var_dump($rows);
echo PHP_EOL.'<br/>';
echo json_encode($rows);
echo PHP_EOL.'<br/>';
                        
			return $rows;

		} catch (\Exception $e) {
        }
        return $rows;
    }

    public function rollback() {
        if (!is_null($this->impl) && $this->impl->inTransaction()) {
            $this->impl->rollback();
        }
    }

    public function save($value, $placeHolders = null) {
    }
}