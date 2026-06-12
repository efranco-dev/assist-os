<?php
session_start();
if (isset($_SESSION['logado']) && $_SESSION['logado']) {
    header('Location: dashboard.php');
    exit();
}

require('conexao.php');
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    if ($usuario && $senha) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['logado']  = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            header('Location: dashboard.php');
            exit();
        }
        $erro = 'Usuário ou senha inválidos.';
    } else {
        $erro = 'Preencha todos os campos.';
    }
}
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Login - Assist-OS</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f2f5;
    }
    .login-card {
      width: 100%;
      max-width: 400px;
      border: 1px solid #dee2e6;
      border-radius: 12px;
    }
    [data-bs-theme="dark"] body {
      background: #1a1d21;
    }
  </style>
</head>
<body>
  <div class="login-card card shadow-sm p-4">
    <div class="text-center mb-4">
      <div class="d-flex align-items-center justify-content-center rounded-3 text-white mx-auto mb-2" style="width:52px;height:52px;background:var(--brand-color,#1a3a5c)">
        <i class="bi bi-tools fs-4"></i>
      </div>
      <h5 class="mb-0 fw-semibold">Assist-OS</h5>
      <small class="text-muted">Faça login para continuar</small>
    </div>

    <?php if ($erro): ?>
      <div class="alert alert-danger py-2 small"><?= $erro ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label small">Usuário</label>
        <input type="text" name="usuario" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label small">Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>
      <button type="submit" class="btn w-100 text-white" style="background:var(--brand-color,#1a3a5c)">Entrar</button>
    </form>
    <small class="text-muted text-center mt-3">admin / admin123</small>
  </div>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
