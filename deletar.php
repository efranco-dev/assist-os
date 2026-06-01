<?php
session_start();
require('conexao.php');

try {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        header('location:/assist-os/lista-clientes.php');
        exit();
    }
    $sql = "DELETE FROM `cadastro` WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id]);
    $_SESSION['sucesso'] = true;
    header('location:/assist-os/lista-clientes.php');
    exit();

} catch (PDOException $e) {
    echo 'Ops! Aconteceu um erro :' . $e->getMessage();
    exit();
}

