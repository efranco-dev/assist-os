<?php

date_default_timezone_set('America/Sao_Paulo');

$pdo = new PDO('mysql:host=127.0.0.1;dbname=ordem;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
