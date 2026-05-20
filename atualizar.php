<?php
require('conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);
$nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
$endereco = filter_input(INPUT_POST, 'endereco', FILTER_DEFAULT);
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT);
$aparelho = filter_input(INPUT_POST, 'aparelho', FILTER_DEFAULT);
$marca = filter_input(INPUT_POST, 'marca', FILTER_DEFAULT);
$modelo = filter_input(INPUT_POST, 'modelo', FILTER_DEFAULT);
$defeito = filter_input(INPUT_POST, 'defeito', FILTER_DEFAULT);
$servico = filter_input(INPUT_POST, 'servico', FILTER_DEFAULT);
$observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_DEFAULT);

try {
    $sql = "UPDATE `cadastro` SET `nome`='$nome',`endereco`='$endereco',`telefone`='$telefone',`aparelho`='$aparelho',
    `marca`='$marca',`modelo`='$modelo',`defeito`='$defeito',`servico`='$servico',`observacoes`='$observacoes' WHERE id = $id";
    $statement = $pdo->query($sql);
    header('location:/crud-php');
} catch (PDOException $e) {
    echo "Ops! algo deu errado: " . $e->getMessage();
    exit();
}