<?php

require('conexao.php');

$nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
$aparelho = filter_input(INPUT_POST, 'aparelho', FILTER_DEFAULT);
$marca = filter_input(INPUT_POST, 'marca', FILTER_DEFAULT);
$modelo = filter_input(INPUT_POST, 'modelo', FILTER_DEFAULT);
$defeito = filter_input(INPUT_POST, 'defeito', FILTER_DEFAULT);
$servico = filter_input(INPUT_POST, 'servico', FILTER_DEFAULT);
$observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_DEFAULT);
$endereco = filter_input(INPUT_POST, 'endereco', FILTER_DEFAULT);
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT);


try {
    $data_entrada = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `cadastro`(nome, aparelho, marca, modelo, defeito, servico, observacoes, endereco, telefone, data_entrada) VALUES('$nome', '$aparelho', '$marca', '$modelo', '$defeito', '$servico', '$observacoes', '$endereco', '$telefone', '$data_entrada')";
    $statement = $pdo->query($sql);
    header('location:/crud-php');
}catch(PDOException $e) {
    echo "Ops! algo deu errado: ". $e->getMessage();
    exit();
}
 
