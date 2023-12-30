<?php
namespace dao;

class Dao4MySQL implements Dao {

    public function loadByKey($transaction, $dto) {
        echo '<br/>' . PHP_EOL;
        echo get_class($transaction);
        echo '<br/>' . PHP_EOL;
        echo get_class($this);
        echo '<br/>' . PHP_EOL;
        echo json_encode($dto);
        echo '<br/>' . PHP_EOL;
        echo '<br/>' . PHP_EOL;
        echo '<br/>' . PHP_EOL;
        $sql = 'SHOW tables';
        $rows = $transaction->fetch($sql);
        echo json_encode($rows);
        echo '<br/>' . PHP_EOL;
    }
}