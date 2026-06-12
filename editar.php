<?php
session_start();
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header('Location: login.php');
    exit();
}
require('conexao.php');
require('gerenciar_opcoes.php');

$sel_ap = $_SESSION['sel_ap'] ?? '';
$sel_ma = $_SESSION['sel_ma'] ?? '';
$sel_st = $_SESSION['sel_st'] ?? '';
unset($_SESSION['sel_ap'], $_SESSION['sel_ma'], $_SESSION['sel_st']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: ordem-servico.php'); exit(); }

$stmt = $pdo->prepare("
    SELECT os.*, c.nome as cliente_nome
    FROM ordens_servico os
    JOIN clientes c ON c.id = os.cliente_id
    WHERE os.id = :id
");
$stmt->execute([':id' => $id]);
$os = $stmt->fetch();
if (!$os) { header('Location: ordem-servico.php'); exit(); }

$clientes = $pdo->query("SELECT id, nome FROM clientes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Editar OS #<?= $os['id'] ?> - Assist-OS</title>
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
      <h5 class="fw-semibold mb-0">Editar OS #<?= $os['id'] ?></h5>
      <a href="ordem-servico.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>

    <?php if (isset($_SESSION['sucesso']) && $_SESSION['sucesso']): ?>
      <div class="alert alert-success alert-dismissible fade show py-2 small">
        <i class="bi bi-check-circle-fill"></i> OS atualizada com sucesso!
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <form action="atualizar.php?id=<?= $os['id'] ?>" method="post">
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label small">Cliente</label>
          <select name="cliente_id" class="form-select form-select-sm" required>
            <?php foreach ($clientes as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $c['id'] == $os['cliente_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small">Aparelho</label>
          <div class="input-group input-group-sm">
            <select name="aparelho" id="aparelho" class="form-select">
              <option value="">Selecione</option>
              <?php foreach ($aparelhos as $a): ?>
                <option value="<?= htmlspecialchars($a['nome']) ?>"
                  <?= $sel_ap === $a['nome'] ? 'selected' : ($os['aparelho'] === $a['nome'] && !$sel_ap ? 'selected' : '') ?>>
                  <?= htmlspecialchars($a['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalAparelhos" title="Gerenciar"><i class="bi bi-gear"></i></button>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label small">Marca</label>
          <div class="input-group input-group-sm">
            <select name="marca" id="marca" class="form-select">
              <option value="">Selecione</option>
              <?php foreach ($marcas as $m): ?>
                <option value="<?= htmlspecialchars($m['nome']) ?>"
                  <?= $sel_ma === $m['nome'] ? 'selected' : ($os['marca'] === $m['nome'] && !$sel_ma ? 'selected' : '') ?>>
                  <?= htmlspecialchars($m['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalMarcas" title="Gerenciar"><i class="bi bi-gear"></i></button>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label small">Modelo</label>
          <input type="text" name="modelo" class="form-control form-control-sm" value="<?= htmlspecialchars($os['modelo']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label small">Defeito</label>
          <textarea name="defeito" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($os['defeito']) ?></textarea>
        </div>
        <div class="col-md-5">
          <label class="form-label small">Serviço Executado</label>
          <textarea name="servico" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($os['servico']) ?></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label small">Observações</label>
          <textarea name="observacoes" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($os['observacoes']) ?></textarea>
        </div>
        <div class="col-md-3">
          <label class="form-label small">Valor Serviço</label>
          <input type="text" name="valor_servico" class="form-control form-control-sm"
            value="<?= isset($os['valor_servico']) ? number_format((float)$os['valor_servico'], 2, ',', '.') : '' ?>"
            oninput="updateTotal()" onblur="formatCurrencyField(this)">
        </div>
        <div class="col-md-3">
          <label class="form-label small">Desconto</label>
          <input type="text" name="desconto" class="form-control form-control-sm"
            value="<?= isset($os['desconto']) ? number_format((float)$os['desconto'], 2, ',', '.') : '' ?>"
            oninput="updateTotal()" onblur="formatCurrencyField(this)">
        </div>
        <div class="col-md-3">
          <label class="form-label small">Valor Total</label>
          <input type="text" name="valor_total" class="form-control form-control-sm"
            value="<?= isset($os['valor_total']) ? number_format((float)$os['valor_total'], 2, ',', '.') : '' ?>" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label small">Status</label>
          <div class="input-group input-group-sm">
            <select name="status" id="status" class="form-select">
              <option value="">Selecione</option>
              <?php foreach ($status_opcoes as $s): ?>
                <option value="<?= htmlspecialchars($s['nome']) ?>"
                  <?= $sel_st === $s['nome'] ? 'selected' : ($os['status'] === $s['nome'] && !$sel_st ? 'selected' : '') ?>>
                  <?= htmlspecialchars($s['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalStatus" title="Gerenciar"><i class="bi bi-gear"></i></button>
          </div>
        </div>
      </div>
      <hr class="my-3">
      <div class="text-end">
        <button type="submit" class="btn btn-sm text-white" style="background:var(--brand-color)"><i class="bi bi-check-lg"></i> Atualizar</button>
      </div>
    </form>
  </main>

  <?php renderModalAparelho($aparelhos); ?>
  <?php renderModalMarca($marcas); ?>
  <?php renderModalStatus($status_opcoes); ?>

  <div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form method="POST" id="editForm">
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
          <form method="POST" id="deleteForm">
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
  <script src="js/total-calculation.js"></script>
  <script>
    function updateSelectAndModal(type, items, modalHtml) {
      var select = document.getElementById(type);
      if (select) {
        var currentVal = select.value;
        select.innerHTML = '<option value="">Selecione</option>';
        items.forEach(function(item) {
          var opt = document.createElement('option');
          opt.value = item.nome;
          opt.textContent = item.nome;
          select.appendChild(opt);
        });
        if (currentVal) select.value = currentVal;
      }
      var modalMap = {
        'aparelho': 'modalAparelhos',
        'marca': 'modalMarcas',
        'status': 'modalStatus',
        'bairro': 'modalBairros'
      };
      var modalId = modalMap[type];
      if (modalId && modalHtml) {
        var oldModal = document.getElementById(modalId);
        if (oldModal) {
          var temp = document.createElement('div');
          temp.innerHTML = modalHtml;
          var newModal = temp.querySelector('.modal');
          if (newModal) {
            oldModal.parentNode.replaceChild(newModal, oldModal);
          }
        }
      }
    }

    function submitModalForm(form, submitter) {
      var formData = new FormData(form);
      formData.append('ajax', '1');
      if (submitter && submitter.name) {
        formData.append(submitter.name, submitter.value || '1');
      }
      fetch(window.location.href, {
        method: 'POST',
        body: formData
      })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (data.success) {
          var modalEl = form.closest('.modal');
          if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
          }
          updateSelectAndModal(data.type, data.items, data.modalHtml);
        }
      })
      .catch(function() {});
    }

    document.addEventListener('submit', function(e) {
      var f = e.target;
      if (f.id === 'editForm' || f.id === 'deleteForm' || (f.id && f.id.match(/^add/))) {
        e.preventDefault();
        submitModalForm(f, e.submitter);
      }
    });

    document.addEventListener('click', function(e) {
      var btn = e.target.closest('.select-item');
      if (btn) {
        document.getElementById(btn.dataset.target).value = btn.dataset.value;
      }
    });

    document.addEventListener('click', function(e) {
      var btn = e.target.closest('.edit-item');
      if (btn) {
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-nome').value = btn.dataset.nome;
        document.getElementById('edit-submit').name = btn.dataset.prefix + '_edit';
        new bootstrap.Modal(document.getElementById('modalEditar')).show();
      }
    });

    document.addEventListener('click', function(e) {
      var btn = e.target.closest('.delete-item');
      if (btn) {
        document.getElementById('delete-id').value = btn.dataset.id;
        document.getElementById('delete-submit').name = btn.dataset.prefix + '_delete';
        document.getElementById('delete-message').innerHTML = 'Tem certeza que deseja excluir <strong>' + btn.dataset.nome + '</strong>?';
        new bootstrap.Modal(document.getElementById('modalConfirmarExclusao')).show();
      }
    });
  </script>
</body>
</html>
