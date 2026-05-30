<?php
session_start();
require('conexao.php');

$sql = "SELECT * FROM `cadastro`";
$statement = $pdo->query($sql);
$result = $statement->fetchAll((PDO::FETCH_ASSOC));
?>

<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Assist-OS</title>
  <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
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
        <h2 class="h5 mb-0">Cadastro de Clientes e Serviço</h2>
      </div>
      <div class="card-body">
        <form action="cadastrar.php" method="post">
          <div class="row g-1">
            <div class="col-md-4">
              <label class="form-label" for="nome">Nome</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input id="nome" autocomplete="off" class="form-control" type="text" name="nome"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-5">
              <label class="form-label" for="endereco">Endereço</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                <input id="endereco" autocomplete="off" class="form-control" type="text" name="endereco"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="bairro">Bairro</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                <select name="bairro" id="bairro" class="form-select">
                  <option value="">Selecione</option>
                  <option value="Vicente Pires">VICENTE PIRES</option>
                  <option value="Águas Claras">AGUAS CLARAS</option>
                  <option value="Ceilândia">CEILÂNDIA</option>
                  <option value="Taguatinga">TAGUATINGA</option>
                  <option value="Samambaia">SAMAMBAIA</option>
                  <option value="Outro">OUTRO</option>
                </select>
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
                  <option value="Microondas">MICROONDAS</option>
                  <option value="Tv de Led">TV DE LED</option>
                  <option value="Tv de Lcd">TV DE LCD</option>
                  <option value="Tv de Plasma">TV DE PLASMA</option>
                  <option value="Outro">OUTRA</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="marca">Marca</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag-fill"></i></i></span>
                <select name="marca" id="marca" class="form-select">
                  <option value="">Selecione</option>
                  <option value="Brastemp">BRASTEMP</option>
                  <option value="Consul">CONSUL</option>
                  <option value="Electrolux">ELECTROLUX</option>
                  <option value="Panasonic">PANASONIC</option>
                  <option value="Philco">PHICO</option>
                  <option value="Midea">MIDEA</option>
                  <option value="Samsung">SAMSUNG</option>
                  <option value="Tcl">TCL</option>
                  <option value="Semp">SEMP</option>
                  <option value="Lg">LG</option>
                  <option value="Outro">OUTRO</option>
                </select>
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
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="servico">Serviço Executado
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hammer"></i></span>
                <input id="servico" autocomplete="off" class="form-control" type="text" name="servico"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="observacoes">Observações</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-text-fill"></i></span>
                <textarea id="observacoes" autocomplete="off" class="form-control" name="observacoes"
                  rows="1"></textarea>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="valor_servico">Valor do Serviço</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                <input id="valor_servico" autocomplete="off" class="form-control" type="text" name="valor_servico"
                  inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="desconto">Desconto</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-percent"></i></span>
                <input id="desconto" autocomplete="off" class="form-control" type="text" name="desconto"
                  inputmode="decimal" oninput="updateTotal()" onblur="formatCurrencyField(this)">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="valor_total">Valor Total</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calculator-fill"></i></span>
                <input id="valor_total" autocomplete="off" class="form-control" type="text" name="valor_total" value=""
                  readonly>
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

    <?php if (isset($_SESSION['sucesso']) && $_SESSION['sucesso']): ?>
      <div id="mensagemSucesso" class="alert alert-success alert-dismissible fade show mt-3" role="alert"
        style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
        <i class="bi bi-check-circle-fill"></i> Contato criado com sucesso!
      </div>
      <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <a href="lista-clientes.php" class="btn btn-sm btn-outline-primary"><i class="bi bi-people-fill"></i> Ver Lista de
      Clientes</a>

  </main>
  <footer>

  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <script src="js/total-calculation.js"></script>
  <script src="js/mask-phone.js"></script>
  <script>
    // Fazer a mensagem de sucesso desaparecer após 4 segundos 
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