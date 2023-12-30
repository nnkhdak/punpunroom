<?php
namespace dao;

class PersonDao implements Dao {

    public function loadByKey($transaction, &$dto) {
        echo '<br/>' . PHP_EOL;
        echo get_class($transaction);
        echo '<br/>' . PHP_EOL;
        echo json_encode($dto);
        echo '<br/>' . PHP_EOL;
        echo '<br/>' . PHP_EOL;
        echo '<br/>' . PHP_EOL;
    }
}