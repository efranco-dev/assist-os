<?php
session_start();
require('conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
  header('location:/assist-os/');
  exit();
}

$sql = "SELECT * FROM `cadastro` WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);
$result = $statement->fetch(PDO::FETCH_ASSOC);
if (!$result) {
  $_SESSION['sucesso'] = true;
  header('location:/assist-os/');
  exit();
}

?>

<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Cadastros</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
  <header>

  </header>
  <main class="container">
    <div class="card my-4 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Editar cadastro</h2>
        <a class="btn btn-sm btn-danger" href="/assist-os"><i class="bi bi-box-arrow-left"></i> Voltar</a>
      </div>
      <div class="card-body">
        <form action="atualizar.php?id=<?= $result['id'] ?>" method="post">
          <div class="row g-1">
            <div class="col-md-4">
              <label class="form-label" for="nome">Nome</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input id="nome" value="<?= $result['nome'] ?>" autocomplete="off" placeholder="Nome"
                  class="form-control" type="text" name="nome" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-5">
              <label class="form-label" for="endereco">Endereço</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                <input id="endereco" value="<?= $result['endereco'] ?>" autocomplete="off" class="form-control"
                  type="text" name="endereco" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="bairro">Bairro</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-cursor "></i></span>
                <input id="bairro" value="<?= $result['bairro'] ?>" autocomplete="off" class="form-control" type="text"
                  name="bairro" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="telefone">Telefone</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input id="telefone" value="<?= $result['telefone'] ?>" autocomplete="off" class="form-control"
                  type="text" name="telefone" maxlength="15" oninput="maskPhone(event)">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="aparelho">Aparelho</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                <input id="aparelho" value="<?= $result['aparelho'] ?>" autocomplete="off" class="form-control"
                  type="text" name="aparelho" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="marca">Marca</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                <input id="marca" value="<?= $result['marca'] ?>" autocomplete="off" class="form-control" type="text"
                  name="marca" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="modelo">Modelo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                <input id="modelo" value="<?= $result['modelo'] ?>" autocomplete="off" class="form-control" type="text"
                  name="modelo" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="defeito">Defeito Relatado</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-bug-fill"></i></span>
                <input id="defeito" value="<?= $result['defeito'] ?>" autocomplete="off" class="form-control"
                  type="text" name="defeito" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="servico">Serviço Executado
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hammer"></i></span>
                <input id="servico" value="<?= $result['servico'] ?>" autocomplete="off" class="form-control"
                  type="text" name="servico" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="observacoes">Observações</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-text-fill"></i></span>
                <textarea id="observacoes" autocomplete="off" class="form-control" name="observacoes"
                  rows="1"><?= $result['observacoes'] ?></textarea>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="valor_servico">Valor do Serviço</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                <input id="valor_servico" autocomplete="off" class="form-control" type="text" name="valor_servico"
                  value="<?= isset($result['valor_servico']) ? number_format((float) $result['valor_servico'], 2, ',', '.') : '' ?>"
                  inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="desconto">Desconto</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-percent"></i></span>
                <input id="desconto" autocomplete="off" class="form-control" type="text" name="desconto"
                  value="<?= isset($result['desconto']) ? number_format((float) $result['desconto'], 2, ',', '.') : '' ?>"
                  inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="valor_total">Valor Total</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calculator-fill"></i></span>
                <input id="valor_total" autocomplete="off" class="form-control" type="text" name="valor_total"
                  value="<?= isset($result['valor_total']) ? number_format((float) $result['valor_total'], 2, ',', '.') : '' ?>"
                  readonly>
              </div>
            </div>
          </div>
          <div class="mt-3 text-end">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-arrow-counterclockwise"></i>
              Atualizar
            </button>
          </div>
        </form>
      </div>
    </div>
    <?php if (isset($_SESSION['sucesso']) && $_SESSION['sucesso']): ?>
      <div id="mensagemSucesso" class="alert alert-success alert-dismissible fade show mt-3" role="alert"
        style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
        <i class="bi bi-check-circle-fill"></i> Contato atualizado com sucesso!
      </div>
      <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

  </main>
  <footer>

  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <script>
    function maskPhone(e) {
      var v = e.target.value.replace(/\D/g, '');
      if (v.length > 10) {
        v = v.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
      } else if (v.length > 5) {
        v = v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      } else if (v.length > 2) {
        v = v.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
      }
      e.target.value = v;
    }

    function parseCurrency(value) {
      if (!value) return 0;
      value = value.replace(/\./g, '').replace(/,/g, '.').trim();
      var parsed = parseFloat(value);
      return isNaN(parsed) ? 0 : parsed;
    }

    function formatCurrency(value) {
      return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatCurrencyField(field) {
      if (!field.value.trim()) {
        field.value = '';
        updateTotal();
        return;
      }
      field.value = formatCurrency(parseCurrency(field.value));
      updateTotal();
    }

    function updateTotal() {
      var valorServicoField = document.getElementById('valor_servico');
      var descontoField = document.getElementById('desconto');
      var valorServico = parseCurrency(valorServicoField.value);
      var desconto = parseCurrency(descontoField.value);
      if (!valorServicoField.value.trim() && !descontoField.value.trim()) {
        document.getElementById('valor_total').value = '';
        return;
      }
      var total = valorServico - desconto;
      document.getElementById('valor_total').value = formatCurrency(total >= 0 ? total : 0);
    }

    document.addEventListener('DOMContentLoaded', updateTotal);
  </script>

  <script>
    // Fazer a mensagem de sucesso desaparecer após 4 segundos igual ao index.php
    const mensagem = document.getElementById('mensagemSucesso');
    if (mensagem) {
      setTimeout(() => {
        mensagem.classList.remove('show');
        setTimeout(() => {
          mensagem.remove();
        }, 150);
      }, 4000);
    }
  </script>
</body>
</html>