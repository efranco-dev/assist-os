<?php
session_start();
require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: clientes.php');
    exit();
}

$nome     = trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT) ?? '');
$endereco = trim(filter_input(INPUT_POST, 'endereco', FILTER_DEFAULT) ?? '');
$bairro   = trim(filter_input(INPUT_POST, 'bairro', FILTER_DEFAULT) ?? '');
$telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT) ?? '');

if (!$nome) {
    $_SESSION['sucesso_cliente'] = 'Nome é obrigatório.';
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("INSERT INTO clientes (nome, endereco, bairro, telefone) VALUES (:nome, :endereco, :bairro, :telefone)");
$stmt->execute([
    ':nome' => $nome,
    ':endereco' => $endereco,
    ':bairro' => $bairro,
    ':telefone' => $telefone,
]);

$cliente_id = $pdo->lastInsertId();
$_SESSION['sucesso_cliente'] = 'Cliente cadastrado com sucesso!';

// If came from OS creation flow, redirect back to OS page with the new client
$redirect = $_POST['redirect'] ?? 'clientes.php';
if ($redirect === 'os-nova.php') {
    header("Location: os-nova.php?cliente_id=$cliente_id");
} else {
    header('Location: index.php');
}
exit();
