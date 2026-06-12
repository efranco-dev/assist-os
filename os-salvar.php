<?php
session_start();
require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ordem-servico.php');
    exit();
}

$cliente_id  = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
$aparelho    = trim(filter_input(INPUT_POST, 'aparelho', FILTER_DEFAULT) ?? '');
$marca       = trim(filter_input(INPUT_POST, 'marca', FILTER_DEFAULT) ?? '');
$modelo      = trim(filter_input(INPUT_POST, 'modelo', FILTER_DEFAULT) ?? '');
$defeito     = trim(filter_input(INPUT_POST, 'defeito', FILTER_DEFAULT) ?? '');
$servico     = trim(filter_input(INPUT_POST, 'servico', FILTER_DEFAULT) ?? '');
$observacoes = trim(filter_input(INPUT_POST, 'observacoes', FILTER_DEFAULT) ?? '');
$status      = trim(filter_input(INPUT_POST, 'status', FILTER_DEFAULT) ?? '');
$valor_servico = trim(filter_input(INPUT_POST, 'valor_servico', FILTER_DEFAULT) ?? '');
$desconto    = trim(filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT) ?? '');
$total       = trim(filter_input(INPUT_POST, 'valor_total', FILTER_DEFAULT) ?? '');

function parseCurrency($v) {
    $v = trim($v);
    if ($v === '') return null;
    $v = str_replace(['.', ','], ['', '.'], $v);
    return is_numeric($v) ? $v : null;
}

$valor_servico = parseCurrency($valor_servico);
$desconto      = parseCurrency($desconto);
$total         = parseCurrency($total);

if (!$cliente_id) {
    $_SESSION['erro_os'] = 'Selecione um cliente.';
    header('Location: os-nova.php');
    exit();
}

$stmt = $pdo->prepare("INSERT INTO ordens_servico (cliente_id, aparelho, marca, modelo, defeito, servico, observacoes, status, valor_servico, desconto, valor_total)
    VALUES (:cliente_id, :aparelho, :marca, :modelo, :defeito, :servico, :observacoes, :status, :valor_servico, :desconto, :valor_total)");
$stmt->execute([
    ':cliente_id'   => $cliente_id,
    ':aparelho'     => $aparelho,
    ':marca'        => $marca,
    ':modelo'       => $modelo,
    ':defeito'      => $defeito,
    ':servico'      => $servico,
    ':observacoes'  => $observacoes,
    ':status'       => $status,
    ':valor_servico'=> $valor_servico,
    ':desconto'     => $desconto,
    ':valor_total'  => $total,
]);

$_SESSION['sucesso_os'] = 'Ordem de serviço criada com sucesso!';
header('Location: ordem-servico.php');
exit();
