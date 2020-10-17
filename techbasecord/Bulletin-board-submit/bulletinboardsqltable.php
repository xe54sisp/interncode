<?php
 $dsn ='mysql:dbname=**********;host=localhost'; //データベース名
 $user ='***********';      //ユーザー名
 $password = '***********';     //パスワード     
 $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 $sql = "CREATE TABLE IF NOT EXISTS phptest"
 ."("
 ."id INT AUTO_INCREMENT PRIMARY KEY,"
 ."name char(32),"
 ."text TEXT,"
 ."date char(32),"
 ."pass char(32)"
 .");";
 $stmt = $pdo->query($sql);
?>