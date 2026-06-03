<?php

date_default_timezone_set('America/Sao_Paulo');

$pdo = new PDO('mysql:host=mysql;dbname=ordem;charset=utf8', 'app_user', 'app_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
