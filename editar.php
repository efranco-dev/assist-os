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
  <title>Editar</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/all.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
  <header>
    <!-- Barra da marca -->
    <div class="container d-flex align-items-center gap-3 py-3">
      <div class="d-flex align-items-center justify-content-center rounded-3 text-white"
        style="width:48px; height:48px; background:#1a3a5c; flex-shrink:0;">
        <i class="bi bi-tools fs-4"></i>
      </div>
      <div>
        <h1 class="h5 mb-0 fw-semibold">Assist-OS</h1>
        <small class="text-muted fs-6">Assistência Técnica — Conserto de Microondas e TV em Geral</small>
      </div>
      <div class="ms-auto d-flex gap-2">
        <a href="lista-clientes.php" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
          <i class="bi bi-people-fill"></i> Clientes
        </a>
        <a href="index.php" class="btn btn-sm text-white d-flex align-items-center gap-1" style="background:#1a3a5c;">
          <i class="bi bi-person-fill-add"></i> Novo Cadastro
        </a>
      </div>
    </div>

    <!-- Navegação -->
    <div class="border-top bg-light">
      <div class="container mb-3">
        <nav class="nav">
          <a href="index.php" class="nav-link active d-flex align-items-center gap-1 fw-medium"
            style="border-bottom: 2px solid #1a3a5c; color:#1a3a5c;">
            <i class="bi bi-clipboard2-fill"></i> Cadastro de Clientes e Serviço
          </a>
          <a href="ordem-servico.php" class="nav-link text-muted d-flex align-items-center gap-1">
            <i class="bi bi-clock-history"></i> Ordens de Serviço
            <span class="badge rounded-pill text-white ms-1" style="background:#1a3a5c; font-size:10px;">12</span>
          </a>
          <a href="relatorios.php" class="nav-link text-muted d-flex align-items-center gap-1">
            <i class="bi bi-bar-chart-fill"></i> Relatórios
          </a>
        </nav>
      </div>
    </div>
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
                  type="text" name="endereco" style="text-transform: capitalize;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="bairro">Bairro</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-cursor "></i></span>
                <select name="bairro" id="bairro" class="form-select">
                  <option value="">Selecione</option>
                  <option value="Vicente Pires" <?= $result['bairro'] === 'Vicente Pires' ? 'selected' : '' ?>>Vicente Pires</option>
                  <option value="Águas Claras" <?= $result['bairro'] === 'Águas Claras' ? 'selected' : '' ?>>Águas Claras</option>
                  <option value="Ceilândia" <?= $result['bairro'] === 'Ceilândia' ? 'selected' : '' ?>>Ceilândia</option>
                  <option value="Taguatinga" <?= $result['bairro'] === 'Taguatinga' ? 'selected' : '' ?>>Taguatinga</option>
                  <option value="Samambaia" <?= $result['bairro'] === 'Samambaia' ? 'selected' : '' ?>>Samambaia</option>
                  <option value="Outro" <?= $result['bairro'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                </select>
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
                <select name="aparelho" id="aparelho" class="form-select">
                  <option value="">Selecione</option>
                  <option value="Microondas" <?= $result['aparelho'] === 'Microondas' ? 'selected' : '' ?>>Microondas
                  </option>
                  <option value="Tv de Led" <?= $result['aparelho'] === 'Tv de Led' ? 'selected' : '' ?>>Tv de Led</option>
                  <option value="Tv de Lcd" <?= $result['aparelho'] === 'Tv de Lcd' ? 'selected' : '' ?>>Tv de Lcd</option>
                  <option value="Tv de Plasma" <?= $result['aparelho'] === 'Tv de Plasma' ? 'selected' : '' ?>>Tv de Plasma
                  </option>
                  <option value="Outro" <?= $result['aparelho'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="marca">Marca</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                <select name="marca" id="marca" class="form-select">
                  <option value="">Selecione</option>
                  <option value="Brastemp" <?= $result['marca'] === 'Brastemp' ? 'selected' : '' ?>>Brastemp</option>
                  <option value="Consul" <?= $result['marca'] === 'Consul' ? 'selected' : '' ?>>Consul</option>
                  <option value="Electrolux" <?= $result['marca'] === 'Electrolux' ? 'selected' : '' ?>>Electrolux</option>
                  <option value="Panasonic" <?= $result['marca'] === 'Panasonic' ? 'selected' : '' ?>>Panasonic</option>
                  <option value="Philco" <?= $result['marca'] === 'Philco' ? 'selected' : '' ?>>Philco</option>
                  <option value="Midea" <?= $result['marca'] === 'Midea' ? 'selected' : '' ?>>Midea</option>
                  <option value="Samsung" <?= $result['marca'] === 'Samsung' ? 'selected' : '' ?>>Samsung</option>
                  <option value="Tcl" <?= $result['marca'] === 'Tcl' ? 'selected' : '' ?>>TCL</option>
                  <option value="Semp" <?= $result['marca'] === 'Semp' ? 'selected' : '' ?>>Semp</option>
                  <option value="Lg" <?= $result['marca'] === 'Lg' ? 'selected' : '' ?>>LG</option>
                  <option value="Outro" <?= $result['marca'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                </select>
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
                  type="text" name="defeito" style="text-transform: capitalize;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="servico">Serviço Executado
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hammer"></i></span>
                <input id="servico" value="<?= $result['servico'] ?>" autocomplete="off" class="form-control"
                  type="text" name="servico" style="text-transform: capitalize;">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="observacoes">Observações</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-text-fill"></i></span>
                <textarea id="observacoes" autocomplete="off" class="form-control" name="observacoes"
                  style="text-transform: capitalize;" rows="1"><?= $result['observacoes'] ?></textarea>
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
            <div class="col-md-4">
              <label class="form-label" for="status">Status</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-clipboard-check-fill"></i></span>
                <select id="status" name="status" class="form-select">
                  <option value="" <?= $result['status'] === '' ? 'selected' : '' ?>>Selecione</option>
                  <option value="Orçamento" <?= $result['status'] === 'Orçamento' ? 'selected' : '' ?>>Orçamento</option>
                  <option value="Em Analise" <?= $result['status'] === 'Em Analise' ? 'selected' : '' ?>>Em Análise
                  </option>
                  <option value="Autorizado" <?= $result['status'] === 'Autorizado' ? 'selected' : '' ?>>Autorizado
                  </option>
                  <option value="Aguardando Peças" <?= $result['status'] === 'Aguardando Peças' ? 'selected' : '' ?>>
                    Aguardando Peças</option>
                  <option value="Pronto" <?= $result['status'] === 'Pronto' ? 'selected' : '' ?>>Pronto</option>
                  <option value="Recusado" <?= $result['status'] === 'Recusado' ? 'selected' : '' ?>>Recusado</option>
                  <option value="Outro" <?= $result['status'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                </select>
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
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/total-calculation.js"></script>
  <script src="js/mask-phone.js"></script>
  <script src="js/mensagem-sucesso.js"></script>
</body>

</html>