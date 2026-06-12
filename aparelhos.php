<?php
session_start();
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header('Location: login.php');
    exit();
}
require('conexao.php');

// Processa ações: adicionar, editar, excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'adicionar') {
        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT));
        if ($nome) {
            $sql = "INSERT INTO aparelhos (nome) VALUES (:nome)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome]);
            $_SESSION['msg'] = 'Aparelho adicionado com sucesso!';
        }
    } elseif ($acao === 'editar') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT));
        if ($id && $nome) {
            $sql = "UPDATE aparelhos SET nome = :nome WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':id' => $id]);
            $_SESSION['msg'] = 'Aparelho atualizado com sucesso!';
        }
    } elseif ($acao === 'excluir') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $sql = "DELETE FROM aparelhos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $_SESSION['msg'] = 'Aparelho excluído com sucesso!';
        }
    }

    header('Location: aparelhos.php');
    exit();
}

$aparelhos = $pdo->query("SELECT * FROM aparelhos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Gerenciar Aparelhos</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
  <?php require('header.php'); ?>
  <main class="container">
    <div class="card my-4 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0"><i class="bi bi-phone-fill"></i> Gerenciar Aparelhos</h2>
        <a href="index.php" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Voltar</a>
      </div>
      <div class="card-body">
        <?php if (isset($_SESSION['msg'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <form method="POST" class="row g-2 mb-4">
          <input type="hidden" name="acao" value="adicionar">
          <div class="col-md-8">
            <input type="text" name="nome" class="form-control" placeholder="Novo aparelho..." required>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-lg"></i> Adicionar</button>
          </div>
        </form>

        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th style="width:200px">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($aparelhos)): ?>
              <tr>
                <td colspan="3" class="text-center text-muted py-3">
                  <i class="bi bi-inbox"></i> Nenhum aparelho cadastrado.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($aparelhos as $a): ?>
                <tr>
                  <td><?= $a['id'] ?></td>
                  <td><?= htmlspecialchars($a['nome']) ?></td>
                  <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal"
                      data-id="<?= $a['id'] ?>" data-nome="<?= htmlspecialchars($a['nome']) ?>">
                      <i class="bi bi-pencil-square"></i> Editar
                    </button>
                    <form method="POST" style="display:inline"
                      onsubmit="return confirm('Excluir aparelho &quot;<?= htmlspecialchars($a['nome']) ?>&quot;?')">
                      <input type="hidden" name="acao" value="excluir">
                      <input type="hidden" name="id" value="<?= $a['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash3-fill"></i> Excluir
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <div class="modal fade" id="editarModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title">Editar Aparelho</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id" id="editar-id">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" id="editar-nome" class="form-control" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <footer></footer>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/theme.js"></script>
  <script>
    document.getElementById('editarModal')?.addEventListener('show.bs.modal', function (e) {
      const btn = e.relatedTarget;
      document.getElementById('editar-id').value = btn.dataset.id;
      document.getElementById('editar-nome').value = btn.dataset.nome;
    });
  </script>
</body>
</html>
