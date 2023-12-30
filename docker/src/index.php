<?php
echo 'helloworld';

$mysqli = new mysqli('db', 'root', 'pass', 'mysql');
if($mysqli->connect_error) {
    echo '接続失敗'.PHP_EOL;
} else {
    echo '接続成功'.PHP_EOL;
}


//ホスト名、データベース名、文字コードの３つを定義する
$host = 'db';
$db = 'mysql';
$charset = 'utf8';
$dsn = "mysql:host=$host; dbname=$db; charset=$charset";

//ユーザー名、パスワード
$user = 'root';
$pass = 'pass';

 //オプション
 $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try{

    //上のデータを引数に入れて、PDOインスタンスを作成
    $pdo = new PDO($dsn, $user, $pass, $options);

}catch(PDOException $e){
    echo $e->getMessage();
}

