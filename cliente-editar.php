<?php
session_start();
require('conexao.php');
require('gerenciar_opcoes.php');

$bairros = $pdo->query("SELECT * FROM bairros ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: clientes.php'); exit(); }

$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
$stmt->execute([':id' => $id]);
$cliente = $stmt->fetch();
if (!$cliente) { header('Location: clientes.php'); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT) ?? '');
    $endereco = trim(filter_input(INPUT_POST, 'endereco', FILTER_DEFAULT) ?? '');
    $bairro   = trim(filter_input(INPUT_POST, 'bairro', FILTER_DEFAULT) ?? '');
    $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT) ?? '');

    if ($nome) {
        $upd = $pdo->prepare("UPDATE clientes SET nome=:nome, endereco=:endereco, bairro=:bairro, telefone=:telefone WHERE id=:id");
        $upd->execute([':nome'=>$nome, ':endereco'=>$endereco, ':bairro'=>$bairro, ':telefone'=>$telefone, ':id'=>$id]);
        $_SESSION['sucesso_cliente'] = 'Cliente atualizado.';
        header('Location: clientes.php');
        exit();
    }
}
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Editar Cliente - Assist-OS</title>
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
    <div class="card shadow-sm border" style="max-width:600px">
      <div class="card-header bg-transparent fw-semibold"><i class="bi bi-pencil"></i> Editar Cliente</div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-2">
            <label class="form-label small">Nome</label>
            <input type="text" name="nome" class="form-control form-control-sm" required value="<?= htmlspecialchars($cliente['nome']) ?>">
          </div>
          <div class="mb-2">
            <label class="form-label small">Endereço</label>
            <input type="text" name="endereco" class="form-control form-control-sm" value="<?= htmlspecialchars($cliente['endereco']) ?>">
          </div>
          <div class="row g-2 mb-2">
            <div class="col">
              <label class="form-label small">Bairro</label>
              <div class="input-group input-group-sm">
                <select name="bairro" id="bairro" class="form-select">
                  <option value="">Selecione</option>
                  <?php foreach ($bairros as $b): ?>
                    <option value="<?= htmlspecialchars($b['nome']) ?>" <?= $cliente['bairro'] === $b['nome'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($b['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalBairros" title="Gerenciar bairros"><i class="bi bi-gear"></i></button>
              </div>
            </div>
            <div class="col">
              <label class="form-label small">Telefone</label>
              <input type="text" name="telefone" class="form-control form-control-sm" value="<?= htmlspecialchars($cliente['telefone']) ?>">
            </div>
          </div>
          <hr class="my-3">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-sm text-white" style="background:var(--brand-color)"><i class="bi bi-check-lg"></i> Salvar</button>
            <a href="clientes.php" class="btn btn-sm btn-outline-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php renderModalBairro($bairros); ?>

  <div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form method="POST">
          <div class="modal-header"><h6 class="modal-title">Editar</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" id="edit-nome" class="form-control" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary btn-sm" id="edit-submit">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalConfirmarExclusao" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h6 class="modal-title">Confirmar Exclusão</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><p id="delete-message">Tem certeza que deseja excluir?</p></div>
        <div class="modal-footer">
          <form method="POST">
            <input type="hidden" name="id" id="delete-id">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger btn-sm" id="delete-submit">Excluir</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/theme.js"></script>
  <script>
    document.querySelectorAll('.select-item').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var el = document.getElementById(this.dataset.target);
        if (el) el.value = this.dataset.value;
      });
    });
    document.querySelectorAll('.edit-item').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('edit-id').value = this.dataset.id;
        document.getElementById('edit-nome').value = this.dataset.nome;
        document.getElementById('edit-submit').name = this.dataset.prefix + '_edit';
        new bootstrap.Modal(document.getElementById('modalEditar')).show();
      });
    });
    document.querySelectorAll('.delete-item').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('delete-id').value = this.dataset.id;
        document.getElementById('delete-submit').name = this.dataset.prefix + '_delete';
        document.getElementById('delete-message').innerHTML = 'Tem certeza que deseja excluir <strong>' + this.dataset.nome + '</strong>?';
        new bootstrap.Modal(document.getElementById('modalConfirmarExclusao')).show();
      });
    });
  </script>
</body>
</html>
