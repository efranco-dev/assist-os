<?php
session_start();
require('conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: editar.php?id=' . $id);
    exit();
}

$cliente_id   = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
$aparelho     = trim(filter_input(INPUT_POST, 'aparelho', FILTER_DEFAULT) ?? '');
$marca        = trim(filter_input(INPUT_POST, 'marca', FILTER_DEFAULT) ?? '');
$modelo       = trim(filter_input(INPUT_POST, 'modelo', FILTER_DEFAULT) ?? '');
$defeito      = trim(filter_input(INPUT_POST, 'defeito', FILTER_DEFAULT) ?? '');
$servico      = trim(filter_input(INPUT_POST, 'servico', FILTER_DEFAULT) ?? '');
$observacoes  = trim(filter_input(INPUT_POST, 'observacoes', FILTER_DEFAULT) ?? '');
$status       = trim(filter_input(INPUT_POST, 'status', FILTER_DEFAULT) ?? '');
$valor_servico = filter_input(INPUT_POST, 'valor_servico', FILTER_DEFAULT) ?? '';
$desconto     = filter_input(INPUT_POST, 'desconto', FILTER_DEFAULT) ?? '';
$total        = filter_input(INPUT_POST, 'valor_total', FILTER_DEFAULT) ?? '';

function parseCurrency($v) {
    $v = trim($v);
    if ($v === '') return null;
    $v = str_replace(['.', ','], ['', '.'], $v);
    return is_numeric($v) ? $v : null;
}

$valor_servico = parseCurrency($valor_servico);
$desconto      = parseCurrency($desconto);
$total         = parseCurrency($total);

try {
    $sql = "UPDATE ordens_servico SET
        cliente_id = :cliente_id,
        aparelho   = :aparelho,
        marca      = :marca,
        modelo     = :modelo,
        defeito    = :defeito,
        servico    = :servico,
        observacoes = :observacoes,
        status     = :status,
        valor_servico = :valor_servico,
        desconto   = :desconto,
        valor_total = :valor_total
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cliente_id'    => $cliente_id,
        ':aparelho'      => $aparelho,
        ':marca'         => $marca,
        ':modelo'        => $modelo,
        ':defeito'       => $defeito,
        ':servico'       => $servico,
        ':observacoes'   => $observacoes,
        ':status'        => $status,
        ':valor_servico' => $valor_servico,
        ':desconto'      => $desconto,
        ':valor_total'   => $total,
        ':id'            => $id,
    ]);
    $_SESSION['sucesso'] = true;
    header('Location: editar.php?id=' . $id);
    exit();
} catch (PDOException $e) {
    echo "Ops! algo deu errado: " . $e->getMessage();
    exit();
}
