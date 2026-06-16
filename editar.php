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

$cliente_id = filter_input(INPUT_GET, 'cliente_id', FILTER_VALIDATE_INT);

$stmt = $pdo->prepare("
    SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone
    FROM ordens_servico os
    JOIN clientes c ON c.id = os.cliente_id
    WHERE os.id = :id
");
$stmt->execute([':id' => $id]);
$os = $stmt->fetch();
if (!$os) { header('Location: ordem-servico.php'); exit(); }

if ($cliente_id) {
    $os['cliente_id'] = $cliente_id;
    $stmt2 = $pdo->prepare("SELECT nome, telefone FROM clientes WHERE id = :id");
    $stmt2->execute([':id' => $cliente_id]);
    $novo_cliente = $stmt2->fetch();
    if ($novo_cliente) {
        $os['cliente_nome'] = $novo_cliente['nome'];
        $os['cliente_telefone'] = $novo_cliente['telefone'];
    }
}

$clientes = $pdo->query("SELECT id, nome, telefone FROM clientes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$status_opcoes = $pdo->query("SELECT * FROM status_opcoes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$marcas = $pdo->query("SELECT * FROM marcas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$aparelhos = $pdo->query("SELECT * FROM aparelhos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$bairros = $pdo->query("SELECT * FROM bairros ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
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
    <?php if (isset($_SESSION['sucesso']) && $_SESSION['sucesso']): ?>
      <div class="alert alert-success alert-dismissible fade show py-2 small">
        <i class="bi bi-check-circle-fill"></i> OS atualizada com sucesso!
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <!-- Client Selection -->
    <div class="card shadow-sm border mb-3">
      <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
        <h2 class="h5 mb-0"><i class="bi bi-person-fill"></i> Cliente</h2>
      </div>
      <div class="card-body">
        <div class="row g-2 align-items-end">
          <div class="col-md-3">
            <label class="form-label" for="clienteSelect">Selecionar existente</label>
            <select id="clienteSelect" class="form-select" onchange="if(this.value) window.location='?id=<?= $id ?>&cliente_id='+this.value">
              <option value="">— Selecione —</option>
              <?php foreach ($clientes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $os['cliente_id'] == $c['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['nome']) ?> — <?= htmlspecialchars($c['telefone']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-9">
            <form action="cadastrar.php" method="post" class="row g-1">
              <input type="hidden" name="redirect" value="editar.php?id=<?= $id ?>">
              <div class="col-md-3">
                <input type="text" name="nome" class="form-control" placeholder="Nome *" required>
              </div>
              <div class="col-md-2">
                <input type="text" name="telefone" class="form-control phone-mask" placeholder="Telefone">
              </div>
              <div class="col-md-2">
                <input type="text" name="endereco" class="form-control" placeholder="Endereço">
              </div>
              <div class="col-md-3">
                <div class="input-group">
                  <select name="bairro" id="bairro" class="form-select">
                    <option value="">Bairro</option>
                    <?php foreach ($bairros as $b): ?>
                      <option value="<?= htmlspecialchars($b['nome']) ?>"><?= htmlspecialchars($b['nome']) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalBairros" title="Gerenciar bairros"><i class="bi bi-gear"></i></button>
                </div>
              </div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-lg"></i></button>
              </div>
            </form>
          </div>
        </div>
        <div class="alert alert-success mt-2 mb-0 py-1 small">
          <i class="bi bi-person-check-fill"></i> Cliente: <strong><?= htmlspecialchars($os['cliente_nome']) ?></strong>
          <?php if ($os['cliente_telefone']): ?> — <?= htmlspecialchars($os['cliente_telefone']) ?><?php endif; ?>
        </div>
      </div>
    </div>

    <!-- OS Form -->
    <div class="card shadow-sm border">
      <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
        <h2 class="h5 mb-0"><i class="bi bi-clock-history"></i> Ordem de Serviço #<?= $os['id'] ?></h2>
        <a href="ordem-servico.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
      </div>
      <div class="card-body">
        <form action="atualizar.php?id=<?= $os['id'] ?>" method="post">
          <input type="hidden" name="cliente_id" value="<?= $os['cliente_id'] ?>">

          <div class="row g-1">
            <div class="col-md-3">
              <label class="form-label" for="aparelho">Aparelho</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                <select name="aparelho" id="aparelho" class="form-select">
                  <option value="">Selecione</option>
                  <?php foreach ($aparelhos as $a): ?>
                    <option value="<?= htmlspecialchars($a['nome']) ?>"
                      <?= $sel_ap === $a['nome'] ? 'selected' : ($os['aparelho'] === $a['nome'] && !$sel_ap ? 'selected' : '') ?>>
                      <?= htmlspecialchars($a['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalAparelhos" title="Gerenciar aparelhos"><i class="bi bi-gear"></i></button>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="marca">Marca</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                <select name="marca" id="marca" class="form-select">
                  <option value="">Selecione</option>
                  <?php foreach ($marcas as $m): ?>
                    <option value="<?= htmlspecialchars($m['nome']) ?>"
                      <?= $sel_ma === $m['nome'] ? 'selected' : ($os['marca'] === $m['nome'] && !$sel_ma ? 'selected' : '') ?>>
                      <?= htmlspecialchars($m['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalMarcas" title="Gerenciar marcas"><i class="bi bi-gear"></i></button>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="modelo">Modelo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                <input id="modelo" autocomplete="off" class="form-control" type="text" name="modelo" style="text-transform: uppercase;" value="<?= htmlspecialchars($os['modelo']) ?>">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="telefone_cliente">Telefone</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input id="telefone_cliente" class="form-control" type="text" value="<?= htmlspecialchars($os['cliente_telefone'] ?? '') ?>" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="defeito">Defeito Relatado</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-bug-fill"></i></span>
                <input id="defeito" autocomplete="off" class="form-control" type="text" name="defeito" style="text-transform: capitalize;" value="<?= htmlspecialchars($os['defeito']) ?>">
              </div>
            </div>
            <div class="col-md-5">
              <label class="form-label" for="servico">Serviço Executado</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hammer"></i></span>
                <input id="servico" autocomplete="off" class="form-control" type="text" name="servico" style="text-transform: capitalize;" value="<?= htmlspecialchars($os['servico']) ?>">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="observacoes">Observações</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-text-fill"></i></span>
                <textarea id="observacoes" autocomplete="off" class="form-control" name="observacoes" rows="2" style="text-transform: capitalize"><?= htmlspecialchars($os['observacoes']) ?></textarea>
              </div>
            </div>
            <div class="d-flex bg-light p-2 rounded-3 mt-2">
              <div class="col-md-3">
                <label class="form-label" for="valor_servico">Valor do Serviço</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                  <input id="valor_servico" autocomplete="off" class="form-control" type="text" name="valor_servico"
                    inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)"
                    value="<?= isset($os['valor_servico']) ? number_format((float)$os['valor_servico'], 2, ',', '.') : '' ?>">
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="desconto">Desconto</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-percent"></i></span>
                  <input id="desconto" autocomplete="off" class="form-control" type="text" name="desconto"
                    inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)"
                    value="<?= isset($os['desconto']) ? number_format((float)$os['desconto'], 2, ',', '.') : '' ?>">
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="valor_total">Valor Total</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-calculator-fill"></i></span>
                  <input id="valor_total" autocomplete="off" class="form-control" type="text" name="valor_total" value="<?= isset($os['valor_total']) ? number_format((float)$os['valor_total'], 2, ',', '.') : '' ?>" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="status">Status do Aparelho</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-clipboard-check-fill"></i></span>
                  <select name="status" id="status" class="form-select">
                    <option value="">Selecione</option>
                    <?php foreach ($status_opcoes as $s): ?>
                      <option value="<?= htmlspecialchars($s['nome']) ?>"
                        <?= $sel_st === $s['nome'] ? 'selected' : ($os['status'] === $s['nome'] && !$sel_st ? 'selected' : '') ?>>
                        <?= htmlspecialchars($s['nome']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalStatus" title="Gerenciar status"><i class="bi bi-gear"></i></button>
                </div>
              </div>
            </div>
            <div class="mt-3 text-end">
              <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Atualizar OS</button>
              <a href="ordem-servico.php" class="btn btn-sm btn-outline-secondary">Cancelar</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>

  <?php renderModalAparelho($aparelhos); ?>
  <?php renderModalMarca($marcas); ?>
  <?php renderModalStatus($status_opcoes); ?>
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

  <footer></footer>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/theme.js"></script>
  <script src="js/total-calculation.js"></script>
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
        var el = document.getElementById(btn.dataset.target);
        if (el) el.value = btn.dataset.value;
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
