<?php
session_start();
require('conexao.php');
require('gerenciar_opcoes.php');

$sel_ap = $_SESSION['sel_ap'] ?? '';
$sel_ma = $_SESSION['sel_ma'] ?? '';
$sel_st = $_SESSION['sel_st'] ?? '';
$sel_ba = $_SESSION['sel_ba'] ?? '';
unset($_SESSION['sel_ap'], $_SESSION['sel_ma'], $_SESSION['sel_st'], $_SESSION['sel_ba']);

$sql = "SELECT * FROM `cadastro`";
$statement = $pdo->query($sql);
$result = $statement->fetchAll((PDO::FETCH_ASSOC));
?>
<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Assist-OS</title>
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
        <h2 class="h5 mb-0">Cadastrar Cliente</h2>
      </div>
      <?php if (isset($_SESSION['sucesso']) && $_SESSION['sucesso']): ?>
        <div id="mensagemSucesso" class="alert alert-success alert-dismissible fade show mt-3" role="alert"
          style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
          <i class="bi bi-check-circle-fill"></i> Contato criado com sucesso!
        </div>
        <?php unset($_SESSION['sucesso']); ?>
      <?php endif; ?>

      <div class="card-body">
        <form action="cadastrar.php" method="post">
          <div class="row g-1">
            <div class="col-md-4">
              <label class="form-label" for="nome">Nome</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input id="nome" autocomplete="off" class="form-control" type="text" name="nome"
                  style="text-transform: capitalize;">
              </div>
            </div>
            <div class="col-md-5">
              <label class="form-label" for="endereco">Endereço</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                <input id="endereco" autocomplete="off" class="form-control" type="text" name="endereco"
                  style="text-transform: capitalize;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="bairro">Bairro</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-cursor "></i></span>
                <select name="bairro" id="bairro" class="form-select">
                  <option value="">Selecione</option>
                  <?php foreach ($bairros as $b): ?>
                    <option value="<?= htmlspecialchars($b['nome']) ?>"
                      <?= $sel_ba === $b['nome'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($b['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalBairros" title="Gerenciar bairros">
                  <i class="bi bi-gear"></i>
                </button>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label" for="telefone">Telefone</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input id="telefone" autocomplete="off" class="form-control" type="text" name="telefone" maxlength="15"
                  oninput="maskPhone(event)">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="aparelho">Aparelho</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                <select name="aparelho" id="aparelho" class="form-select">
                  <option value="">Selecione</option>
                  <?php foreach ($aparelhos as $a): ?>
                    <option value="<?= htmlspecialchars($a['nome']) ?>"
                      <?= $sel_ap === $a['nome'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($a['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalAparelhos" title="Gerenciar aparelhos">
                  <i class="bi bi-gear"></i>
                </button>
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
                      <?= $sel_ma === $m['nome'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($m['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalMarcas" title="Gerenciar marcas">
                  <i class="bi bi-gear"></i>
                </button>
              </div>
            </div>

            <div class="col-md-3">
              <label class="form-label" for="modelo">Modelo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                <input id="modelo" autocomplete="off" class="form-control" type="text" name="modelo"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="defeito">Defeito Relatado</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-bug-fill"></i></span>
                <input id="defeito" autocomplete="off" class="form-control" type="text" name="defeito"
                  style="text-transform: capitalize;">
              </div>
            </div>
            <div class="col-md-5">
              <label class="form-label" for="servico">Serviço Executado
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hammer"></i></span>
                <input id="servico" autocomplete="off" class="form-control" type="text" name="servico"
                  style="text-transform: capitalize;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="observacoes">Observações</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-text-fill"></i></span>
                <textarea id="observacoes" autocomplete="off" class="form-control" name="observacoes" rows="2"
                  style="text-transform: capitalize"></textarea>
              </div>
            </div>
            <div class="d-flex bg-light p-2 rounded-3 mt-2">
              <div class="col-md-3">
                <label class="form-label" for="valor_servico">Valor do Serviço</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                  <input id="valor_servico" autocomplete="off" class="form-control" type="text" name="valor_servico"
                    inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)">
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="desconto">Desconto</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-percent"></i></span>
                  <input id="desconto" autocomplete="off" class="form-control" type="text" name="desconto"
                    inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)">
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="valor_total">Valor Total</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-calculator-fill"></i></span>
                  <input id="valor_total" autocomplete="off" class="form-control" type="text" name="valor_total"
                    value="" readonly>
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
                        <?= $sel_st === $s['nome'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['nome']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalStatus" title="Gerenciar status">
                    <i class="bi bi-gear"></i>
                  </button>
                </div>
              </div>

            </div>
            <div class="mt-3 text-end">
              <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-person-fill-add"></i>
                Cadastrar</button>
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
        <form method="POST">
          <div class="modal-header">
            <h6 class="modal-title">Editar</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">
            <input type="hidden" name="edit_action" id="edit-action">
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
        <div class="modal-header">
          <h6 class="modal-title">Confirmar Exclusão</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="delete-message">Tem certeza que deseja excluir?</p>
        </div>
        <div class="modal-footer">
          <form method="POST" id="delete-form">
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
  <script src="js/mensagem-sucesso.js"></script>
  <script>
    document.querySelectorAll('.select-item').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById(this.dataset.target).value = this.dataset.value;
      });
    });
    document.querySelectorAll('.edit-item').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('edit-id').value = this.dataset.id;
        document.getElementById('edit-nome').value = this.dataset.nome;
        document.getElementById('edit-action').name = this.dataset.prefix + '_edit';
        document.getElementById('edit-submit').name = this.dataset.prefix + '_edit';
        var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
        modal.show();
      });
    });
    document.querySelectorAll('.delete-item').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('delete-id').value = this.dataset.id;
        document.getElementById('delete-submit').name = this.dataset.prefix + '_delete';
        document.getElementById('delete-message').innerHTML =
          'Tem certeza que deseja excluir <strong>' + this.dataset.nome + '</strong>?';
        var modal = new bootstrap.Modal(document.getElementById('modalConfirmarExclusao'));
        modal.show();
      });
    });
  </script>
</body>

</html>
