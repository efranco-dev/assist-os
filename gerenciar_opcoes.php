<?php
// === PROCESSAMENTO DAS AÇÕES (ADD/EDIT/DELETE) ===

if (isset($_POST['ap_add'])) {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome) {
        $stmt = $pdo->prepare("INSERT INTO aparelhos (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        $_SESSION['sel_ap'] = $nome;
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM aparelhos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalAparelho($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'aparelho', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ap_edit'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    if ($id && $nome) {
        $stmt = $pdo->prepare("UPDATE aparelhos SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM aparelhos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalAparelho($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'aparelho', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ap_delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM aparelhos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM aparelhos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalAparelho($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'aparelho', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ma_add'])) {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome) {
        $stmt = $pdo->prepare("INSERT INTO marcas (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        $_SESSION['sel_ma'] = $nome;
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM marcas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalMarca($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'marca', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ma_edit'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    if ($id && $nome) {
        $stmt = $pdo->prepare("UPDATE marcas SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM marcas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalMarca($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'marca', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ma_delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM marcas WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM marcas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalMarca($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'marca', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['st_add'])) {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome) {
        $stmt = $pdo->prepare("INSERT INTO status_opcoes (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        $_SESSION['sel_st'] = $nome;
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM status_opcoes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalStatus($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'status', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['st_edit'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    if ($id && $nome) {
        $stmt = $pdo->prepare("UPDATE status_opcoes SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM status_opcoes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalStatus($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'status', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['st_delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM status_opcoes WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM status_opcoes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalStatus($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'status', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ba_add'])) {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome) {
        $stmt = $pdo->prepare("INSERT INTO bairros (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        $_SESSION['sel_ba'] = $nome;
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM bairros ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalBairro($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'bairro', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ba_edit'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    if ($id && $nome) {
        $stmt = $pdo->prepare("UPDATE bairros SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM bairros ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalBairro($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'bairro', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ba_delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM bairros WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    if (!empty($_POST['ajax'])) {
        $items = $pdo->query("SELECT * FROM bairros ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        renderModalBairro($items);
        $modalHtml = ob_get_clean();
        echo json_encode(['success' => true, 'type' => 'bairro', 'items' => $items, 'modalHtml' => $modalHtml]);
        exit;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}

// === BUSCA DADOS ===

$aparelhos = $pdo->query("SELECT * FROM aparelhos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$marcas = $pdo->query("SELECT * FROM marcas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$status_opcoes = $pdo->query("SELECT * FROM status_opcoes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$bairros = $pdo->query("SELECT * FROM bairros ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

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
        <form method="POST" class="input-group mb-3" id="addAparelhoForm">
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

function renderModalStatus($status_opcoes) {
?>
<div class="modal fade" id="modalStatus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-clipboard-check-fill"></i> Gerenciar Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" class="input-group mb-3" id="addStatusForm">
          <input type="text" name="nome" class="form-control" placeholder="Novo status..." required>
          <button type="submit" name="st_add" class="btn btn-success"><i class="bi bi-plus-lg"></i> Adicionar</button>
        </form>
        <div class="table-responsive" style="max-height:300px;overflow-y:auto">
          <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Nome</th><th style="width:220px">Ações</th></tr></thead>
            <tbody>
              <?php foreach ($status_opcoes as $s): ?>
              <tr>
                <td class="align-middle"><?= htmlspecialchars($s['nome']) ?></td>
                <td class="text-nowrap">
                  <button type="button" class="btn btn-sm btn-outline-success select-item"
                    data-target="status" data-value="<?= htmlspecialchars($s['nome']) ?>"
                    data-bs-dismiss="modal">
                    <i class="bi bi-check-lg"></i> Selecionar
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-warning edit-item"
                    data-id="<?= $s['id'] ?>" data-nome="<?= htmlspecialchars($s['nome']) ?>"
                    data-table="status_opcoes" data-prefix="st">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                    data-id="<?= $s['id'] ?>" data-nome="<?= htmlspecialchars($s['nome']) ?>"
                    data-prefix="st">
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

function renderModalBairro($bairros) {
?>
<div class="modal fade" id="modalBairros" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-cursor"></i> Gerenciar Bairros</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" class="input-group mb-3" id="addBairroForm">
          <input type="text" name="nome" class="form-control" placeholder="Novo bairro..." required>
          <button type="submit" name="ba_add" class="btn btn-success"><i class="bi bi-plus-lg"></i> Adicionar</button>
        </form>
        <div class="table-responsive" style="max-height:300px;overflow-y:auto">
          <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Nome</th><th style="width:220px">Ações</th></tr></thead>
            <tbody>
              <?php foreach ($bairros as $b): ?>
              <tr>
                <td class="align-middle"><?= htmlspecialchars($b['nome']) ?></td>
                <td class="text-nowrap">
                  <button type="button" class="btn btn-sm btn-outline-success select-item"
                    data-target="bairro" data-value="<?= htmlspecialchars($b['nome']) ?>"
                    data-bs-dismiss="modal">
                    <i class="bi bi-check-lg"></i> Selecionar
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-warning edit-item"
                    data-id="<?= $b['id'] ?>" data-nome="<?= htmlspecialchars($b['nome']) ?>"
                    data-table="bairros" data-prefix="ba">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger delete-item"
                    data-id="<?= $b['id'] ?>" data-nome="<?= htmlspecialchars($b['nome']) ?>"
                    data-prefix="ba">
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
        <form method="POST" class="input-group mb-3" id="addMarcaForm">
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
