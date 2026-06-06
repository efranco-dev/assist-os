<?php
// === PROCESSAMENTO DAS AÇÕES (ADD/EDIT/DELETE) ===

if (isset($_POST['ap_add'])) {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome) {
        $stmt = $pdo->prepare("INSERT INTO aparelhos (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        $_SESSION['sel_ap'] = $nome;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_POST['ap_edit'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    if ($id && $nome) {
        $stmt = $pdo->prepare("UPDATE aparelhos SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_POST['ap_delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM aparelhos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['ma_add'])) {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome) {
        $stmt = $pdo->prepare("INSERT INTO marcas (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        $_SESSION['sel_ma'] = $nome;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_POST['ma_edit'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    if ($id && $nome) {
        $stmt = $pdo->prepare("UPDATE marcas SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_POST['ma_delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM marcas WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// === BUSCA DADOS ===

$aparelhos = $pdo->query("SELECT * FROM aparelhos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$marcas = $pdo->query("SELECT * FROM marcas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// === FUNÇÕES DE RENDERIZAÇÃO DOS MODAIS ===

function renderModalAparelho($aparelhos) {
?>
<div class="modal fade" id="modalAparelhos" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-phone-fill"></i> Gerenciar Aparelhos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" class="input-group mb-3">
          <input type="text" name="nome" class="form-control" placeholder="Novo aparelho..." required>
          <button type="submit" name="ap_add" class="btn btn-success"><i class="bi bi-plus-lg"></i> Adicionar</button>
        </form>
        <div class="table-responsive" style="max-height:300px;overflow-y:auto">
          <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Nome</th><th style="width:220px">Ações</th></tr></thead>
            <tbody>
              <?php foreach ($aparelhos as $a): ?>
              <tr>
                <td class="align-middle"><?= htmlspecialchars($a['nome']) ?></td>
                <td class="text-nowrap">
                  <button type="button" class="btn btn-sm btn-outline-success select-item"
                    data-target="aparelho" data-value="<?= htmlspecialchars($a['nome']) ?>"
                    data-bs-dismiss="modal">
                    <i class="bi bi-check-lg"></i> Selecionar
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-warning edit-item"
                    data-id="<?= $a['id'] ?>" data-nome="<?= htmlspecialchars($a['nome']) ?>"
                    data-table="aparelhos" data-prefix="ap">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                    data-id="<?= $a['id'] ?>" data-nome="<?= htmlspecialchars($a['nome']) ?>"
                    data-prefix="ap">
                    <i class="bi bi-trash3"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
}

function renderModalMarca($marcas) {
?>
<div class="modal fade" id="modalMarcas" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-tag-fill"></i> Gerenciar Marcas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" class="input-group mb-3">
          <input type="text" name="nome" class="form-control" placeholder="Nova marca..." required>
          <button type="submit" name="ma_add" class="btn btn-success"><i class="bi bi-plus-lg"></i> Adicionar</button>
        </form>
        <div class="table-responsive" style="max-height:300px;overflow-y:auto">
          <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Nome</th><th style="width:220px">Ações</th></tr></thead>
            <tbody>
              <?php foreach ($marcas as $m): ?>
              <tr>
                <td class="align-middle"><?= htmlspecialchars($m['nome']) ?></td>
                <td class="text-nowrap">
                  <button type="button" class="btn btn-sm btn-outline-success select-item"
                    data-target="marca" data-value="<?= htmlspecialchars($m['nome']) ?>"
                    data-bs-dismiss="modal">
                    <i class="bi bi-check-lg"></i> Selecionar
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-warning edit-item"
                    data-id="<?= $m['id'] ?>" data-nome="<?= htmlspecialchars($m['nome']) ?>"
                    data-table="marcas" data-prefix="ma">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                    data-id="<?= $m['id'] ?>" data-nome="<?= htmlspecialchars($m['nome']) ?>"
                    data-prefix="ma">
                    <i class="bi bi-trash3"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
}
