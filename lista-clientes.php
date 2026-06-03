<?php
session_start();
require('conexao.php');

// Captura o termo de busca
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($busca !== '') {
  $sql = "SELECT * FROM `cadastro` WHERE nome LIKE :busca OR telefone LIKE :busca";
  $statement = $pdo->prepare($sql);
  $statement->execute([':busca' => "%$busca%"]);
} else {
  $sql = "SELECT * FROM `cadastro`";
  $statement = $pdo->query($sql);
}

$result = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Lista de Clientes</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/all.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
  <header>
    <!-- Barra da marca -->
    <div class="container d-flex align-items-center gap-3 py-3">
      <div class="d-flex align-items-center justify-content-center rounded-3 text-white"
        style="width:48px; height:48px; background:#1a3a5c; flex-shrink:0;">
        <i class="bi- bi-tools fs-4"></i>
      </div>
      <div>
        <h1 class="h5 mb-0 fw-semibold">Assist-OS</h1>
        <small class="text-muted fs-6">Assistência Técnica — Conserto de Microondas e TV em Geral - Rua 10 chácara 61
          lote 9 loja 4 - Vicente Pires - DF</small>
      </div>
      <div class="ms-auto d-flex gap-2">
        <a href="lista-clientes.php" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
          <i class="bi bi-people-fill"></i> Clientes
        </a>
        <a href="index.php" class="btn btn-sm text-white d-flex align-items-center gap-1" style="background:#1a3a5c;">
          <i class="bi bi-person-fill-add"></i> Novo Cadastro
        </a>
      </div>
    </div>

    <!-- Navegação -->
    <div class="border-top bg-light">
      <div class="container mb-3">
        <nav class="nav">
          <a href="index.php" class="nav-link active d-flex align-items-center gap-1 fw-medium"
            style="border-bottom: 2px solid #1a3a5c; color:#1a3a5c;">
            <i class="bi bi-clipboard2-fill"></i> Cadastro de Clientes e Serviço
          </a>
          <a href="ordem-servico.php" class="nav-link text-muted d-flex align-items-center gap-1 nav-link:hover">
            <i class="bi bi-clock-history"></i> Ordens de Serviço
            <span class="badge rounded-pill text-white ms-1" style="background:#1a3a5c; font-size:10px;">12</span>
          </a>
          <a href="relatorios.php" class="nav-link text-muted d-flex align-items-center gap-1 nav-link:hover">
            <i class="bi bi-bar-chart-fill"></i> Relatórios
          </a>
        </nav>
      </div>
    </div>
  </header>
  <main class="container">
    <div class="card my-4 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Lista de Clientes Cadastrados</h2>
        <a class="btn btn-sm btn-danger" href="8080"><i class="bi bi-box-arrow-left"></i> Voltar</a>
      </div>
      <div class="card-body">

        <!-- Campo de busca -->
        <form method="GET" action="" class="mb-3">
          <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou telefone..."
              value="<?= htmlspecialchars($busca) ?>" />
            <button class="btn btn-primary" type="submit">
              <i class="bi bi-search"></i> Buscar
            </button>
            <?php if ($busca !== ''): ?>
              <a href="?" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i> Limpar
              </a>
            <?php endif; ?>
          </div>
        </form>

        <!-- Feedback de resultados -->
        <?php if ($busca !== ''): ?>
          <p class="text-muted small">
            <?= count($result) ?> resultado(s) encontrado(s) para
            "<strong><?= htmlspecialchars($busca) ?></strong>"
          </p>
        <?php endif; ?>

        <?php if (!empty($_SESSION['sucesso'])): ?>
          <div id="mensagemSucesso" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            Cliente excluído com sucesso.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
          </div>
          <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>

        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Telefone</th>
              <th>Data de Entrada</th>
              <th>Opções</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($result)): ?>
              <tr>
                <td colspan="4" class="text-center text-muted py-3">
                  <i class="bi bi-inbox"></i> Nenhum cliente encontrado.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($result as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nome']) ?></td>
                  <td><?= htmlspecialchars($row['telefone']) ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($row['data_entrada'])) ?></td>
                  <td class="d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-primary" href="visualizar.php?id=<?= $row['id'] ?>">
                      <i class="bi bi-eye-fill"></i> Ver
                    </a>
                    <a class="btn btn-sm btn-warning" href="editar.php?id=<?= $row['id'] ?>">
                      <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a class="btn btn-sm btn-danger delete-link" href="deletar.php?id=<?= $row['id'] ?>">
                      <i class="bi bi-trash3-fill"></i> Excluir
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar exclusão</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          Tem certeza que deseja excluir cliente?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button id="confirmDeleteButton" type="button" class="btn btn-danger">OK</button>
        </div>
      </div>
    </div>
  </div>

  <footer></footer>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/total-calculation.js"></script>
  <script src="js/mask-phone.js"></script>
  <script src="js/mensagem-sucesso.js"></script>
  <script src="js/delete-link.js"></script>
</body>

</html>