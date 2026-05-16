<?php

require('conexao.php');

$nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
$sobrenome = filter_input(INPUT_POST, 'sobrenome', FILTER_DEFAULT);
$datanasc = filter_input(INPUT_POST, 'datanasc', FILTER_DEFAULT);


try {
    $sql = "INSERT INTO `cadastro`(nome, sobrenome, datanasc) VALUES('$nome', '$sobrenome', '$datanasc')";
    $statement = $pdo->query($sql);
}catch(PDOException $e) {
    echo "Ops! algo deu errado: ". $e->getMessage();
    exit;
}