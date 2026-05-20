<?php
require('conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);
$nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
$aparelho = filter_input(INPUT_POST, 'aparelho', FILTER_DEFAULT);
$marca = filter_input(INPUT_POST, 'marca', FILTER_DEFAULT);
$modelo = filter_input(INPUT_POST, 'modelo', FILTER_DEFAULT);
$defeito = filter_input(INPUT_POST, 'defeito', FILTER_DEFAULT);
$observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_DEFAULT);

try {
    $sql = "UPDATE `cadastro` SET `nome`='$nome',`aparelho`='$aparelho',`marca`='$marca',`modelo`='$modelo',`defeito`='$defeito',`observacoes`='$observacoes' WHERE id = $id";
    $statement = $pdo->query($sql);
    header('location:/crud-php');
} catch (PDOException $e) {
    echo "Ops! algo deu errado: " . $e->getMessage();
    exit();
}