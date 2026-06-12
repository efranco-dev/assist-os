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
$sel_ba = $_SESSION['sel_ba'] ?? '';
unset($_SESSION['sel_ap'], $_SESSION['sel_ma'], $_SESSION['sel_st'], $_SESSION['sel_ba']);

$nome_edit     = $_GET['nome'] ?? '';
$endereco_edit = $_GET['endereco'] ?? '';
$bairro_edit   = $_GET['bairro'] ?? '';
$telefone_edit = $_GET['telefone'] ?? '';

$bairros = $pdo->query("SELECT * FROM bairros ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">
<head>
  <title>Novo Cliente - Assist-OS</title>
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
      <div class="card-header bg-transparent fw-semibold">
        <i class="bi bi-person-plus-fill"></i> Novo Cliente
      </div>
      <div class="card-body">
        <?php if (isset($_SESSION['sucesso_cliente'])): ?>
          <div class="alert alert-success alert-dismissible fade show py-2 small">
            <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['sucesso_cliente'] ?>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['sucesso_cliente']); ?>
        <?php endif; ?>

        <form action="cadastrar.php" method="post">
          <div class="mb-2">
            <label class="form-label small">Nome</label>
            <input type="text" name="nome" class="form-control form-control-sm" required value="<?= htmlspecialchars($nome_edit) ?>">
          </div>
          <div class="mb-2">
            <label class="form-label small">Endereço</label>
            <input type="text" name="endereco" class="form-control form-control-sm" value="<?= htmlspecialchars($endereco_edit) ?>">
          </div>
          <div class="row g-2 mb-2">
            <div class="col">
              <label class="form-label small">Bairro</label>
              <div class="input-group input-group-sm">
                <select name="bairro" id="bairro" class="form-select">
                  <option value="">Selecione</option>
                  <?php foreach ($bairros as $b): ?>
                    <option value="<?= htmlspecialchars($b['nome']) ?>"
                      <?= $sel_ba === $b['nome'] ? 'selected' : ($bairro_edit === $b['nome'] && !$sel_ba ? 'selected' : '') ?>>
                      <?= htmlspecialchars($b['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalBairros" title="Gerenciar bairros"><i class="bi bi-gear"></i></button>
              </div>
            </div>
            <div class="col">
              <label class="form-label small">Telefone</label>
              <input type="text" name="telefone" class="form-control form-control-sm phone-mask" value="<?= htmlspecialchars($telefone_edit) ?>">
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
  <script src="js/mask-phone.js"></script>
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
