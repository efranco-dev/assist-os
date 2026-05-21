<?php

require('conexao.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
  header('location:/crud-php');
  exit();
}

$sql = "SELECT * FROM `cadastro` WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);
$result = $statement->fetch(PDO::FETCH_ASSOC);
if (!$result) {
  header('location:/crud-php');
  exit();
}
?>

<!doctype html>
<html lang="pt-BR" data-bs-theme="light">

<head>
  <title>Visualização</title>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS v5.3.8 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
  <header>
  </header>
  <main class="container py-4">
    <div class="card shadow-sm border-0">
      <div
        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h4 class="mb-1">Dados do cadastro</h4>
          <small class="text-muted"><i class="bi bi-clock-fill"></i>
            <?= date('d/m/Y H:i', strtotime($result['data_entrada'])) ?></small>
        </div>
        <a href="/crud-php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-left"></i> Voltar</a>
      </div>
      <div class="card-body">
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
          <div class="list-group-item px-0 py-2 border-bottom">
            <span class="text-secondary"><i class="bi bi-bug-fill me-1"></i> Defeito:</span>
            <span class="fw-semibold"> <?= $result['defeito'] ?></span>
          </div>
          <div class="list-group-item px-0 py-2 border-bottom">
            <span class="text-secondary"><i class="bi bi-hammer me-1"></i> Serviço Executado:</span>
            <span class="fw-semibold"> <?= $result['servico'] ?></span>
          </div>
          <div class="list-group-item px-0 py-2 border-bottom">
            <span class="text-secondary"><i class="bi bi-chat-text-fill me-1"></i> Observações:</span>
            <span class="fw-semibold d-block mt-1"> <?= nl2br(htmlspecialchars($result['observacoes'])) ?></span>
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
        </div>
      </div>
    </div>
  </main>
  <footer>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
</body>

</html>