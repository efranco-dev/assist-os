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
$valor_servico = filter_input(INPUT_POST, 'valor_servico', FILTER_DEFAULT);
$desconto = filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT);
$total = filter_input(INPUT_POST, 'valor_total', FILTER_DEFAULT);

function parseCurrency($value) {
    $value = trim($value);
    if ($value === '') {
        return null;
    }
    $value = str_replace(['.', ','], ['', '.'], $value);
    return is_numeric($value) ? $value : null;
}

$valor_servico = parseCurrency($valor_servico);
$desconto = parseCurrency($desconto);
$total = parseCurrency($total);

try {
    $data_entrada = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `cadastro`(nome, aparelho, marca, modelo, defeito, servico, observacoes, endereco, telefone, data_entrada, valor_servico, desconto, valor_total)
     VALUES(:nome, :aparelho, :marca, :modelo, :defeito, :servico, :observacoes, :endereco, :telefone, :data_entrada, :valor_servico, :desconto, :valor_total)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        ':nome' => $nome,
        ':aparelho' => $aparelho,
        ':marca' => $marca,
        ':modelo' => $modelo,
        ':defeito' => $defeito,
        ':servico' => $servico,
        ':observacoes' => $observacoes,
        ':endereco' => $endereco,
        ':telefone' => $telefone,
        ':data_entrada' => $data_entrada,
        ':valor_servico' => $valor_servico,
        ':desconto' => $desconto,
        ':valor_total' => $total,
    ]);
    header('location:/crud-php');
} catch (PDOException $e) {
    echo "Ops! algo deu errado: " . $e->getMessage();
    exit();
}

