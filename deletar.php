<?php
session_start();
require('conexao.php');

$id     = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$tabela = filter_input(INPUT_GET, 'tabela', FILTER_DEFAULT) ?? 'ordens_servico';

if (!$id) {
    header('Location: /assist-os/index.php');
    exit();
}

try {
    if ($tabela === 'clientes') {
        $pdo->prepare("DELETE FROM ordens_servico WHERE cliente_id = :id")->execute([':id' => $id]);
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['sucesso'] = 'Cliente excluído com sucesso.';
        header('Location: /assist-os/clientes.php');
    } else {
        $stmt = $pdo->prepare("DELETE FROM ordens_servico WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['sucesso'] = 'Ordem de serviço excluída.';
        header('Location: /assist-os/ordem-servico.php');
    }
    exit();
} catch (PDOException $e) {
    echo 'Ops! Aconteceu um erro: ' . $e->getMessage();
    exit();
}
