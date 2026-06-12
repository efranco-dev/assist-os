<?php
session_start();
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header('Location: login.php');
    exit();
}
require('conexao.php');
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($busca) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nome LIKE :busca OR telefone LIKE :busca ORDER BY nome");
    $stmt->execute([':busca' => "%$busca%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome");
}
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Clientes - Assist-OS</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
  <?php require('header.php'); ?>
  <main class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-semibold mb-0">Clientes</h5>
      <a href="index.php" class="btn btn-sm text-white d-flex align-items-center gap-1" style="background:var(--brand-color)">
        <i class="bi bi-person-plus-fill"></i> Novo Cliente
      </a>
    </div>

    <form method="GET" class="mb-3">
      <div class="input-group input-group-sm">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou telefone..." value="<?= htmlspecialchars($busca) ?>">
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        <?php if ($busca): ?>
          <a href="?" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        <?php endif; ?>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Bairro</th>
            <th>Desde</th>
            <th style="width:200px">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($clientes)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox"></i> Nenhum cliente encontrado.</td></tr>
          <?php else: ?>
            <?php foreach ($clientes as $c): ?>
              <tr>
                <td class="fw-medium"><?= htmlspecialchars($c['nome']) ?></td>
                <td><?= htmlspecialchars($c['telefone']) ?></td>
                <td><?= htmlspecialchars($c['bairro']) ?></td>
                <td class="text-muted small"><?= date('d/m/Y', strtotime($c['data_cadastro'])) ?></td>
                <td>
                  <a href="visualizar.php?cliente_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver"><i class="bi bi-eye"></i></a>
                  <a href="cliente-editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                  <a href="os-nova.php?cliente_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-success" title="Nova OS"><i class="bi bi-clock-history"></i></a>
                  <button type="button" class="btn btn-sm btn-outline-danger delete-cliente" title="Excluir"
                    data-id="<?= $c['id'] ?>" data-nome="<?= htmlspecialchars($c['nome']) ?>">
                    <i class="bi bi-trash3"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="modalConfirmarExclusao" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Confirmar Exclusão</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="delete-message">Tem certeza que deseja excluir?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" id="delete-confirm-btn" class="btn btn-danger btn-sm">Excluir</a>
        </div>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/theme.js"></script>
  <script>
    document.querySelectorAll('.delete-cliente').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var nome = this.dataset.nome;
        document.getElementById('delete-message').innerHTML =
          'Excluir cliente <strong>' + nome + '</strong> e todas as suas OS?';
        document.getElementById('delete-confirm-btn').href =
          'deletar.php?id=' + id + '&tabela=clientes';
        new bootstrap.Modal(document.getElementById('modalConfirmarExclusao')).show();
      });
    });
  </script>
</body>
</html>
