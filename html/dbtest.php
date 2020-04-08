<?php

$user = 'root';
$pass = 'root';
$pdo = new PDO('mysql:host=mysql;dbname=docker_db2', $user, $pass);

$sql = 'select * from testtable';
$stmt = $pdo->prepare($sql);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<pre>';
print_r($rows);
