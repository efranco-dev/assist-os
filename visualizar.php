<?php

require('conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
  header('Location:/assist-os/');
  exit();
}

$sql = "SELECT * FROM `cadastro` WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);
$result = $statement->fetch(PDO::FETCH_ASSOC);
if (!$result) {
  header('Location:/assist-os/');
  exit();
}
?>

<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Visualizar</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
  <?php require('header.php'); ?>
  <main class="container py-4">
    <div class="card shadow-sm border-0">
      <div
        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h4 class="mb-1">Dados do cadastro</h4>
          <small class="text-muted"><i class="bi bi-clock-fill"></i>
            <?= date('d/m/Y H:i', strtotime($result['data_entrada'])) ?></small>
        </div>
        <a href="/" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-left"></i> Voltar</a>
      </div>
      <div class="card-body">
        <div class="row gx-4">
          <div class="col-12 col-md-6">
            <div class="list-group list-group-flush">
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-person-fill me-1"></i> Nome:</span>
                <span class="fw-semibold"> <?= $result['nome'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-geo-alt-fill me-1"></i> Endereço:</span>
                <span class="fw-semibold"> <?= $result['endereco'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-cursor me-1"></i> Bairro:</span>
                <span class="fw-semibold"> <?= $result['bairro'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-telephone-fill me-1"></i> Telefone:</span>
                <span class="fw-semibold"> <?= $result['telefone'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-phone-fill me-1"></i> Aparelho:</span>
                <span class="fw-semibold"> <?= $result['aparelho'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-tag-fill me-1"></i> Marca:</span>
                <span class="fw-semibold"> <?= $result['marca'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-box-seam me-1"></i> Modelo:</span>
                <span class="fw-semibold"> <?= $result['modelo'] ?></span>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="list-group list-group-flush">
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-bug-fill me-1"></i> Defeito:</span>
                <span class="fw-semibold"> <?= $result['defeito'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-hammer me-1"></i> Serviço Executado:</span>
                <span class="fw-semibold"> <?= $result['servico'] ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-cash-stack me-1"></i> Valor do Serviço:</span>
                <span class="fw-semibold"> <?= number_format($result['valor_servico'], 2, ',', '.') ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-percent me-1"></i> Desconto:</span>
                <span class="fw-semibold"> <?= number_format($result['desconto'], 2, ',', '.') ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-calculator-fill me-1"></i> Valor Total:</span>
                <span class="fw-semibold"> <?= number_format($result['valor_total'], 2, ',', '.') ?></span>
              </div>
              <div class="list-group-item px-0 py-2 border-bottom">
                <span class="text-secondary"><i class="bi bi-chat-text-fill me-1"></i> Observações:</span>
                <span class="fw-semibold d-block mt-1"> <?= nl2br(htmlspecialchars($result['observacoes'])) ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer>
  </footer>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/theme.js"></script>
</body>

</html>