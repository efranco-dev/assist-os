<?php
require('conexao.php');

$senha = password_hash('admin123', PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, usuario, senha, nivel) VALUES (:nome, :usuario, :senha, :nivel)");
$stmt->execute([
    ':nome'    => 'Administrador',
    ':usuario' => 'admin',
    ':senha'   => $senha,
    ':nivel'   => 'admin',
]);

echo "Usuário admin criado com sucesso! Hash: " . $senha;
